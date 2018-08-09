
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

  jQuery.extend({
    "setCookie": function (key, value, minutes = 60) {
      var d = new Date();
      d.setTime(d.getTime() + (minutes * 60 * 1000));
      var expires = "expires=" + d.toUTCString();

      document.cookie = key + "=" + escape(value) + "; " + expires;
    },
    "getCookie": function (key) {
      var name = key + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return unescape(c.substring(name.length, c.length));
      }

      return "";
    },
    "clearCookie": function (key) {
      this.setCookie(key, "", -1);
    }
  });

  $(".dropdown-toggle-bar").click(function () {
    var _this = $(this);
    var _con = $(this).nextUntil('dropdown-menu-bar');
    _con.slideToggle(100);

    $(document).mouseup(function (e) {
      if ((!_con.is(e.target) && _con.has(e.target).length === 0)
        && (!_this.is(e.target) && _this.has(e.target).length === 0)
      ) {
        _con.hide();
      }
    });
  });

  // Menu
  var $SIDEBAR_MENU = $('#sidebar-menu'),
    $LEFT_COL = $('.left-col'),
    $RIGHT_COL = $('.right-col'),
    $GURUDIN_NAV = $('#gurudin-nav'),
    $NAVBAR_TOGGLE = $('#gurudin-navbar-toggle'),
    $MENUTREE = '<ul class="nav side-menu">',
    $LEFT_W = 230,
    $isMouseOver = true;

  window.onresize = function () {
    setSize();
  };

  var setSize = function () {
    // reset height
    $RIGHT_COL.css({
      'width': $(window).width() - $LEFT_W,
      'min-height': $(window).height()
    });
    $GURUDIN_NAV.css({ 'width': $(window).width() - $LEFT_W });

    // animate
    $RIGHT_COL.animate({ 'margin-left': $LEFT_W }, 200);
    $LEFT_COL.animate({ 'width': $LEFT_W }, 200, function () {
      if ($LEFT_W == 230) {
        $SIDEBAR_MENU.find('span').fadeIn();
        $("#site-name").fadeIn();
      }
    });
  };

  var menuTreeView = function (tree, $group_id) {
    tree.forEach(row => {
      $MENUTREE += '<li>';

      if (row.children.length > 0) {
        $MENUTREE += '<a href="javascript:;">';
        $MENUTREE += '<i class="' + row.icon + '"></i>';
        $MENUTREE += '<span>' + row.text + '</span> <span class="direction-icon fas fa-chevron-right"></span>';
        $MENUTREE += '</a>';

        $MENUTREE += '<ul class="nav child_menu">';
        menuTreeView(row.children, $group_id);
        $MENUTREE += '</ul>';
      } else {
        $MENUTREE += '<a href="' + row.href + '?group=' + $group_id + '">';
        $MENUTREE += '<i class="' + row.icon + '"></i>';
        $MENUTREE += '<span>' + row.text + '</span>';
        $MENUTREE += '</a>';

        $MENUTREE += '</li>';
      }
    });
  };

  var menuTreeClick = function () {
    $SIDEBAR_MENU.find('a').on('click', function (ev) {
      $isMouseOver = false;
      var $li = $(this).parent();

      if ($li.is('.active')) {
        $li.removeClass('active active-sm');
        $li.children('a').find('.direction-icon').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        $('ul:first', $li).slideUp(function () {

        });
      } else {
        // prevent closing menu if we are on child menu
        if (!$li.parent().is('.child_menu')) {
          $SIDEBAR_MENU.find('li').removeClass('active active-sm');
          $SIDEBAR_MENU.find('li ul').slideUp();
        }

        $li.addClass('active');
        $li.children('a').find('.direction-icon').removeClass('fa-chevron-right').addClass('fa-chevron-down');

        $('ul:first', $li).slideDown(function () {

        });
      }
    });
  };

  $LEFT_COL.hover(
    function () {
      if ($LEFT_W == 60) {
        $isMouseOver = true

        $LEFT_COL.animate({ 'width': 230 }, 200, function () {
          $SIDEBAR_MENU.find('span').fadeIn();
          $("#site-name").fadeIn();
        });
      }
    },
    function () {
      if ($LEFT_W == 60) {
        $SIDEBAR_MENU.find('span').hide();
        $("#site-name").hide();
        $LEFT_COL.animate({ 'width': 60 }, 200, function () {
          $SIDEBAR_MENU.find('span').hide();
          $("#site-name").hide();
        });
      }
    }
  );

  $NAVBAR_TOGGLE.click(function () {
    if ($LEFT_W == 230) {
      $LEFT_W = 60;

      $SIDEBAR_MENU.find('span').hide();
      $("#site-name").hide();
    } else {
      $LEFT_W = 230;
    }
    $.setCookie('left_w', $LEFT_W, 60);

    setSize();
  });

  jQuery.extend({
    "init": function (menuTree, group) {
      menuTreeView(menuTree, group);
      $("#menu-section").append($MENUTREE + '</ul>');
      menuTreeClick();

      $("#gurudin-nav").show();

      if ($.getCookie('left_w') == 60) {
        $LEFT_W = 60;

        $SIDEBAR_MENU.find('span').hide();
        $("#site-name").hide();
      }

      setSize();
    }
  });
})(jQuery);
