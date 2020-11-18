@if(isset($data['breadcrumb']))
    <section class="padding-bottom mt-5">
        <nav aria-label="breadcrumb"> 
            <ol class="breadcrumb">
                <li class=" item-1"></li>
                @foreach($data['breadcrumb'] as $b)
                    <li class="breadcrumb-item"><a href="{{ $b['link'] }}">{!! strtoupper($b['title']) !!}</a></li>
                @endforeach
            </ol>
        </nav> 
    </section>
@endif