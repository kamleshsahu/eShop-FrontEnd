{{-- recently added and new on ekart --}}

@if(isset($s->products) && is_array($s->products) && count($s->products))
    <section class="section-content padding-bottom mt-3 sellpro">
        <div class="container">
            @if(isset($s->title) && $s->title != "")
                <h4 class="title-section text-uppercase font-weight-bold">{{ $s->title }} <small class="text-muted short-desc"> {{ $s->short_description }}</h4>  
                @if(isset($s->slug) && $s->slug != "")
                    <a href="{{ route('shop', ['section' => $s->slug]) }}" class="view float-right text-uppercase title-section viewall">View All</a>
                @endif
                <hr class="line">
            @endif
            <div class="row pr-3 ekart">
              
                @php   $maxProductShow = get('style_2.max_product_on_homne_page'); @endphp
                @foreach($s->products as $p)
                    @if((--$maxProductShow) > -1)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                            <figure class="card card-sm card-product-grid">
                                <aside class="add-to-fav">                                    
                                    <button type="button" class="btn fa fa-{{ (isset($p->is_favorite) && intval($p->is_favorite)) ? 'heart' : 'heart-o' }}" data-id='{{ $p->id }}' />
                                </aside>
                                <a href="{{ route('product-single', $p->slug) }}" class="img-wrap"> <img src="{{ $p->image }}" alt="{{ $p->name ?? 'Product Image' }}"> </a>
                                <figcaption class="info-wrap">
                                    <div class="text-wrap p-3 text-left">
                                        <a href="{{ route('product-single', $p->slug) }}" class="title font-weight-bold product-name">{{ $p->name }}</a>
                                        
                                        <span class="text-muted style-desc"><?= substr($p->description, 0,50)?></span>
                                        <div class="price mt-1 ">
                                            <strong id="price_{{ $p->id }}">{!! print_price($p) !!}</strong> &nbsp; <s class="text-muted" id="mrp_{{ $p->id }}">{!! print_mrp($p) !!}</s>
                                            <small class="text-success" id="savings_{{ $p->id }}"> {{ get_savings_varients($p->variants[0]) }} </small>
                                        </div>   
                                    </div>
                                </figcaption>
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
                            </figure>
                        </div> 
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif


