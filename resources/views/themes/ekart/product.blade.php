<div class="section-content mt-5">
    <div class="container mt-5 padding-bottom"> 
        <div class="card pb-4 mt-5">   
            <!--Grid row-->
            <div class="row">
                <!--Grid column-->
                <div class="col-md-5 pics mt-5 text-center productdetails1">
                    @php $count=1; @endphp
                    <div class="wrap-gallery-article">
                        <div id="myCarouselArticle" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#myCarouselArticle" data-slide-to="0" {{ $count == 0 ? 'class="active"' : ''}}></li>
                                @if(isset($data['product']->other_images) && is_array($data['product']->other_images) && count($data['product']->other_images))
                                    @foreach($data['product']->other_images as $index => $img)
                                        <li data-target="#myCarouselArticle" data-slide-to="{{$count}}"></li>
                                    @php $count++; @endphp
                                    @endforeach
                                @endif
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img class="img-fluid" src="{{ $data['product']->image }}" alt="{{ $data['product']->name ?? 'Product Image' }}">
                                </div>
                                @if(isset($data['product']->other_images) && is_array($data['product']->other_images) && count($data['product']->other_images))
                                    @foreach($data['product']->other_images as $index => $img)
                                    <div class="carousel-item">
                                        <img class="img-fluid" src="{{ $img }}" alt="{{ $data['product']->name ?? 'Product Image' }}" title="">
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <a class="carousel-control-prev" href="#myCarouselArticle" role="button" data-slide="prev">
                                <em class="fa fa-angle-left text-dark font-weight-bold"></em>
                            </a>

                            <a class="carousel-control-next" href="#myCarouselArticle" role="button" data-slide="next">
                                <em class="fa fa-angle-right text-dark font-weight-bold"></em>
                            </a>
                        </div>
                        <br>

                        <div class="row hidden-xs " id="slider-thumbs">
                            <!-- Bottom switcher of slider -->
                            <ul class="reset-ul d-flex flex-wrap list-thumb-gallery">
                                <li class="col-sm-3 col-3" >
                                    <a class="thumbnail" data-target="#myCarouselArticle" data-slide-to="0">
                                        <img class="img-fluid" src="{{ $data['product']->image }}" alt="{{ $data['product']->name ?? 'Product Image' }}">
                                    </a>
                                </li>
                                @php $count=1; @endphp
                                @foreach($data['product']->other_images as $index => $img)
                                
                                <li class="col-sm-3 col-3">
                                <a class="thumbnail thumbnailimg" data-target="#myCarouselArticle" data-slide-to="{{$count}}">
                                    <img class="img-fluid" src="{{$img}}" alt="{{ $data['product']->name ?? 'Product Image' }}">
                                    </a>
                                </li>
                                @php $count++; @endphp
                                @endforeach            
                            </ul>                 
                        </div>
                    </div>
                </div>
                <!--Grid column-->
                <!--Grid column-->
                <div class="col-md-7 mt-5 text-left productdetails2">
                    <!--Content-->
                    <div class="text-left">
                        <p class="lead dark font-weight-bold">{{ $data['product']->name ?? '-' }}</p>
                        <p></p>
                        <hr class="line1 ml-0">
                        <p class="mt-2 read-more desc"><p><?= substr($data['product']->description, 0,200)?>...<a class="more-content" href="#desc" id="description">Read More</a></p>
                        <hr class="line1 ml-0">
                        <p class="text-muted" id="price_mrp_{{ $data['product']->id }}"><del>Price: <span class='value'></span></del></p>
                        <h5 class="font-weight-bold" id="price_offer_{{ $data['product']->id }}">Offer Price: <span class='value'>60</span></h5>
                        <h5 class="font-weight-bold" id="price_regular_{{ $data['product']->id }}">Price: <span class='value'>60</span></h5>
                        <small class="text-primary" id="price_savings_{{ $data['product']->id }}">You Save: Rs <span class='value'>10.0 (10%)</span></small>
                        <div class="form">
                            <form action="{{ route('cart-add') }}" class="addToCart" method="POST">
                                @csrf
                                <input type="hidden" name='id' value='{{ $data['product']->id }}'>
                                <input type="hidden" name="type" value='add'>
                                <input type="hidden" name="child_id" value='0' id="child_{{ $data['product']->id }}">
                                <div class="row mt-4">
                                    <div class="button-container col">
                                        <button class="cart-qty-minus button-minus" type="button" id="button-minus" value="-">-</button>
                                        <input class="form-control qtyPicker" id="qtyPicker_{{ $data['product']->id }}" type="number" name="qty" data-min="0" min="1" max="1" data-max="1" value="1">
                                        <button class="cart-qty-plus button-plus" type="button" id="button-plus" value="+">+</button>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="form-group col">
                                        <div class="btn-group-toggle variant" data-toggle="buttons">
                                            @php $firstSelected = true; @endphp
                                            @foreach($data['product']->variants as $v)
                                                <button class="btn" data-id="{{ $data['product']->id }}">
                                                    <span class="text-dark name">{{ get_varient_name($v) }}</span><br>
                                                    <small>1 option from {{ get_price_varients($v) }}</s></small>
                                                    <input type="radio" name="options" id="option{{ $v->id }}" value="{{ $v->id }}" data-id='{{ $v->id }}' data-price='{{ get_price_varients($v) }}' data-mrp='{{ get_mrp_varients($v) }}' data-savings='{{ get_savings_varients($v, false) }}' data-stock='{{ intval(getMaxQty($v)) }}' autocomplete="off" >
                                                </button>
                                                @if($firstSelected == true)
                                                    {{ $firstSelected = false }}
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                   @if(intval($data['product']->indicator) == 2)
                                        <img src="{{ asset('images/nonvag.svg') }}" alt="Not Vegetarian Product">
                                        <span class="text-left ml-1"> This is not a <strong>Vegetarian</strong> product.</span>    
                                    @elseif(intval($data['product']->indicator) == 1)
                                        <img src="{{ asset('images/vag.svg') }}" alt="Vegetarian Product">
                                        <span class="text-left ml-1"> This is a <strong>Vegetarian</strong> product.</span>
                                    @endif
                                </div>
                                <div class="form-group text-left add-to-cart1">
                                    <button type="submit" name="submit" class="btn"> 
                                        <em class="fa fa-shopping-cart"> <span class="text-uppercase ml-2">Add to cart</span></em> 
                                    </button>
                                    <button class="buy-now btn btn-primary text-center text-uppercase text-white" type="submit" name="submit" value="buynow"> <span class="buy-now1">Buy Now </span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Grid row tab content-->
        <div class="row">
            <div class="col-md-12 mt-5">
            <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active product-info" href="#desc" role="tab" data-toggle="tab">PRODUCT DETAILS</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content box rounded product-info-tab">
                    <div role="tabpanel" class="tab-pane active bg-white text-dark" id="desc">{!! $data['product']->description !!}</div>
                </div>

                <div class="m-2">
                    @if(isset($data['product']->manufacturer) && trim($data['product']->manufacturer) != "")
                        <p>Manufacturer : {{ $data['product']->manufacturer }}</p>
                    @endif
                    @if(isset($data['product']->made_in) && trim($data['product']->made_in) != "")
                        <p>MadeIn : {{ $data['product']->made_in }}</p>
                    @endif
                </div> 
                <div class="box mt-2 rounded">
                    @if(isset($data['product']->return_status))
                        <small class="text-primary">
                            @if(intval($data['product']->return_status))
                                Returnable.
                            @else
                                Not Returnable.
                            @endif
                        </small><br>
                    @endif
                    @if(isset($data['product']->cancelable_status))
                        <small class="text-primary">
                            @if(intval($data['product']->cancelable_status))                            
                                Order Can Cancel Till Order {{ strtoupper($data['product']->till_status ?? '') }}.
                            @else
                                Not Cancellable.
                            @endif
                        </small>
                    @endif
                </div>
            </div>     
        </div>
    </div>

</div>



