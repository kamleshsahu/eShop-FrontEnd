
@include("themes.$theme.common.msg")
<section class="section-content padding-bottom mt-5">
    <nav aria-label="breadcrumb"> 
        <ol class="breadcrumb">
            <li class=" item-1"></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Account</li>
        </ol>   
    </nav>
    <div class="container">
        <div class="row">
            @include("themes.$theme.user.sidebar")
            <main class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg">
                                <form action='#' method='POST'>
                                    @csrf
                                    <div class="form-row">
                                        <div class="col form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="" value="{{ $data['profile']['name']}}" required>
                                        </div>
                                        <div class="col form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ $data['profile']['email'] }}" class="form-control">
                                            <small class="form-text text-muted">We'll never share your email with anyone else.</small>
                                        </div>                                       
                                    </div>
                                    <div class="form-row">
                                        <div class="col form-group">
                                            <label>Mobile</label>
                                            <input type="text" value="{{ $data['profile']['mobile'] }}" class="form-control" disabled="disabled">
                                        </div>
                                        <div class="col form-group">
                                            <label>City</label>
                                            <select id='city' name='city_id' class="form-control" required>
                                                <option value='{{ $data['profile']['city_id'] }}' selected='selected'>{{ $data['profile']['city_name'] }}</option>
                                            </select>
                                        </div>                                        
                                    </div>
                                    <div class="form-row">                                        
                                        <div class="col form-group">
                                            <label>Area</label>
                                            <select id="area" name='area_id' class="form-control" required>
                                                <option value="{{ $data['profile']['area_id'] }}" selected='selected'>{{ $data['profile']['area_name'] }}</option>
                                            </select>
                                        </div>
                                        <div class="col form-group">
                                            <label>Pincode</label>
                                            <input type="number" class="form-control" name="pincode" value="{{ $data['profile']['pincode'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col form-group">
                                            <label>Address</label>
                                            <textarea class="form-control" name="street">{{ $data['profile']['street'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col form-group">
                                            <label>Data Of Birth</label>
                                            <input class="form-control" type="date" name="dob" value="{{ $data['profile']['dob'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block mt-4"> Update </button>
                                    </div>         
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>   
        </div>   
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<script>
$(document).ready(function(){
    $('select[name=city_id]').select2();
    $('select[name=area_id]').select2();
    loadOptions($("select[name=city_id]"), "{{route('cities')}}");
    loadOptions($("select[name=area_id]"), "{{ route('area', $data['profile']['city_id'] ?? 0 ) }}");
    $('select[name=city_id]').change(function(){
        loadOptions($("select[name=area_id]"), "{{ route('area','') }}/" + $('select[name=city_id]').val(), true);
    });
});
</script>