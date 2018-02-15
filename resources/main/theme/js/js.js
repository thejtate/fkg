(function ($) {

  if (typeof Drupal != 'undefined') {
    Drupal.behaviors.fkg = {
      attach: function (context, settings) {
        init();
      },

      completedCallback: function () {
        // Do nothing. But it's here in case other modules/themes want to override it.
      }
    }
  }

  $(function () {
    if (typeof Drupal == 'undefined') {
      init();
    }

    $(window).load(function () {

    });
  });

  function init() {
    initPopup();
    initRandomItemsTeam();
    initMobileMenu();
    initRandomItemsServices();
    initRandomItemsClient();
    initElmsAnimation();
    initColumn();
  }

  function initColumn() {
    $('.section-list .desc').columnize({
      columns: 3,
      accuracy: true
    });
  }

  function initMobileMenu() {
    var $btn = $('.mobile-menu'),
      $menu = $('.nav'), $navHeight = $('header .nav ul').outerHeight();

    $(window).resize(function () {
      $navHeight = $('header .nav ul').height();
    });


    $btn.on('click touch', function (e) {

      e.preventDefault();

      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $menu.removeClass('active');
        $('.outer-wrapper').css('margin-top', 0);

      } else {
        $(this).addClass('active');
        $menu.addClass('active');
        $('.outer-wrapper').css('margin-top', $navHeight);
      }
    })
  }

  function initPopup() {
    var $items = $('.section-teams .item .popup-btn'),
      $popup = $('.popup-items .popup-item article'),
      $close = $('.popup-item .close'),
      $section = $('.section-team'),
      url = window.location.hash;

    if(url.length>0){
      $('.popup-items').find(url).addClass('active');
      $section.addClass('active');
      $('.popup-items').show();
    }

    $items.on('click', function (e) {
      e.preventDefault();

      var $this = $(this),
        $id = $this.attr('href');

      $('.popup-items').find($id).addClass('active');
      $section.addClass('active');
      $('.popup-items').show();
    });

    $close.on('click', function (e) {
      e.preventDefault();
      $popup.removeClass('active');
      $section.removeClass('active');
      $('.popup-items').hide();
    });
  }

  function initElmsAnimation() {
    var $elms = $('.el-with-animation');
    var animationEnd = [];

    $(window).on('resize scroll', checkScroll);

    checkScroll();

    function checkScroll() {
      if (animationEnd.length === $elms.length) return;

      for (var i = 0; i < $elms.length; i++) {
        var $currentEl = $elms.eq(i);

        if (!$currentEl.hasClass('animating-end') && $(window).height() + $(window).scrollTop() > $currentEl.offset().top + $currentEl.height() / 2 + 50) {
          animate($currentEl);
        }
      }
    }

    function animate(el) {
      el.addClass('animating-end');
      animationEnd.push(1);
    }
  }

  function initRandomItemsClient() {
    var $items = $('.section-clients .desc .item'),
      randoms = [];

    function getRandomNum() {
      var rnd = Math.floor(Math.random() * $items.length);

      if (randoms[rnd] != true) {
        randoms[rnd] = true;
        return rnd;
      } else {
        return getRandomNum();
      }
    }

    for (var k = 0; k < $items.length; k++) {
      $('.section-clients .desc').append($items.eq(getRandomNum()));
    }

  }


  function initRandomItemsServices() {
    var $items = $('.section-services .desc .item, .section-clients .desc .item'),
      randoms = [];

    function getRandomNum() {
      var rnd = Math.floor(Math.random() * $items.length);

      if (randoms[rnd] != true) {
        randoms[rnd] = true;
        return rnd;
      } else {
        return getRandomNum();
      }
    }

    for (var k = 0; k < $items.length; k++) {
      $('.section-services .site-container .desc .view-content, .section-clients .desc').append($items.eq(getRandomNum()));
    }

  }

  function initRandomItemsTeam() {
    var $items = $('.section-teams .desc .item'),
      randoms = [];

    function getRandomNum() {
      var rnd = Math.floor(Math.random() * $items.length);

      if (randoms[rnd] != true) {
        randoms[rnd] = true;
        return rnd;
      } else {
        return getRandomNum();
      }
    }

    for (var k = 0; k < $items.length; k++) {
      $('.section-teams .site-container .desc .view-content').append($items.eq(getRandomNum()));
    }

  }

})(jQuery);