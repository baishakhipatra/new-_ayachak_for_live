$(document).ready(function () {
  // user dropdown

  // $(".header_user_menu_a").click(function () {
  //   $(".header_user_dropdown").toggleClass("add_menu");
  // });

  // Deal slider start
  var swiper = new Swiper(".deal_slider", {
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
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 2,
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

  $(function () {
    $(".product_counter_btn").on("click", function () {
      var $button = $(this);
      var $parent = $button.parent();
      var oldValue = $parent.find(".input_text").val();

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
      $parent.find("a.add-to-cart").attr("data-quantity", newVal);
      $parent.find(".input_text").val(newVal);
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

  

  // profile_menu
  const listItems = document.querySelectorAll(".account-list li span");

  listItems.forEach((item) => {
    item.addEventListener("click", function () {
      const parentLi = this.parentElement;
      const accountItem = parentLi.querySelector(".account-item");

      if (parentLi.classList.contains("active")) {
        parentLi.classList.remove("active");
        accountItem.classList.remove("active");
      } else {
        // Remove active class from all
        document.querySelectorAll(".account-list li.active").forEach((li) => {
          li.classList.remove("active");
          li.querySelector(".account-item").classList.remove("active");
        });

        // Add active class to the clicked one
        parentLi.classList.add("active");
        accountItem.classList.add("active");
      }
    });
  });
});
