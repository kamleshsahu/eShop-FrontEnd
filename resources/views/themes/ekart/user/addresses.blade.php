<script src="{{ theme('js/checkout.js') }}"></script>
<div class="container mt-5">
    <div class="row">
        @include("themes.".get('theme').".user.sidebar")
        <div class="col-md-9">
            @if(isset($data['address']) && is_array($data['address']) && count($data['address']))
            <div class="row">
                <div class="col-md-12" id="address">
                    <div class="card shadow mb-4">
                        <p class="product-name pb-0 font-weight-bold head" id="myDec">Delivery Address</p>
                        <hr class="mb-0">
                        <table class="table table-borderless table-shopping-cart" aria-describedby="myDec" aria-hidden="true">                            
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @foreach($data['address'] as $a)
                                                @if(isset($a->id) && intval($a->id))
                                                    <div class="row delivery-address">
                                                        <span class="form-group edit-delete">
                                                            <button class="btn editAddress" data-data='{{ json_encode($a) }}'> <em class="fa fa-pencil"></em></button>
                                                            <a href="{{ route('address-remove', $a->id) }}" class="btn"> <em class="fa fa-remove text-danger"></em></a>
                                                        </span>
                                                        <span class="form-group ml-3">
                                                            <label>
                                                                <strong>{{ $a->name ?? '' }}</strong><br>
                                                                <label class="badge badge-primary">{{ $a->type }}</label> {{ $a->address ?? '' }}, {{ $a->area_name ?? '' }}<br>
                                                                {{ $a->city_name ?? ''}} - {{ $a->pincode ?? '' }}<br>
                                                                Mobile: {{ ($a->country_code ?? '') ." ". ($a->mobile ?? '-') }}
                                                            </label>
                                                        </span>
                                                    </div>
                                                    <hr class="p-0">
                                                @else
                                                    {{ var_dump($a) }}
                                                @endif
                                            @endforeach
                                            <div class="form-group mb-0 text-right">
                                                <button onclick="address()" class="btn btn-primary text-uppercase">Add New Address</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-12" id="editAddress">
                    <div class="card">
                        <p class="product-name pb-0 font-weight-bold head">Edit Address</p>
                        <hr class="mb-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg">
                                    <form action='{{ route('address-add') }}' id='formEditAddress' method="POST">                                   
                                        <input type="hidden" name="id">
                                        <input type="hidden" name="latitude" value="0">
                                        <input type="hidden" name="longitude" value="0">
                                        <input type="hidden" name="country_code" value="0">
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Name</label>
                                                <input class="form-control" name="name" type="text" value="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Mobile No</label>
                                                <input class="form-control" id='editPhone' type="number" value="" name="mobile">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Alternate mobile No</label>
                                                <input class="form-control" type="number" name="alternate_mobile">      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Address</label>
                                                <input class="form-control" type="text" name="address">      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Landmark</label>
                                                <input class="form-control" type="text" name="landmark">      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>PinCode</label>
                                                <input class="form-control" type="number" name="pincode">      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Select City</label>
                                                <br>
                                                <select name='city' class="form-control" required style="width: 100%">
                                                </select>
                                            </div>
                                            <div class="form-group col">
                                                <label>Select Area</label>
                                                <br>
                                                <select name='area' class="form-control" required style="width: 100%">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>State</label>
                                                <input class="form-control" type="text" name="state" required>      
                                            </div>
                                            <div class="form-group col">
                                                <label>Country</label>
                                                <input class="form-control" type="text" name="country" required>      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="radio-inline">
                                              <input class="mr-2" type="radio" name="type" checked value="Home">Home
                                            </label>
                                            <label class="radio-inline  ml-5">
                                              <input class="mr-2" type="radio" name="type" value="Work">Work
                                            </label>
                                            <label class="radio-inline  ml-5">
                                              <input class="mr-2" type="radio" name="type" value="Other">Other
                                            </label>
                                        </div>
                                        <div class="form-row mb-4 mt-3">
                                            <input type="checkbox" name="is_default" class=" mt-1" />
                                            <label class="control-label" for="default-address"> Set as default address</label>   
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block text-uppercase"> Update </button>
                                            <button class="btn btn-primary btn-block text-uppercase AddEditAddressCancel"> Cancel </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            @else

            @endif
            <div class="row padding-bottom">
                <div class="col-md-12" id="addAddress">
                    <div class="card">
                        <p class="product-name pb-0 font-weight-bold head">Add New Address</p>
                        <hr class="mb-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg">
                                    <form action='{{ route('address-add') }}' id='formAddAddress' method='POST'>                                   
                                        <input type="hidden" name="latitude" value="0">
                                        <input type="hidden" name="longitude" value="0">
                                        <input type="hidden" name="country_code" value="0">
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Name</label>
                                                <input class="form-control" name="name" type="text" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Mobile No</label>
                                                <input class="form-control" id='addPhone' type="number" name="mobile" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Alternate /mobile No</label>
                                                <input class="form-control" type="number" name="alternate_mobile">      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Address</label>
                                                <input class="form-control" type="text" name="address" required>      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Landmark</label>
                                                <input class="form-control" type="text" name="landmark" required>      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>PinCode</label>
                                                <input class="form-control" type="number" name="pincode" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>Select City</label>
                                                <br>
                                                <select name='city' class="form-control" required style="width: 100%">
                                                </select>
                                            </div>
                                            <div class="form-group col">
                                                <label>Select Area</label>
                                                <br>
                                                <select name='area' class="form-control" required style="width: 100%">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label>State</label>
                                                <input class="form-control" type="text" name="state" required>      
                                            </div>
                                            <div class="form-group col">
                                                <label>Country</label>
                                                <input class="form-control" type="text" name="country" required>      
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="radio-inline">
                                                <input class="mr-2" type="radio" name="type" value="Home" checked>Home
                                            </label>
                                            <label class="radio-inline  ml-5">
                                                <input class="mr-2" type="radio" name="type" value="Work">Work
                                            </label>
                                            <label class="radio-inline  ml-5">
                                                <input class="mr-2" type="radio" name="type" value="Other">Other
                                            </label>
                                        </div>
                                        <div class="form-row mb-4 mt-3">
                                            <input type="checkbox" name="is_default" class=" mt-1" />
                                            <label class="control-label" for="default-address"> Set as default address</label>   
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block text-uppercase"> Add New Address </button>
                                            <button class="btn btn-primary btn-block text-uppercase AddEditAddressCancel"> Cancel </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<script>
var addPhone = document.querySelector("#addPhone");
window.intlTelInput(addPhone);
var itiAdd = window.intlTelInputGlobals.getInstance(addPhone);

$(document).ready(function(){
    $("#formAddAddress").find('select[name=city]').select2({placeholder:'Select City'});
    $("#formAddAddress").find('select[name=area]').select2({placeholder:'Select Area'});
    loadOptions($("#formAddAddress").find("select[name=city]"), "{{route('cities')}}", false, false, true);
    $("#formAddAddress").find('select[name=city]').change(function(){
        loadOptions($("#formAddAddress").find("select[name=area]"), "{{ route('area','') }}/" + $("#formAddAddress").find('select[name=city]').val(), true);
    });
    $("#formAddAddress").submit(function(e){
        var c = $(".iti__selected-flag").attr('title').split(" ");
        c = c[c.length -1];
        $(this).find("input[name=country_code]").val(c);
        return true;
    });
    $('.AddEditAddressCancel').click(function(e){
        e.preventDefault();
        $("#editAddress").addClass("address-hide");
        $("#editAddress").removeClass("address-show");
        
        $("#addAddress").addClass("address-hide");
        $("#addAddress").removeClass("address-show");
        
        $("#address").addClass("address-show");
        $("#address").removeClass("address-hide");
    });
    addPhone.addEventListener("countrychange", function() {
        var c = itiAdd.getSelectedCountryData();
        $("#formAddAddress").find("input[name=country_code]").val("+" + c.dialCode);
    });
    $('.editAddress').click(function(e){
        e.preventDefault();
        $("#editAddress").removeClass("address-hide");
        $("#editAddress").addClass("address-show");
        
        $("#address").removeClass("address-show");
        $("#address").addClass("address-hide");
        var address = $(this).data('data');
        console.log(address);
        
        var editPhone = document.querySelector("#editPhone");
        window.intlTelInput(editPhone);
        var itiEdit = window.intlTelInputGlobals.getInstance(editPhone);

        var countryData = itiEdit.getSelectedCountryData();
        itiEdit.setNumber(address.country_code);

        editPhone.addEventListener("countrychange", function() {
            var c = itiEdit.getSelectedCountryData();
            $("#formEditAddress").find("input[name=country_code]").val("+" + c.dialCode);
        });

        $("#formEditAddress").find('input[name=id]').val(address.id);
        $("#formEditAddress").find('input[name=name]').val(address.name);
        $("#formEditAddress").find('input[name=mobile]').val(address.mobile);
        $("#formEditAddress").find('input[name=alternate_mobile]').val(address.alternate_mobile);
        $("#formEditAddress").find('input[name=address]').val(address.address);
        $("#formEditAddress").find('input[name=landmark]').val(address.landmark);
        $("#formEditAddress").find('input[name=pincode]').val(address.pincode);
        $("#formEditAddress").find('input[name=state]').val(address.state);
        $("#formEditAddress").find('input[name=country]').val(address.country);
        $("#formEditAddress").find('input[name=type][value="' + address.type + '"]').attr('checked', 'checked');
        $("#formEditAddress").find('select[name=city]').select2({placeholder:'Select City'});
        $("#formEditAddress").find('select[name=area]').select2({placeholder:'Select Area'});
        if(address.is_default == 1){
            $("#formEditAddress").find('input[name=is_default]').prop('checked', 'checked');
        }else{
            $("#formEditAddress").find('input[name=is_default]').prop('checked', '');
        }
        loadOptions($("#formEditAddress").find("select[name=city]"), "{{route('cities')}}", false, false, true, address.city_id);
        $("#formEditAddress").find('select[name=city]').change(function(){
            loadOptions($("#formEditAddress").find("select[name=area]"), "{{ route('area','') }}/" + $("#formEditAddress").find('select[name=city]').val(), true, false, true, address.area_id);
        });
    });
});
</script>