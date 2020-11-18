"use strict";

$(document).ready(function () {
  $('.has-submenu').click(function () {
    $(this).children('.submenu .dropdown').toggleClass('dropdown');
  });
  /* Anything that gets to the document
  will hide the dropdown */

  $(document).on('click', function (event) {
    if ($(event.target).closest('.menu-category').length == false) {
      $(".submenu .dropdown").addClass('dropdown');
    }
  });
  /* Clicks within the dropdown won't make
  it past the dropdown itself */

  $(".has-submenu .dropdown ").click(function (e) {
    e.stopPropagation();
  });
});