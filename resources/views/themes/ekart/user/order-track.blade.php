<section class="section-content padding-bottom">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-2">  
                    <div class="row mt-2 mb-0">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Ordered Id : {{ $data['list']->id }}</span><br>
                            <span class="text-dark product-name">Order Date : 23-08-2020</span>
                        </div>
                    </div>
                    <hr class="m-0">
                    @if(isset($data['list']->items) && is_array($data['list']->items) && count($data['list']->items))
                        @foreach($data['list']->items as $itm)
                            @php
                            $allStatus = ['received' => 0, 'processed' => 1, 'shipped' => 2, 'delivered' => 3];
                            $orderPlaced = "";
                            $orderProcessed = "";
                            $orderShipped = "";
                            $orderDelivered = "";
                            $orderCancelled = "";
                            $orderReturned = "";
                            foreach($itm->status as $s){
                                if($s[0] == "received"){
                                    $orderPlaced = $s[1];
                                }elseif($s[0] == "processed"){
                                    $orderProcessed = $s[1];
                                }elseif($s[0] == "shipped"){
                                    $orderShipped = $s[1];
                                }elseif($s[0] == "delivered"){
                                    $orderDelivered = $s[1];
                                }elseif($s[0] == "cancelled"){
                                    $orderCancelled = $s[1];
                                }elseif($s[0] == "returned"){
                                    $orderReturned = $s[1];
                                }
                            }
                            @endphp
                            <div class="row no-gutters mb-3">
                                <div class="col-sm-5">
                                    <img class="ml-2 fav-image" src="{{ $itm->image }}" alt="{{ $itm->name ?? '' }}">
                                </div>
                                <div class="col-sm-7">
                                    <div class="card-body">
                                        <a href="#" class="card-title text-dark">{{ strtoupper($itm->name) }} <small>{{ strtoupper(($itm->measurement ?? '') ." ". ($itm->unit ?? ''))}}</small></a>
                                        <p class="small text-muted mb-0">Qty : {{ $itm->quantity }}</p>
                                        <p class="card-text mb-0">
                                            <span class="font-weight-bold text-dark">{{ get_price($itm->sub_total) }}</span>
                                        </p>
                                        <small class="text-primary font-weight-bold">
                                            Via {{ strtoupper($data['list']->payment_method ?? '') }}
                                        </small>
                                        <div class="row">
                                            <p>
                                                <span class="form-group ml-3 font-weight-bold text-success">{{ strtoupper($itm->active_status) }}</span>
                                            </p>
                                            @if($orderCancelled == "")
                                                @if(intval($itm->cancelable_status) && intval($allStatus[$itm->active_status] ?? 0) <= intval($allStatus[$itm->till_status ?? 0]))
                                                    <span align="right" class="form-group col add-to-fav1">
                                                        <a role="button" href="{{ route('order-item-status', ['orderId' => $data['list']->id, 'orderItemId' => $itm->id, 'status' => 'cancelled'] ) }}" data-confirm="Are you sure, you want to cancel this item?">
                                                            <button class="btn btn-sm btn-primary">
                                                                Cancel Item
                                                            </button>
                                                        </a>
                                                    </span>
                                                @endif
                                                @if($orderDelivered != "" && intval($itm->return_status))
                                                    <span align="right" class="form-group col add-to-fav1">
                                                        <a role="button" href="{{ route('order-item-status', ['orderId' => $data['list']->id, 'orderItemId' => $itm->id, 'status' => 'returned'] ) }}" data-confirm="Are you sure, you want to return this item?">
                                                            <button class="btn btn-sm btn-primary">
                                                                Return Item
                                                            </button>
                                                        </a>
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(count($itm->status))
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row bs-wizard">
                                            @if($orderPlaced != "")
                                                <div class="col-3 col-md-3 bs-wizard-step complete">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Placed</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot activeStep"></a>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderPlaced)) }}</div>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date('h:i:s A', strtotime($orderPlaced)) }}</div>
                                                </div>
                                            @endif

                                            @if($orderProcessed != "")
                                                <div class="col-3 col-md-3 bs-wizard-step complete">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Processed</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot activeStep"></a>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderProcessed)) }}</div>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderProcessed)) }}</div>
                                                </div>
                                            @elseif($orderCancelled == "")
                                                <div class="col-3 col-md-3 bs-wizard-step disabled">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Processed</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                            @endif

                                            @if($orderShipped != "")
                                                <div class="col-3 col-md-3 bs-wizard-step complete">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Shipped</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot activeStep"></a>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderShipped)) }}</div>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderShipped)) }}</div>
                                                </div>
                                            @elseif($orderCancelled == "")
                                                <div class="col-3 col-md-3 bs-wizard-step disabled">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Shipped</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                            @endif

                                            @if($orderDelivered != "")
                                                <div class="col-3 col-md-3 bs-wizard-step complete">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Delivered</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot activeStep"></a>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderDelivered)) }}</div>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderDelivered)) }}</div>
                                                </div>
                                            @elseif($orderCancelled == "")
                                                <div class="col-3 col-md-3 bs-wizard-step disabled">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Delivered</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot"></a>
                                                </div>
                                            @endif

                                            @if($orderCancelled != "")
                                                <div class="col-3 col-md-3 bs-wizard-step complete">
                                                    <div class="text-center bs-wizard-stepnum text-muted">Order Cancelled</div>
                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                    <a href="#" class="bs-wizard-dot activeStep"></a>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderCancelled)) }}</div>
                                                    <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderCancelled)) }}</div>
                                                </div>
                                            @endif

                                            @if($itm->applied_for_return == true)
                                                @if($orderReturned != "")
                                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                                        <div class="text-center bs-wizard-stepnum text-muted">Order Returned</div>
                                                        <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderReturned)) }}</div>
                                                        <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderReturned)) }}</div>
                                                    </div>
                                                @else
                                                    <div class="col-3 col-md-3 bs-wizard-step disabled">
                                                        <div class="text-center bs-wizard-stepnum text-muted">Order Returned</div>
                                                        <div class="progress"><div class="progress-bar"></div></div>
                                                        <a href="#" class="bs-wizard-dot"></a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="row no-gutters mt-1">
            <div class="col-md-12">
                <div class="card shadow mb-2">  
                    <span class="m-2 font-weight-bold text-dark">Price Detail</span>
                   
                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Items Amount : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">{{ get_price($data['list']->total, false)}}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Delivery Charge : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">+ {{ get_price($data['list']->delivery_charge, false)}}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Tax({{ $data['list']->tax_percentage}}%) : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">+ {{ get_price($data['list']->tax_amount, false) }}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Discount(0%) : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">- {{ get_price($data['list']->discount, false)}}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Total : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">{{ get_price(floatval($data['list']->total) + floatval($data['list']->delivery_charge) + floatval($data['list']->tax_amount) - floatval($data['list']->discount), false)}}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">PromoCode Discount : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">- {{ get_price($data['list']->promo_discount, false) }}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark product-name">Wallet Balance : </span>
                        </div>
                        <div class="form-group">
                           <span class="price">-{{ get_price($data['list']->wallet_balance, false) }}</span>
                        </div>
                    </div>

                    <div class="row mr-5">
                        <div class="ml-2 form-group col">
                            <span class="text-dark font-weight-bold">Final Total : </span>
                        </div>
                        <div class="form-group">
                           <span class="text-primary font-weight-bold">{{ get_price($data['list']->final_total, false) }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div> 

        <div class="row no-gutters mt-1">
            <div class="col-md-12">
                <div class="card shadow mb-2">  
                    <span class="m-2 font-weight-bold text-dark">Other Details</span>
                    <div class="ml-4">
                        <div class="row">
                            <div class="form-group">
                                <span class="text-dark product-name">Name : {{ $data['list']->user_name }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <span class="text-dark product-name">Mobile No : {{ $data['list']->mobile }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <span class="text-dark product-name">Address : {{ $data['list']->address ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="row no-gutters mt-1">
            <div class="col-md-12">
                <div class="card shadow mb-2">  
                    <span class="m-2 font-weight-bold text-dark">Order Status</span>
                    <div class="card-body">
                        @if(count($data['list']->status))
                            @php
                            $orderPlaced = "";
                            $orderProcessed = "";
                            $orderShipped = "";
                            $orderDelivered = "";
                            $orderCancelled = "";
                            $orderReturned = "";
                            foreach($data['list']->status as $s){
                                if($s[0] == "received"){
                                    $orderPlaced = $s[1];
                                }elseif($s[0] == "processed"){
                                    $orderProcessed = $s[1];
                                }elseif($s[0] == "shipped"){
                                    $orderShipped = $s[1];
                                }elseif($s[0] == "delivered"){
                                    $orderDelivered = $s[1];
                                }elseif($s[0] == "cancelled"){
                                    $orderCancelled = $s[1];
                                }elseif($s[0] == "returned"){
                                    $orderReturned = $s[1];
                                }
                            }
                            @endphp
                            <div class="row bs-wizard">
                                @if($orderPlaced != "")
                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Placed</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderPlaced)) }}</div>
                                        <div class="bs-wizard-info text-center text-muted">{{ date('h:i:s A', strtotime($orderPlaced)) }}</div>
                                    </div>
                                @endif

                                @if($orderProcessed != "")
                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Processed</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderProcessed)) }}</div>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderProcessed)) }}</div>
                                    </div>
                                @elseif($orderCancelled == "")
                                    <div class="col-3 col-md-3 bs-wizard-step disabled">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Processed</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                    </div>
                                @endif

                                @if($orderShipped != "")
                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Shipped</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderShipped)) }}</div>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderShipped)) }}</div>
                                    </div>
                                @elseif($orderCancelled == "")
                                    <div class="col-3 col-md-3 bs-wizard-step disabled">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Shipped</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                    </div>
                                @endif

                                @if($orderDelivered != "")
                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Delivered</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderDelivered)) }}</div>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderDelivered)) }}</div>
                                    </div>
                                @elseif($orderCancelled == "")
                                    <div class="col-3 col-md-3 bs-wizard-step disabled">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Delivered</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                    </div>
                                @endif

                                @if($orderCancelled != "")
                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Cancelled</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderCancelled)) }}</div>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderCancelled)) }}</div>
                                    </div>
                                @endif

                                @if($orderReturned != "")
                                    <div class="col-3 col-md-3 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum text-muted">Order Returned</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot activeStep"></a>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("d-m-Y", strtotime($orderReturned)) }}</div>
                                        <div class="bs-wizard-info text-center text-muted">{{ date("h:i:s A", strtotime($orderReturned)) }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif 
                    </div>
                </div>
            </div>
        </div> 
        
        <!--- cancel confirm box -->
        <div class="modal" id="modal">
            <div class="modal-dialog ml-5">
                <div class="modal-content">
                    <div class="modal-body">
                        <span class="text-dark text-center">Are you sure, you want to return item?</span>
                        <div class="row add-to-fav1 mr-1">
                            <a href="" id="modal-btn-yes" class="btn text-primary font-weight-bold">Yes</a>
                            <button type="button" class="btn font-weight-bold text-primary" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--- cancel confirm box -->
    </div>  
</section>
<script>
$(document).ready(function() {
    $('a[data-confirm]').click(function(ev) {
        var href = $(this).attr('href');
        $('#modal').find('.modal-title').text($(this).attr('data-confirm'));
        $('#modal-btn-yes').attr('href', href);
        $('#modal').modal({show:true});
        return false;
    });
});
</script>