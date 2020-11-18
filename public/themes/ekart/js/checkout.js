   
    /*confirm steps start*/
    // function myFunction() {
    //    var x = document.getElementById("payment");
    //     if (x.style.display === "none") {
    //         $("#delivery").hide();
    //         $("#summary").hide();
    //         $("#payment").hide();
    //         $("#balance").show();
    //         $("#address").show();
    //     } else {
    //         $("#payment").hide();
    //         $("#address").hide();
    //     } 
    // }

    // function myFunction1() {
    //    var x = document.getElementById("address");
    //     if (x.style.display === "none") {
    //         $("#delivery").hide();
    //         $("#summary").hide();
    //         $("#balance").hide();
    //         $("#address").show();
    //     } else {
    //         $("#payment").show();
    //         $("#address").hide();
    //     } 
    // }

    function address() {
        var address1 = document.getElementById("address");
        if (address1.style.display === "none") {
            $("#address").hide();
       
        } else {
           
            $("#address").hide();
            $("#addAddress").show();
        }       
    }

    // function addAddress() {
    //     var address2 = document.getElementById("addAddress");
    //     if (address2.style.display === "none") {
    //         $("#addAddress").hide();
            
    //     } else {
           
    //         $("#address").show();
    //         $("#addAddress").hide();
    //     }       
    // }

    // function updateAddress() {
    //     var update = document.getElementById("address");
    //     if(update.style.display === "none") {
           
    //         $("#address").hide();
    //     } else {

    //         $("#address").hide();
    //         $("#editAddress").show();
    //     }
    // }

    // function addressEdit() {
    //     var editaddress = document.getElementById("editAddress");
    //     if(editaddress.style.display === "none") {
            
    //         $("#editAddress").hide();
    //     } else {

    //         $("#address").show();
    //         $("#editAddress").hide();
   
    //     }
    // }
    /*confirm steps end*/

    /* datepicker start*/
    $(function(){  
        $('#datepicker').datepicker({  
            dateFormat:'dd-mm-yy',
            minDate: '0d',
            maxDate: "+28d",
            todayHighlight: true,
            inline: true,  
            altField: "#date",
        });  
       
    });

        //Delivery day & time selection

    // $("input[name=deliverDay]").change(function(e){
    //     var today = new Date();
    //     var dd = String(today.getDate()).padStart(2, '0');
    //     var mm = String(today.getMonth() + 1).padStart(2, '0'); 
    //     var yyyy = today.getFullYear();

    //     todayDate = dd + '/' + mm + '/' + yyyy;
      
    //     var currentTime = new Date();
    //     var currentHours = currentTime.getHours();
    //     var daySelected = $("[name=deliverDay]").val();

    //     // if(daySelected == 0){
           
    //     //     //$("input[type=radio][name=deliverTime]").attr('disabled', 'disabled').prop('checked', false);

    //     // }
    //     if(daySelected == 0){
            
    //         if (currentHours < 9){
    //             document.getElementById("morning").disabled = true;
    //             document.getElementById("afternoon").disabled = true;
    //             document.getElementById("mid-afternoon").disabled = true;
    //             document.getElementById("evening").disabled = true;

    //         }
    //         else if (currentHours >= 9 && currentHours <=12){

    //             document.getElementById("morning").disabled = false;
    //             document.getElementById("afternoon").disabled = false;
    //             document.getElementById("mid-afternoon").disabled = false;
    //             document.getElementById("evening").disabled = false;  
    //         }
    //         if (currentHours >= 12){

    //             document.getElementById("morning").disabled = true;
    //             document.getElementById("mid-afternoon").disabled = false;
    //             document.getElementById("afternoon").disabled = false;
    //             document.getElementById("evening").disabled = false;
    //         }
    //         if (currentHours >= 15){

    //             document.getElementById("morning").disabled = true;
    //             document.getElementById("mid-afternoon").disabled = true;
    //             document.getElementById("afternoon").disabled = false;
    //             document.getElementById("evening").disabled = false;
    //         }
    //         if (currentHours >= 18){
                
    //             document.getElementById("morning").disabled = true;
    //             document.getElementById("afternoon").disabled = true;
    //             document.getElementById("mid-afternoon").disabled = true;
    //             document.getElementById("evening").disabled = false;
    //         }
    //         if (currentHours >= 21){

    //             document.getElementById("morning").disabled = true;
    //             document.getElementById("afternoon").disabled = true;
    //             document.getElementById("mid-afternoon").disabled = true;
    //             document.getElementById("evening").disabled = true;
    //         }
    //     }else {
    //         $("input[type=radio][name=deliverTime]").removeAttr('disabled', 'disabled');

    //         $("input[type=radio][name=deliverTime]:first").prop('checked', true);
    //     }
    // }).change();
    // /*delivery day end*/

    /*modal*/
    $(document).ready(function() {
        $('a[data-confirm]').click(function(ev) {
            var href = $(this).attr('href');
            $('#orderConfirm').find('.modal-title').text($(this).attr('data-confirm'));
            $('#modal-btn-yes').attr('href', href);
            $('#orderConfirm').modal({show:true});
            return false;
        });  
    });
    /*modal end*/