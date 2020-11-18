<section class="section-content padding-bottom mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg col-md-3 filter mb-3">
                <div class="card">
                    <div class="pt-4">
                        <legend class="mb-1 p-0">Filter By</legend>
                        <hr class="line mb-0 pb-0">
                    </div>
                    <form action='#' method="GET" id='filter'>
                        <input type="hidden" name="s" value="{{ isset($_GET['s']) ? trim($_GET['s']) : ''}}">
                        <input type="hidden" name="section" value="{{ isset($_GET['section']) ? trim($_GET['section']) : ''}}">
                        <input type="hidden" name="category" value="{{ isset($_GET['category']) ? trim($_GET['category']) : ''}}">
                        <input type="hidden" name="sub-category" value="{{ isset($_GET['sub-category']) ? trim($_GET['sub-category']) : ''}}">
                        <input type="hidden" name="sort" value="{{ isset($_GET['sort']) ? trim($_GET['sort']) : ''}}">
                        <div>
                            <br>
                            <h5 class="mb-3 name">Price</h5>
                            <div class="row">
                                <div class="col">
                                    <div id="slider-range"></div>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="number" name="min_price" value="{{ intval($data['selectedMinPrice']) }}" class="form-control">
                                </div>
                                <div class="col">
                                    <input type="number" name="max_price" value="{{ intval($data['selectedMaxPrice'])+1 }}" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <br>
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    @if(isset($data['categories']) && is_array($data['categories']) && count($data['categories']))
                    <div>
                        <h5 class="mb-3 name">Categories</h5>
                        <div class="text ml-4 ">
                            @foreach($data['categories'] as $c)
                                @if(isset($c->name) && trim($c->name) != "")
                                    <div class="custom-control custom-checkbox pb-2">
                                        <input type="checkbox" class="custom-control-input cats" id="cat-{{ $c->id }}" value="{{ $c->slug }}" {{ (isset($data['selectedCategory']) && is_array($data['selectedCategory']) && in_array($c->slug, $data['selectedCategory'])) ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="cat-{{ $c->id }}">{{ $c->name }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div> 
                    </div>
                    @endif
                    @if(isset($data['selectedCategory']) && is_array($data['selectedCategory']))
                        @foreach($data['selectedCategory'] as $cat)
                            @if(isset(Cache::get('categories',[])[$cat]) && isset(Cache::get('categories',[])[$cat]->childs) /*&& is_array(Cache::get('categories',[])[$_GET['category']]->childs) && count(Cache::get('categories',[])[$_GET['category']]->childs)*/)
                                <br>
                                <div>
                                    <h5 class="mb-3 name">{{ Cache::get('categories',[])[$cat]->name }}</h5>
                                    <div class="text ml-4">
                                        @foreach(Cache::get('categories',[])[$cat]->childs as $c)
                                            <div class="custom-control custom-checkbox pb-2">
                                                <input type="checkbox" class="custom-control-input subs" id="sub-{{ $c->id }}" value="{{ $c->slug }}" {{ (isset($data['selectedSubCategory']) && is_array($data['selectedSubCategory']) && in_array($c->slug, $data['selectedSubCategory'])) ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="sub-{{ $c->id }}">{{ $c->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <br>
                </div>
            </div>
            <div class="col-md-9 col-xl-9 col-lg-7 col-12 pl-0 shopdetails">
                <nav class="navbar navbar-md navbar-light bg-white row gridviewdiv">
                    <div class="col-md-6 col-xs-3 col-6">
                        <div class="row">
                            <div id="list">
                                <em class= "fa fa-list fa-lg" data-view ="list-view"></em>
                            </div>
                            <div id="grid">
                                <em class="selected fa fa-th fa-lg" data-view ="grid-view"></em>
                            </div>
                            <div class="letter">
                                <small class="">{{ (isset($data['total']) && intval($data['total'])) ? $data['total'].' Items' : '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="gridviewselect">
                            <div class="row">
                                <div class="select"> Sort By:  </div>
                                <div class="select1">
                                    <select class="form-control innerselect1" id="sort">
                                        <option value="">Relevent</option> 
                                        <option value="new" {{ (isset($_GET['sort']) && $_GET['sort'] == 'new') ? 'selected' : '' }}>New</option> 
                                        <option value="old" {{ (isset($_GET['sort']) && $_GET['sort'] == 'old') ? 'selected' : '' }}>Old</option> 
                                        <option value="low" {{ (isset($_GET['sort']) && $_GET['sort'] == 'low') ? 'selected' : '' }}>Low to High</option> 
                                        <option value="high" {{ (isset($_GET['sort']) && $_GET['sort'] == 'high') ? 'selected' : '' }}>High to Low</option> 
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                @if(isset($data['list']) && isset($data['list']['data']) && is_array($data['list']['data']) && count($data['list']['data']))
                    <div id="products" class="row view-group">   
                        @foreach($data['list']['data'] as $p)
                            <div class="item1 col-xs-4 col-md-3">
                                <div class="add-to-fav">
                                    <button type="button" class="btn fa fa-{{ (isset($p->is_favorite) && intval($p->is_favorite)) ? 'heart' : 'heart-o' }}" data-id='{{ $p->id }}' />
                                </div>
                                <a href="{{ route('product-single', $p->slug ?? '-') }}">
                                    <div class="thumbnail card-sm">                                        
                                        <a href="{{ route('product-single', $p->slug ?? '-') }}">
                                            <div class="img-event">
                                                <img class="group list-group-image img-fluid" src="{{ $p->image }}" alt="" width="200px">
                                            </div>
                                        </a>
                                        <div class="caption card-body">
                                            <div class="text-wrap text-left">
                                                <a href="{{ route('product-single', $p->slug ?? '-') }}" class="title font-weight-bold product-name">{{ $p->name }}</a><br>
             
                                                <span class="text-muted desc"><?= substr($p->description, 0,20)?></span>
                                                <div class="price mt-1 ">
                                                    <strong id="price_{{ $p->id }}">{!! print_price($p) !!}</strong> &nbsp; <s class="text-muted" id="mrp_{{ $p->id }}">{!! print_mrp($p) !!}</s>
                                                    <small class="text-success" id="savings_{{ $p->id }}"> {{ get_savings_varients($p->variants[0]) }} </small>
                                                </div> 
                                            </div>
                                            <span class="inner">
                                                <form action='{{ route('cart-add-single-varient') }}' method="POST">
                                                    <input type="hidden" name="id" value="{{ $p->id }}">
                                                    <select name="varient" data-id="{{ $p->id }}">
                                                        @foreach($p->variants as $v)
                                                            @if(intval($v->stock))
                                                                <option value="{{ $v->id }}"  data-price='{{ get_price(get_price_varients($v)) }}' data-mrp='{{ get_price(get_mrp_varients($v)) }}' data-savings='{{ get_savings_varients($v) }}'>{{ get_varient_name($v) }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn cart-1 fa fa-shopping-cart" onclick="" title="Add to Cart"><span>&nbsp;&nbsp;Add to cart</span></button>
                                                </form>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>  
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col"><br></div>
                        </div>
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
                    @else
                        <div class="row">
                            <div class="col">
                                <br><br>
                                <h1 class="text-center">No Product Found</h1>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
$(document).ready(function() {
    $('#list').click(function(event){event.preventDefault();$('#products .item1').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item1').removeClass('list-group-item');$('#products .item1').addClass('grid-group-item');});
    $("#sort").change(function(e){
        $("input[type=hidden][name=sort]").val($(this).val());
        $("#filter").submit();
    });
    $(".subs").change(function(){
        var sub_ids = [];
        $('.subs:checked').each(function(){
            sub_ids.push($(this).val());
        });
        if(sub_ids.length > 0){
            $("#filter input[type=hidden][name=sub-category]").val(sub_ids.join(","));
            $("#filter").submit();
        }
    });
    $(".cats").change(function(){
        var cat_ids = [];
        $('.cats:checked').each(function(){
            cat_ids.push($(this).val());
        });
        if(cat_ids.length > 0){
            $("#filter input[type=hidden][name=category]").val(cat_ids.join(","));
            $("#filter").submit();
        }
    });
    $( "#slider-range" ).slider({
        range: true,
        min: {{ intval($data['min_price']) }},
        max: {{ intval($data['max_price'])+1 }},
        values: [ {{ intval($data['selectedMinPrice']) }}, {{ intval($data['selectedMaxPrice'])+1 }} ],
        slide: function( event, ui ) {
            $( "input[type=number][name=min_price]" ).val(ui.values[ 0 ]);
		    $( "input[type=number][name=max_price]" ).val(ui.values[ 1 ]);
        }
    });
    $("#filter").submit(function(){
        $(this).find("button[type=submit]").click();
    });
});
</script>