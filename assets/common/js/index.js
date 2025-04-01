"use strict";

$(window).on('load', function () {
  $('.flexslider').flexslider({
    animation: "slide", animationLoop: true,
    nextText: "",
    prevText: "",
    // controlNav: true,
    // directionNav: false,
    slideshowSpeed: 4000,
  });
});

$('.owl-persuade').owlCarousel({
  loop: true,
  margin: 30,
  nav: false,
  dots: true,
  // navText: [
  //    '<i class="fas fa-chevron-left"></i>',
  //    '<i class="fas fa-chevron-right"></i>'
  // ],
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: 3
    },
    1000: {
      items: 5
    }
  }
});

$('.owl-activities').owlCarousel({
  stagePadding: 50,
  loop: true,
  margin: 30,
  nav: true,
  navText: [
    '<i class="fas fa-chevron-left"></i>',
    '<i class="fas fa-chevron-right"></i>'
  ],
  dots: false,
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: true,
  responsive: {
    0: {
      items: 2
    },
    600: {
      items: 1
    },
    1000: {
      items: 1
    }
  }
});
