@if(Cache::has('categories') && is_array(Cache::get('categories')) && count(Cache::get('categories')))
    <!--section categories popular categories transparent image-->
    <section class="section-content padding-bottom mt-4">
        <div class="container">
            <h4 class="title-section text-uppercase font-weight-bold">Popular Categories</h4>
            <hr class="line">
            <div class="popular">
                <div class="row p-0">
                    @foreach(Cache::get('categories') as $i => $c)
                        <div class="col-md-4">
                            <div class="item category-item-card rounded">  
                                <img class="category-item" src="{{ $c->image }}" alt="{{ $c->name ?? 'Category' }}">  
                                <span class="overlay-text">
                                    <p class="text-dark title font-weight-bold name mb-0">{{ $c->name }}</p>
                                    <small class="text-muted subtitle">{{ $c->subtitle }}</small>
                                    <p class="m-0">
                                        <a href="{{ route('category', $i) }}" class="shop-now">Shop Now <em class="fa fa-chevron-right"></em></a>
                                    </p>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--end section categories-->
@endif