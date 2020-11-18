
<section class="section-content padding-bottom mt-5">
    <div class="container">
        <div class="row mx-auto">
            <aside class="col-lg col-md-3 col-12 card">
                <nav class="nav-home-aside" >
                    <h6 class="title-category text-uppercase name mt-2"><strong>Categories</strong></h6>
                    <ul  class="menu-category" id="navContainer">
                        @php
                            $categories = Cache::get('categories', []);
                            $maxProductToShow = 7;
                            $totalCategories = count($categories);
                        @endphp
                        @foreach($categories as $c)
                            @if(isset($c->childs) && count((array)$c->childs))
                                <li class="has-submenu"><a>{{ $c->name }}</a>
                                    <ul class="submenu" >
                                        @foreach($c->childs as $child)
                                            <li><a href="{{ route('shop', ['category' => $c->slug, 'sub-category' => $child->slug]) }}">{{ $child->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li><a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a></li>
                            @endif
                            @if(intval(--$maxProductToShow))                               
                            @else
                                @if($maxProductToShow == 0)
                                    <li class="more-category1">
                                        <input class="check_id" type="checkbox" id="check_id">
                                        <label for="check_id"></label>
                                    <ul>
                                @endif
                            @endif
                        @endforeach
                        @if($maxProductToShow < 0)
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>
            </aside> <!-- col.// -->
            @if(Cache::has('sliders') && is_array(Cache::get('sliders')) && count(Cache::get('sliders')))
                <div class="col-md-9 col-xl-9 col-lg-7 p-0">
                    <div class="slider12">
                        <!-- ================== COMPONENT SLIDER  BOOTSTRAP  ==================  -->
                        <div id="carouselDocumentationIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @for($i =0; $i < count(Cache::get('sliders')); $i++)
                                    <li data-target="#carouselDocumentationIndicators" data-slider-to="{{$i}}" {{ $i == 0 ? 'class="active"' : ''}}></i>
                                @endfor
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                @foreach(Cache::get('sliders') as $i => $s)
                                    <div class="carousel-item {{ $i == 0 ? 'active' : ''}}">
                                        <img src="{{ $s->image }}" alt="{{ $s->name }}" class="d-block img-fluid" > 
                                    </div>
                                @endforeach  
                            </div>
                            <a class="carousel-control-prev" href="#carouselDocumentationIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselDocumentationIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>

                </div><!-- col-->
                <!-- ==================  COMPONENT SLIDER BOOTSTRAP end.// ==================  .// -->                    
            @endif
        </div> <!-- row.// -->
    </div> <!-- card-body.// -->  
 
</section>

