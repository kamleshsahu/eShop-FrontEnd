@if(Cache::has('offers') && is_array(Cache::get('offers')) && count(Cache::get('offers')))
    @foreach(Cache::get('offers') as $o)
        @if(isset($o->image) && trim($o->image) !== "")
            <section class="section-content">
                <div class="container">
                    <article class="padding-bottom">
                        <img src="{{ $o->image }}" class="w-100" alt="offer">
                    </article>
                </div>
            </section>
        @endif
    @endforeach
@endif

<!---section advertise ---->
<section class="section-content padding-bottom mt-3">
    <div class="container">
        <div class="card pb-3">
            <div class="row mt-3">
                <div class="col-md-6 col-12">
                    <img src="{{ theme('images/3.png') }}" class="w-100" alt="Google Play Store">
                </div>
                <div class="col-md-6 col-12">
                    <div class="buttonicon">
                        <h3 class="mb-2">Download</h3>
                        <h3 class="mb-3">eCart App Now</h3>
                        <p class="text-muted">Fast, Simple and Delightful.</p>
                        <p class="text-muted">All it takes 30 seconds to download.</p>
                        <div class="google-apple">
                            <a href="https://play.google.com"><img src="{{ theme('images/google1.png') }}" alt="Google Play Store"></a>
                        </div> 
                    </div>
                </div>
            </div>
         </div>
    </div>
</section>
<!----section end advertise -->