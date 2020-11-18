$( document ).ready(function() {
    $('.footerfix').css('min-height',$(window).height()-525);
    
    //home pade side menu category
    $("#navContainer").on("click", "li", function(){
        $(this).children("ul").toggleClass("active");
        $("#navContainer li").not(this).children("ul").removeClass("active");
    });
    //sub-header
    $('.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });
     
});

//carousel
$(document).ready(function(e){
    $('#myCarouselArticle').carousel({
        interval: 10000
    });
});