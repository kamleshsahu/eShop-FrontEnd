<aside class="col-md-3 padding-bottom">
    <div class="list-group mb-1">
        <a href="{{ route('my-orders') }}" class="list-group-item"> <span><em class="fa fa-reorder"></em> All Orders</span></a>
        <a href="{{ route('my-orders', 'processed') }}" class="list-group-item"> <span><em class="fa fa-tasks"></em> In-Process</span></a>
        <a href="{{ route('my-orders', 'received') }}" class="list-group-item"> <span><em class="fa fa-list-ul"></em> Received</span></a>
        <a href="{{ route('my-orders', 'shipped') }}" class="list-group-item"> <span><em class="fa fa-ship"></em> Shipped</span></a>
        <a href="{{ route('my-orders', 'delivered') }}" class="list-group-item"> <span><em class="fa fa-truck"></em> Delivered</span></a>
        <a href="{{ route('my-orders', 'cancelled') }}" class="list-group-item"> <span><em class="fa fa-ban"></em> Cancelled</span></a>
        <a href="{{ route('my-orders', 'returned') }}" class="list-group-item"> <span><em class="fa fa-undo"></em> Returned</span></a>   
   </div>
</aside>