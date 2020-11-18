<section class="padding-bottom footerfix section-content">   
    <div class="container mt-5">
        <div class="row">
            @include("themes.".get('theme').".user.order-sidebar")
            <div class="col-md-9">
                @if(isset($data['list']) && isset($data['list']['data']) && count($data['list']['data']))
                    @foreach($data['list']['data'] as $w)
                        @if(isset($w->items) && is_array($w->items) && count($w->items))
                            @foreach($w->items as $itm)
                                @if(isset($itm->id) && intval($itm->id))
                                    <div class="card shadow mb-4">
                                        <div class="row mt-2 mb-0">
                                            <div class="ml-2 form-group col">
                                                <span class="text-dark product-name">Ordered Id : {{ $itm->order_id ?? '-'}}</span><br>
                                                <span class="text-dark product-name">Order Date :  {{ isset($itm->date_added) ? date('d-m-Y', strtotime($itm->date_added)) : '' }}</span>
                                            </div>
                                            <div class="mr-5 form-group">
                                                <div class="wallet-header">
                                                    <a href="{{ route('order-track-item', $w->id ?? 0) }}"><button class="btn btn-sm btn-primary">View Details</button></a>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row no-gutters">
                                            <div class="col-sm-5 text-center">
                                                <img class="fav-image text-left" src="{{ $itm->image }}" alt="{{ $itm->name ?? 'Product Image'}}">
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="card-body">
                                                    <a href="#" class="card-title text-dark">{{ $itm->name }}</a>
                                                    <p class="small text-muted mb-0">Qty : {{ $itm->quantity }}</p>
                                                    <p class="card-text mb-0">
                                                        <span class="font-weight-bold text-dark">Rs {{ get_price($itm->sub_total) }}</span>
                                                    </p>
                                                    <small class="text-primary font-weight-bold">
                                                        Via {{ strtoupper($w->payment_method) }}
                                                    </small>
                                                    <p>
                                                        <span class="text-muted font-weight-bold">{{ strtoupper($itm->active_status) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($itm->status))
                                        @php
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
                                        <div class="card shadow mb-4">
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
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <div class="row text-center">
                        <div class="col-12">
                            <br><br>
                            <h3>No Orders Found.</h3>
                        </div>
                        <div class="col-12">
                            <br><br>
                            <a href="{{ route('shop') }}" class="btn btn-primary"><em class="fa fa-chevron-left mr-1"></em> Continue shopping</a>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col">
                        @if(isset($data['last']) && $data['last'] != "")
                            <a href="{{ $data['last'] }}" class="btn btn-primary pull-left text-white"><em class="fa fa-arrow-left"></em> Previous</a>
                        @endif
                    </div>
                    <div class="col">
                        @if(isset($data['next']) && $data['next'] != "")
                            <a href="{{ $data['next'] }}" class="btn btn-primary pull-right text-white">Next <em class="fa fa-arrow-right"></em></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>