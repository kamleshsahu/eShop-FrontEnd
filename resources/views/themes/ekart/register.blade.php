<section class="footerfix section-content mt-5">
    <div class="container">
        <div class="card mx-auto register-card">
            <article class="card-body">
                <header class="mb-4"><h4 class="card-title">Sign up</h4></header>
                <form action='#' method='POST' id='registerForm'>
                    @csrf
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="auth_uid" value="{{ $data['auth_uid'] }}">
                    <input type="hidden" name="mobile" value="{{ $data['mobile'] }}">
                    <input type="hidden" name="country" value="{{ $data['country'] }}">
                    <div class="form-group">
                        <div class="alert alert-danger error-hide" id="registerError"></div>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Full Name</label>
                            <input type="text" name='display_name' class="form-control" value='{{ $data['display_name'] }}' required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name='email' placeholder="" value='{{ $data['email'] }}' required>
                        <small class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Mobile</label>
                            <input type="text" class="form-control" name='mobile' placeholder="" value='{{ $data['mobile'] }}' readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <select id='city' name='city' class="form-control" required>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Area</label>
                            <select id='area' name='area' class="form-control" required>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Pincode</label>
                            <input type="number" class="form-control" name='pincode' placeholder="" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label>Address</label>
                            <textarea class="form-control" name='address' placeholder="Address" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Create password</label>
                            <input class="form-control" name='password' type="password" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Repeat password</label>
                            <input class="form-control" name='password_confirmation' type="password" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Referral Code</label>
                            <input class="form-control" name='friends_code' type="text" value='{{ $data['friends_code'] }}'>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="custom-control custom-checkbox"> <input type="checkbox" class="custom-control-input" required> <div class="custom-control-label"> I am agree with <a href="{{ route('page', 'tnc') }}" target="_blank">terms and contitions</a>  </div> </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Register  </button>
                    </div>
                    <p class="text-center mt-4">Have an account? <a href="{{ route('login') }}">Log In</a></p>
                </form>
            </article>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<script>
$(document).ready(function(){
    $("#registerForm").submit(function(e){
        var form = $(this);
        form.find('button[type=submit]').attr('disabled', 'disabled');
        e.preventDefault();
        $.ajax({
            url : "#",
            type : 'POST',
            data : $(this).serialize(),    // multiple data sent using ajax
            success: function (response) {
                form.find('button[type=submit]').removeAttr('disabled');
                try{
                    var obj = JSON.parse(response);
                    if(obj.error === true){
                        $("#registerError").html(obj.message).show();
                    }else{
                        window.location.href = "{{ route('my-account') }}"
                    }
                }catch(e){
                    form.find('button[type=submit]').removeAttr('disabled');
                }
            }
        });
    });
    $('select[name=city]').select2({placeholder:'Select City'});
    $('select[name=area]').select2({placeholder:'Select Area'});
    loadOptions($("select[name=city]"), "{{route('cities')}}", false, false, true, true);
    $('select[name=city]').change(function(){
        loadOptions($("select[name=area]"), "{{ route('area','') }}/" + $('select[name=city]').val(), true);
    });
});
</script>