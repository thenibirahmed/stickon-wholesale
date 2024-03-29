
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
                    <div class="row">
                        <div class="col-md-3 pt-4">
                            <b>{{ __('All Orders') }}</b>
                        </div>
                        <div class="col-md-4">
                            <label><b>From</b></label>
                            <input wire:model="from" type="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label><b>To</b></label>
                            <input wire:model="to" type="date" class="form-control">
                        </div>
                        <div class="col-md-1 mt-4">
                            <button wire:click="reset_filter" class="btn btn-primary">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    
                    <table class="table table-striped table-bordered table-hover table-responsive-md">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Done By</th>
                                <th>Subtotal</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Placed at</th>
                                <th>Products</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @forelse ($allOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        @if (auth()->user()->role == "Admin")
                                            {!! $order->user ? "<a href=". route("user.edit",['user'=> $order->user->id]) . ">" . $order->user->name . "</a>"  : 'Not found' !!}
                                        @else
                                            {!! $order->user ? $order->user->name : 'Not found' !!}                        
                                        @endif
                                    </td>
                                    <td>{{ $order->subtotal ?? '' }}</td>
                                    <td>{{ $order->discount  ?? '' }}</td>
                                    <td>{{ $order->total ?? '' }}</td>
                                    <td>{{ $order->paid ?? '' }}</td>
                                    <td>{{ $order->due ?? '' }}</td>
                                    <td>{{ $order->status ?? '' }}</td>
                                    <td>{{ $order->created_at->format("d-M-Y | h:i a") }}</td>
                                    <td>
                                        @if ( auth()->user()->role == "Admin" || auth()->user()->role == "Employee" )
                                            @if ( $order->is_seen )
                                                <a class="btn btn-success" href="{{ route('orders.show',['order'=>$order->id]) }}">Show</a>
                                            @else
                                                <a class="btn btn-primary" href="{{ route('orders.show',['order'=>$order->id]) }}">Show</a>
                                            @endif
                                        @else
                                            <a class="btn btn-primary" href="{{ route('orders.show',['order'=>$order->id]) }}">Show</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ( auth()->user()->role == "Admin" )
                                            <button class="btn btn-danger" wire:click="delete({{ $order->id }})" data-toggle="modal" data-target="#exampleModal">Delete</button>
                                        @else
                                            <p>Only for admin</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">{{ __('No Orders Found') }}</td>
                                </tr> 
                            @endforelse
                        </tbody>
                    </table>

                    {{ $allOrders->links() }}

                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Confirm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true close-btn">×</span>
                            </button>
                        </div>
                       <div class="modal-body">
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                            <button type="button" wire:click="modalDelete()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            


        </div>
    </div>
</div>




