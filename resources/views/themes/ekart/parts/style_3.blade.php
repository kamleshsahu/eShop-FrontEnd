
{{-- ekart special --}}
@if(isset($s->products) && is_array($s->products) && count($s->products))
    <!---section polular categories-->
    <section class="section-content padding-bottom ekartspec">
        <div class="container">
            <h4 class="title-section text-uppercase font-weight-bold">{{ $s->title }} <small class="text-muted short-desc">{{ $s->short_description }}</small></h4>
            @if(isset($s->slug) && $s->slug != "")
                <a href="{{ route('shop', ['section' => $s->slug]) }}" class="view float-right text-uppercase title-section viewall">View All</a>
            @endif
            <hr class="line">
            <div class="row">
                @php $maxProductShow = get('style_3.max_product_on_homne_page'); @endphp
                @foreach($s->products as $p)
                    @if((--$maxProductShow-1) > -1)
                        <div class="col-md-4">
                            <div class="card-popular-category">
                                <a href="{{ route('product-single', $p->slug) }}">
                                <div class="col-4 m-0 p-0">  
                                    <img class="rounded" src="{{ $p->image }}" alt="{{ $p->name ?? 'Product Name'}}">       
                                </div>
                                </a>
                                <div class="col-8">
                                    <div class="text-wrap p-2 text-left">
                                        <a href="{{ route('product-single', $p->slug) }}" class="title font-weight-bold product-name">{{ $p->name }}</a>
                                        <span class="text-muted desc"><?= substr($p->description, 0,80)?></span>
                                        <div class="price mt-1 ">
                                            <strong>{!! print_price($p) !!}</strong>&nbsp; <s class="text-muted">{!! print_mrp($p) !!}</s>
                                            <small class="text-success ml-3"> {{ get_savings_varients($p->variants[0]) }} </small>
                                        </div> 
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    <!---end section categories-->
@endif