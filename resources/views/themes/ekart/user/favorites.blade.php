<section class="section-content padding-bottom">   
    <div class="container mt-5">
        @if(isset($data['list']['data']) && is_array($data['list']['data']) && count($data['list']['data']))
            @foreach($data['list']['data'] as $itm)
                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">  
                            <div class="row no-gutters">
                                <div class="col-sm-5 text-center">
                                    <img class="fav-image" src="{{ $itm->image }}" alt="{{ $tim->name ?? 'Product Image' }}">
                                </div>
                                <div class="col-sm-7">
                                    <div class="card-body">
                                        <div class="add-to-fav1">                                            
                                            <a href="{{ route('favourite-remove', $itm->product_id) }}" class="fav">
                                                <em class="fa fa-heart"></em>
                                            </a>
                                        </div>
                                        <a href="{{ route('product-single', $itm->slug) }}" class="card-title font-weight-bold ">{{ strtoupper($itm->name) }}</a>
                                        <form action="{{ route('cart-add') }}" method="POST">
                                            <p class="text-muted" id="price_mrp_{{ $itm->id }}"><del>Price: <span class='value'>{!! print_mrp($itm) !!}</span></del></p>
                                            <h5 class="font-weight-bold" id="price_offer_{{ $itm->id }}">Offer Price: <span class='value'>60</span></h5>
                                            <h5 class="font-weight-bold" id="price_regular_{{ $itm->id }}">Price: <span class='value'>60</span></h5>
                                            <small class="text-primary" id="price_savings_{{ $itm->id }}">You Save: Rs <span class='value'>10.0 (10%)</span></small>
                                            
                                            <input type="hidden" name='id' value='{{ $itm->product_id }}'>
                                            <input type="hidden" name="type" value='add'>
                                            <input type="hidden" name="child_id" value='{{ $itm->variants[0]->id }}' id="child_{{ $itm->id }}">
                                            <div class="button-container">
                                                <button class="cart-qty-minus button-minus" type="button" id="button-minus" value="-">-</button>
                                                <input class="form-control qtyPicker" id="qtyPicker_{{ $itm->id }}" type="number" name="qty" data-min="0" min="1" max="{{ $itm->variants[0]->stock }}" data-max="{{ $itm->variants[0]->stock }}" value="1">
                                                <button class="cart-qty-plus button-plus" type="button" id="button-plus" value="+">+</button>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="form-group col">
                                                    <div class="btn-group-toggle variant" data-toggle="buttons">
                                                        @php $firstSelected = true; @endphp
                                                        @foreach($itm->variants as $v)
                                                            <button class="btn" data-id="{{ $itm->id }}">
                                                                <span class="text-dark name">{{ get_varient_name($v) }}</span><br>
                                                                <small>1 option from {{ get_price_varients($v) }}</s></small>
                                                                <input type="radio" name="options" id="option{{ $v->id }}" value="{{ $v->id }}" data-id='{{ $v->id }}' data-price='{{ get_price_varients($v) }}' data-mrp='{{ get_mrp_varients($v) }}' data-savings='{{ get_savings_varients($v) }}' data-stock='{{ intval(getMaxQty($v)) }}' autocomplete="off" >
                                                            </button>
                                                            @if($firstSelected == true)
                                                                {{ $firstSelected = false }}
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group text-left add-to-cart1">
                                                <button type="submit" name="submit" class="btn"> 
                                                    <em class="fa fa-shopping-cart"> <span class="text-uppercase ml-2">Add to cart</span></em> 
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            @endforeach
        @else
            <div class="row text-center">
                <div class="col-12">
                    <br><br>
                    <h3>No Favorites Product Found</h3>
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
</section>