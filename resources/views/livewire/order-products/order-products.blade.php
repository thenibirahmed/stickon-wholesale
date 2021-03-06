
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <b>Order ID - {{ $order['id'] }}</b>
                    <b class="ml-3">
                        <a href="{{ route("order.download-product-images",['order'=>$order['id']]) }}"
                            onclick="return confirm('Download all product images?')">
                            Download All Images
                        </a>
                    </b>
                </div>

                <div class="card-body">
                    
                    @if (session('updated'))
                        <div class="alert alert-success" role="alert">
                            {{ session('updated') }}
                        </div>
                    @endif

                    
                    <table class="table table-striped table-bordered table-hover table-responsive-md">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Attribute</th>
                                <th>Quantity</th>
                                <th>Last Updated</th>
                                <th>Status</th>
                                <th>Update</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orderProducts as $orderProduct)
                                <tr>
                                    {{-- {{ dd(Storage::url($orderProduct->image->path)) }} --}}
                                    <td>{!! $orderProduct->image ? "<a target='_blank' href=". Storage::url($orderProduct->image->path) . "> <img src=" . Storage::url($orderProduct->image->path) . " width='100px' /> </a>"   : "Not Found" !!}</td>
                                    <td>{{ $orderProduct->product ? $orderProduct->product->name : "Not Found" }}</td>
                                    <td>{{ $orderProduct->attribute ? $orderProduct->attribute->value : "Not Found" }}</td>
                                    <td>{{ $orderProduct->quantity ?? "Not Found" }}</td>
                                    <td>{{ $orderProduct->updated_at->format("d-M-Y | h:i a") }}</td>
                                    <form action="{{ route("update.order.products",['order_product'=>$orderProduct->id]) }}" method="POST">
                                        @csrf
                                        @method("PATCH")
                                        <td>
                                            <select @if ( auth()->user()->role == "Viewer" || auth()->user()->role == "Customer" ) disabled @endif class="form-control" name="status">
                                                <option @if( $orderProduct->status == "Processing" ) selected @endif value="Processing">Processing</option>
                                                <option @if( $orderProduct->status == "Completed" ) selected @endif value="Completed">Completed</option>
                                                <option @if( $orderProduct->status == "Declined" ) selected @endif value="Declined">Declined</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if ( auth()->user()->role == "Admin" )
                                                <input type="submit" class="btn btn-primary" value="Update">
                                            @else
                                                <p>Only for admin</p>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                            @empty
                                {{ __('No Orders Products Found') }}
                            @endforelse
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md">
                            @if ( $order['comment'] )
                                <h2 class="mt-3">Order Message</h2>
                                {{ $order['comment'] }}
                            @endif
                        </div>
                        <div class="col-md">
                            @if (session()->has('saved'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('saved') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <h4 class="mt-4">Overview</h4>
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td>Status</td>
                                    @if ( auth()->user()->role == "Admin" )  
                                        <td>
                                            <select wire:model="status" class="form-control">
                                                <option value="In Process">In Process</option>
                                                <option value="In Delivery">In Delivery</option>
                                                <option value="Delivered">Delivered</option>
                                                <option value="Refunded">Refunded</option>
                                                <option value="Pending Payment">Pending Payment</option>
                                            </select>
                                        </td>
                                    @else
                                        <td>{{ $status }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td>{{ $order['subtotal'] }}</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    @if ( auth()->user()->role == "Admin" )   
                                        <td><input type="number" class="form-control" wire:model.defer="discount"></td>
                                    @else
                                    <td>{{ $discount }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{ $order['total'] }}</td>
                                </tr>
                                <tr>
                                    <td>Paid</td>
                                    @if ( auth()->user()->role == "Admin" )  
                                        <td><input type="number" class="form-control" wire:model.defer="paid"></td>
                                    @else
                                        <td>{{ $paid }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Due</td>
                                    <td>{{ $order['due'] }}</td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route("orders.index") }}" class="btn btn-info text-light">Back</a>
                                    @error('discount') <span class="text-danger">{{ $message }}</span> @enderror
                                    @error('paid') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col text-right">
                                    @if ( auth()->user()->role == "Admin" )  
                                        <button class="btn btn-success" wire:click="updateOrder">Save</button>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


