<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class OrderController extends Controller {

    /**
     * Make an order
     *
     * @return order
     */
    public function makeOrder() {
        return view( 'order.make-order' );
    }

    public function individual( User $user ) {
        // dd($user->order);

        return view( 'order.individual-user-order', [
            'allOrders' => $user->order()->paginate( 15 ),
        ] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view( 'order.all-orders' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request ) {
        //
    }

    /**
     * Dowload order product images
     *
     * @param $order
     * @return void
     */
    public function download_prodcut_images( Order $order ) {
        $zip        = new ZipArchive();
        $zipcreated = Storage::path( $order->id . ".zip" );

        if ( $zip->open( $zipcreated, \ZipArchive::CREATE | \ZipArchive::OVERWRITE ) ) {

            foreach ( $order->order_products as $product ) {

                if ( $product->image ) {
                    $nameParts = explode( "/", $product->image->path );
                    $name      = $nameParts[array_key_last( $nameParts )]; // Get the last part of the name from the link like /path/to/file.php returns file.php
                    $zip->addFile( Storage::path( $product->image->path ), $name );
                }

            }

        }

        $zip->close();

        return response()->download( $zipcreated )->deleteFileAfterSend( true );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id ) {
        $order = Order::findOrFail( $id );
        if ( auth()->user()->role == "Admin" ) {
            if ( $order->is_seen == 0 ) {
                $order->update( [
                    "is_seen" => 1,
                ] );
            }
        }

        return view( "order-products.order-products", [
            'orderID' => $order->id,
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Order $order ) {

        $order->delete();

        return redirect()->route( 'user.order', ['user' => $order->user->id] )->with( 'deleted', 'Deleted Successfully' );
    }

}
