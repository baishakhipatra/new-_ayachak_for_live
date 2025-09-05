$(document).ready(function () {


// Deal slider start
  var swiper = new Swiper(".deal_slider", {
    slidesPerView: 4,
    // autoplay: true,
    spaceBetween: 20,
    breakpoints: {
      320: {
        slidesPerView: 2,
      },
    //   576: {
    //     slidesPerView: 2,
    //   },
      768: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 4,
      },
    },
    loop: true,
    // Navigation arrows
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

// Deal slider end


  // Deal_product slider start
  var swiper = new Swiper(".deal_product_slider", {
    // autoplay: true,
    spaceBetween: 20,

    breakpoints: {
      320: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 4,
      },
    },
    
    // pagination: {
    //   el: ".swiper-pagination",
    //   clickable: true,
    // },
    // // Navigation arrows
    // navigation: {
    //   nextEl: ".swiper-button-next",
    //   prevEl: ".swiper-button-prev",
    // },
  });




// wishlist colour fill

// $(".blue_heart").click(function(){
//   $(".blue_heart_fill").toggleClass("add_fill");
// });



// poroduct counter


$(function() {
  $(".product_counter_btn").on("click", function() {
    var $button = $(this);
    var $parent = $button.parent(); 
    var oldValue = $parent.find('.input_text').val();
 
    if ($button.text() == "+") {
       var newVal = parseFloat(oldValue) + 1;
     } else {
        // Don't allow decrementing below zero
       if (oldValue > 1) {
         var newVal = parseFloat(oldValue) - 1;
         } else {
         newVal = 1;
       }
       }
     $parent.find('a.add-to-cart').attr('data-quantity', newVal);
     $parent.find('.input_text').val(newVal);
  });
 });




// product_details slider 











var swiper = new Swiper(".wallet_swiper", {
  slidesPerView: 4,
  freeMode: true,
  watchSlidesProgress: true,

  breakpoints: {
    280: {
      slidesPerView: 2,
    },
    320: {
      slidesPerView: 3,
    },
    576: {
      slidesPerView: 4,
    },
    
  },
});
var swiper2 = new Swiper(".wallet_swiper2", {
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  thumbs: {
    swiper: swiper,
  },
});




// select2


$(".coupon_code").select2();



});
