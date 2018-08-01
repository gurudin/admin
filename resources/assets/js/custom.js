
(function ($) {
  var loadingDefaultValue = '';

  $.fn.loading = function (text = '') {
    text == '' ? text = 'loading...' : text;
    if (text == 'reset') {
      this.removeClass('disabled').attr('disabled', false).html(loadingDefaultValue);
    } else {
      loadingDefaultValue = this[0].innerHTML;
      this.addClass('disabled').attr('disabled', true).html(text);
    }
  };

  $("#navbar-toggler").click(function () {
    $("#gurudin-menu-bar").toggleClass('d-none');
    $("#nav-top").toggleClass('d-none');

    if ($("#gurudin-menu-bar").attr('class').indexOf('d-none') > -1) {
      $("#gurudin-main").addClass('col-md-12').removeClass('col-md-10');
    } else {
      $("#gurudin-main").addClass('col-md-10').removeClass('col-md-12');
    }
  });
})(jQuery);