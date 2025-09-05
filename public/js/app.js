// Scroll Header
// jQuery(window).scroll(function () {
//   if (jQuery(document).scrollTop() > 0) {
//     jQuery("header").addClass("scroll");
//   } else {
//     jQuery("header").removeClass("scroll");
//   }
// });

$.fn.extend({
    equalizer: function() {
        var minHeight = 0;
        $(this).each(function() {
            if($(this).outerHeight() > minHeight) {
                minHeight = $(this).outerHeight();
            }
        });
        $(this).css('min-height', minHeight + 'px');
    }
  });
  
$('.listing-block .product__single figcaption h4').equalizer();

jQuery(window).scroll(function () {
    if (jQuery(document).scrollTop() > 0) {
        jQuery("header.scroll-header").addClass("visible");
    } else {
        jQuery("header.scroll-header").removeClass("visible");
    }
});

jQuery(window).scroll(function () {
    if (jQuery(document).scrollTop() > 90) {
        jQuery(".filter_cat_list").addClass("squeez");
    } else {
        jQuery(".filter_cat_list").removeClass("squeez");
    }
});


/* Scroll up */

jQuery(window).scroll(function () {
    if (jQuery(document).scrollTop() > 500) {
        jQuery('.scroll-up').addClass('scroll');
    } else {
        jQuery('.scroll-up').removeClass('scroll');
    }
});

jQuery(document).ready(function() {
  	var address = jQuery("input[name=existing_billing_address]:checked").val();
	var address_billing_city = $("input[name=existing_billing_address]:checked").attr('billing_city');
    var address_billing_state = $("input[name=existing_billing_address]:checked").attr('billing_state');
    var address_billing_country = $("input[name=existing_billing_address]:checked").attr('billing_country');
    var address_billing_address = $("input[name=existing_billing_address]:checked").attr('billing_address');
    var address_billing_pin = $("input[name=existing_billing_address]:checked").attr('billing_pin');
    var address_billing_landmark = $("input[name=existing_billing_address]:checked").attr('billing_landmark');
    $("input[name='billing_city']").val(address_billing_city);
    $("input[name='billing_state']").val(address_billing_state);
    $("input[name='billing_country']").val(address_billing_country);
    $("input[name='billing_address']").val(address_billing_address);
    $("input[name='billing_pin']").val(address_billing_pin);
    $("input[name='billing_landmark']").val(address_billing_landmark);
});



/* Smooth Scroll */
jQuery(function () {
    jQuery('a[href*="#"]:not([href="#"])').click(function () {
        if (
            location.pathname.replace(/^\//, "") ===
            this.pathname.replace(/^\//, "") &&
            location.hostname === this.hostname
        ) {
            var target = jQuery(this.hash);
            target = target.length ?
                target :
                jQuery("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                jQuery("html, body").animate({
                        scrollTop: target.offset().top,
                    },
                    1000
                );
                return false;
            }
        }
    });
});

/* Overlay (Open / Close) */

var tl = gsap.timeline({
    paused: true
});

//tl.add(gsap.to(".overlay", { y: "0", ease: Sine.easeInOut, duration: 0.5 }), 0);
tl.addLabel("menu__links", ">");
tl.add(
    gsap.to(".overlay .overlay__right", {
        y: "0",
        ease: Sine.easeInOut,
        duration: 0.5,
    }),
    "<+=0.1"
);
tl.addLabel("secondary-nav", ">");
tl.add(
    gsap.to(".overlay .overlay__right .bottom", {
        y: "0",
        ease: Sine.easeInOut,
        duration: 0.5,
    }),
    "<+=0.3"
);
tl.addLabel("slogan", ">");

tl.add(
    gsap.from(".overlay .menu__links > ul > li", {
        y: "30px",
        opacity: 0,
        ease: Sine.easeInOut,
        duration: 0.3,
        stagger: 0.1,
    }),
    "menu__links-=0.2"
);
tl.add(
    gsap.from(".overlay .social-menu > ul > li", {
        y: "30px",
        opacity: 0,
        ease: Sine.easeInOut,
        duration: 0.3,
        stagger: 0.1,
    }),
    "menu__links-=0.2"
);
tl.add(
    gsap.from(".overlay .menu__links .button", {
        y: "30px",
        opacity: 0,
        ease: Sine.easeInOut,
        duration: 0.3,
    }),
    ">-=0.3"
);

tl.add(
    gsap.to(".overlay .overlay__close", {
        opacity: 1,
        ease: Sine.easeInOut,
        duration: 0,
    }),
    "secondary-nav-=0"
);
tl.add(
    gsap.from(".overlay .top h2, .overlay .top h2 address", {
        y: "30px",
        opacity: 0,
        ease: Sine.easeInOut,
        duration: 0.3,
        stagger: 0.1,
    }),
    "secondary-nav-=0.2"
);

tl.add(
    gsap.from(".overlay .logo", {
        y: "30px",
        opacity: 0,
        ease: Sine.easeInOut,
        duration: 0.3,
    }),
    "slogan-=0.2"
);

// gsap.registerPlugin(ScrollTrigger);
// gsap.from(".fact", {
//   scrollTrigger: {
//     trigger: ".fact",
//     triggerActions: "restart none none none"
//   },
//   y: "300px",
//   opacity: 1,
//   ease: Sine.easeInOut,
//   duration: 1,
//   stagger: 0.1,
// });

$(".nav-toggle").click(function () {
    toggle_overlay();
});

$(".overlay .overlay__close").click(function () {
    $(".nav-toggle").removeClass("open");
    $(".overlay").removeClass("open");
    $(".overlay").css({
        "transform": "translateY(-150%)",
        "opacity": "0"
    });
    $("body").removeClass("menu-open");
});

function toggle_overlay() {
    $(".nav-toggle").toggleClass("open");
    $(".overlay").toggleClass("open");
    $(".overlay").css({
        "transform": "translateY(0%)",
        "opacity": "1"
    });
    $("body").toggleClass("menu-open");

    if ($(".overlay").hasClass("open")) {
        tl.restart();
        tl.play();
    } else {
        tl.reverse();
    }
}

$(".mega-menu a").hover(function () {
    var imageDataSource = $(this).attr('data-src');
    $(this).parents('.mega-menu').find('img').attr('src', imageDataSource);
    //$('#bgimage').attr('src', imageDataSource);
});

$("input[name$='paymentMethod']").click(function() {
    var test = $(this).val();

    $("div.method").hide();
    $("#method" + test).show();
});

/* Overlay (Sub menu) */

$(".overlay .main-nav ul li.menu-item-has-children > a").click(function (e) {
    e.preventDefault();

    var submenu = $(this).next(".sub-menu");

    $(".overlay .main-nav ul li .sub-menu").not(submenu).slideUp(500);
    $(this).next(".sub-menu").slideToggle(500);

    if ($(this).hasClass('open')) {
        $(".overlay .main-nav ul li a").removeClass('open');
        $(this).removeClass('open');
    } else {
        $(".overlay .main-nav ul li a").removeClass('open');
        $(this).addClass('open');
    }
});

/* Video Overlay */

$("[data-overlay-trigger]").click(function () {
    var id = $(this).data("overlay-trigger");
    $('[data-overlay="' + id + '"]')
        .css("display", "flex")
        .hide()
        .fadeIn(500);
});

$(".video-overlay .video-overlay__close").click(function () {
    $(this).parent().fadeOut(500);
});

/* -----------------------------
// Home Banner (Slider)
-------------------------------*/

var total = $(".home-banner__slider .home-banner__slider-single").length;
var video = document.getElementById('onn-video');
$(".home-banner .total").html(total);
$(".home-banner .current").html("1");
var menu = ['North Store', 'South Store', 'West Store', 'East Store']
var banner_slider = new Swiper(".home-banner__slider", {
    autoplay: {
        delay: 23000,
        disableOnInteraction: false,
    },
    effect: 'fade',
    fadeEffect: {
        crossFade: true
    },
    speed: 4000,
    loop: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            //return '<div class="' + className + '">' + (index + 1) + "<b><i></i></b></div>";
            return '\
        <div class="box ' + className + '">\
        <b><i></i></b>\
        <div class="bigNumber">' + (index < 10 ? '0' + (index + 1) : (index + 1)) + '</div>\
        <div class="text">' + (menu[index]) + '</div>\
        </div>';
        },
    },
    // pagination: {
    //   el: '.swiper-pagination',
    //   clickable: true,
    //   renderBullet: function(index, className) {
    //     return '\
    //       <div class="box ' + className + '">\
    //       <div class="bigNumber">' + (index < 10 ? '0' + (index + 1) : (index + 1)) + '</div>\
    //       <div class="text">' + (menu[index]) + '</div>\
    //       </div>';
    //   },
    // },
});
banner_slider.on("transitionEnd", function () {
    $(".home-banner .current").html(banner_slider.realIndex + 1);
});
banner_slider.on('slideChangeTransitionEnd', function () {
    if (this.realIndex == 1) { // second slide, because of zero indexed counting
        video.play();
    }
})

/* Home News (Slider) */

new Swiper(".home-product__slider", {
    slidesPerView: 5.5,
    grid: {
        rows: 2,
    },
    grabCursor: true,
    spaceBetween: 0,
    allowTouchMove: true,
    pagination: {
        el: ".bullets",
        bulletActiveClass: "active",
        bulletClass: "slide",
        modifierClass: "",
        clickable: true,
    },
    navigation: {
        nextEl: ".product-collection-right",
        prevEl: ".product-collection-left",
    },
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 2,
            spaceBetween: 10
        },
        // when window width is >= 480px
        576: {
            slidesPerView: 3,
            spaceBetween: 20
        },
        // when window width is >= 640px
        900: {
            slidesPerView: 3.5,
            spaceBetween: 30
        },
        // when window width is >= 640px
        1100: {
            slidesPerView: 4.5,
            spaceBetween: 30
        }
    }
});

var highlight_slider = new Swiper(".home-highlights__slider", {
    direction: "horizontal",
    loop: false,
    speed: 2000,
    grabCursor: true,
    slidesPerView: 1,
    centeredSlides: false,
    allowTouchMove: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
});
var highlight_text_slider = new Swiper(".home-highlights__text__slider", {
    direction: "horizontal",
    loop: false,
    speed: 2000,
    grabCursor: true,
    slidesPerView: 1,
    centeredSlides: false,
    allowTouchMove: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
});

highlight_text_slider.on('slideChangeTransitionStart', function () {
    highlight_slider.slideTo(highlight_text_slider.activeIndex);
});

highlight_slider.on('transitionStart', function () {
    highlight_text_slider.slideTo(highlight_slider.activeIndex);
});

var menu = ['Innerwear', 'Outerwear', 'Platina', 'Relaxz', 'Footkins', 'Thermal', 'Winter Wear']
var highlight_main_slider = new Swiper(".home-highlights__mainslider", {
    direction: "vertical",
    loop: true,
    speed: 2000,
    // autoplay: {
    //   delay: 1000,
    //   disableOnInteraction: false,
    // },
    grabCursor: true,
    slidesPerView: 1,
    centeredSlides: false,
    allowTouchMove: true,
    pagination: {
        el: ".highlight-pagination",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + (className) + ' ' + (className + index) + '"><b><i>' + (menu[index]) + '</i></b></span>';
        },
    },
});


var our_store = new Swiper(".our_store", {
    slidesPerView: 5,
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});


$(function () {
    $('.swiper-pagination-bullet-active').prev().addClass('active');
});

$('.swiper-pagination-bullet').click(function () {
    $(this).prevUntil('.swiper-pagination-bullet:first').addClass('active');
    $(this).removeClass('active');
    $(this).nextUntil('.swiper-pagination-bullet:last').removeClass('active');
    //$('.swiper-pagination-bullet').not($(this).next()).removeClass('active');
});

/* Contact Overlay */

$('.contact-overlay .contact-overlay__toggle').click(function () {
    $(this).toggleClass('opened');
    $('.contact-overlay .contact-overlay__content').toggle();
});

new Swiper(".home-viewed__slider", {
    direction: "horizontal",
    loop: false,
    speed: 2000,
    grabCursor: true,
    slidesPerView: 1,
    centeredSlides: false,
    allowTouchMove: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
});



let SwiperBottom = new Swiper('.home-marquee__slider', {
    spaceBetween: 50,
    centeredSlides: true,
    speed: 10000,
    autoplay: {
        delay: 0.1,
    },
    loop: true,
    loopedSlides: 5,
    slidesPerView: 'auto',
    allowTouchMove: false,
    disableOnInteraction: true
});

let SwiperTop = new Swiper('.home-marquee__revslider', {
    spaceBetween: 50,
    centeredSlides: true,
    speed: 10000,
    autoplay: {
        delay: 0.1,
    },
    loop: true,
    loopedSlides: 5,
    slidesPerView: 'auto',
    allowTouchMove: false,
    disableOnInteraction: true
});

let saleslider = new Swiper('.home-sale__slider', {
    spaceBetween: 60,
    centeredSlides: true,
    speed: 10000,
    autoplay: {
        delay: 0.1,
    },
    loop: true,
    loopedSlides: 5,
    slidesPerView: 'auto',
    allowTouchMove: false,
    disableOnInteraction: true,
    breakpoints: {
        // when window width is >= 320px
        320: {
            spaceBetween: 20
        },
        // when window width is >= 480px
        480: {
            spaceBetween: 30
        },
        // when window width is >= 640px
        640: {
            spaceBetween: 40
        },
        // when window width is >= 640px
        1024: {
            spaceBetween: 50
        }
    }
});


var collection__slider = new Swiper(".home-collection__slider", {
    direction: "horizontal",
    loop: true,
    speed: 2000,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    grabCursor: true,
    slidesPerView: 'auto',
    centeredSlides: false,
    allowTouchMove: true,
    slideToClickedSlide: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
    navigation: {
        nextEl: ".collection-right",
        prevEl: ".collection-left",
    },
    thumbs: {
        swiper: collection__thumb,
    },
});

var collection__thumb = new Swiper(".home-collection__thumb", {
    direction: "horizontal",
    loop: true,
    effect: 'fade',
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    speed: 2000,
    grabCursor: true,
    slidesPerView: 1,
    centeredSlides: false,
    allowTouchMove: true,
    slideToClickedSlide: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
    navigation: {
        nextEl: ".collection-right",
        prevEl: ".collection-left",
    },
});

var collection__thumbs = new Swiper(".home-collection__thumbs", {
    direction: "horizontal",
    loop: true,
    speed: 2000,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    effect: 'fade',
    grabCursor: true,
    slidesPerView: 1,
    slideToClickedSlide: true,
    centeredSlides: false,
    allowTouchMove: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
    navigation: {
        nextEl: ".collection-right",
        prevEl: ".collection-left",
    },
});

collection__thumb.on('slideChangeTransitionStart', function () {
    collection__slider.slideTo(collection__thumb.activeIndex);
    collection__thumbs.slideTo(collection__thumb.activeIndex);
});

collection__slider.on('transitionStart', function () {
    collection__thumb.slideTo(collection__slider.activeIndex);
    collection__thumbs.slideTo(collection__slider.activeIndex);
});

collection__thumbs.on('transitionStart', function () {
    collection__thumb.slideTo(collection__thumbs.activeIndex);
    collection__slider.slideTo(collection__thumbs.activeIndex);
});

new Swiper(".home-productgallery__slider", {
    direction: "horizontal",
    loop: false,
    speed: 2000,
    grabCursor: true,
    slidesPerView: 'auto',
    centeredSlides: false,
    allowTouchMove: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
});

var numItems = $('.product-details__gallery__thumb-single').length;

if(numItems > 1) {
    var gallery__thumb = new Swiper(".product-details__gallery__thumb", {
        direction: "vertical",
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        lazy: true,
        observer: true,
        watchSlidesProgress: true,
        slideToClickedSlide: true,
    });
    var gallery__slider = new Swiper(".product-details__gallery__slider", {
        loop: true,
        lazy: true,
        observer: true,
        spaceBetween: 0,
        centeredSlides: true,
        navigation: {
            nextEl: ".buttom_button",
            prevEl: ".top_button",
        },
        thumbs: {
            swiper: gallery__thumb,
        },
    });

} else {
    var gallery__thumb = new Swiper(".product-details__gallery__thumb", {
        direction: "vertical",
        loop: false,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        lazy: true,
        observer: true,
        runCallbacksOnInit: true,
        watchSlidesProgress: true,
        slideToClickedSlide: true,
    });
    var gallery__slider = new Swiper(".product-details__gallery__slider", {
        loop: false,
        lazy: true,
        observer: true,
        spaceBetween: 0,
        centeredSlides: true,
        runCallbacksOnInit: true,
        navigation: {
            nextEl: ".buttom_button",
            prevEl: ".top_button",
        },
        thumbs: {
            swiper: gallery__thumb,
        },
    });
}

// var gallery__slider = new Swiper(".product-details__gallery__slider", {
//     direction: "horizontal",
//     loop: true,
//     speed: 2000,
//     grabCursor: true,
//     slidesPerView: 1,
//     // centeredSlides: true,
//     allowTouchMove: true,
//     // initialSlide: 2,
//     pagination: {
//         el: ".bullet",
//         clickable: true,
//         type: "bullets",
//         renderBullet: function (index, className) {
//             return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
//         },
//     },
// });

// var gallery__thumb = new Swiper(".product-details__gallery__thumb", {
//     direction: "vertical",
//     loop: true,
//     speed: 2000,
//     freeMode: true,
//     // initialSlide: 2,
//     // centeredSlides: true,
//     slidesPerView: 4,
//     slideToClickedSlide: true,
//     spaceBetween: 6,
//     pagination: {
//         el: ".bullet",
//         clickable: true,
//         type: "bullets",
//         renderBullet: function (index, className) {
//             return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
//         },
//     },
//     navigation: {
//         nextEl: ".buttom_button",
//         prevEl: ".top_button",
//     },
// });

// gallery__thumb.on('slideChangeTransitionStart', function () {
//     gallery__slider.slideTo(gallery__thumb.activeIndex);
// });

// gallery__slider.on('transitionStart', function () {
//     gallery__thumb.slideTo(gallery__slider.activeIndex);
// });

var collection__thumb = new Swiper(".login-collection__thumb", {
    direction: "horizontal",
    loop: true,
    effect: 'fade',
    autoplay: {
        delay: 2000,
        disableOnInteraction: false,
    },
    speed: 1000,
    grabCursor: true,
    slidesPerView: 1,
    centeredSlides: false,
    allowTouchMove: true,
    slideToClickedSlide: true,
    pagination: {
        el: ".bullet",
        clickable: true,
        type: "bullets",
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "<b><i></i></b></span>";
        },
    },
});



new Swiper('.register-collection__thumb', {
    direction: 'horizontal',
    loop: true,
    speed: 2500,
    freeMode: true,
    freeModeMomentum: false,
    freeModeMomentumBounce: false,
    grabCursor: false,
    slidesPerView: 4,
    spaceBetween: 10,
    centeredSlides: false,
    allowTouchMove: false,
    autoplay: {
        delay: 0,
        disableOnInteraction: false,
    },
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 2,
        },
        // when window width is >= 480px
        480: {
            slidesPerView: 2,
        },
        // when window width is >= 640px
        640: {
            slidesPerView: 3,
        },
        // when window width is >= 640px
        1024: {
            slidesPerView: 4,
        }
    }
});

new Swiper('.filter_category', {
    direction: 'horizontal',
    loop: true,
    speed: 2500,
    grabCursor: true,
    slidesPerView: 5,
    spaceBetween: 0,
    autoplay: false,
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 3,
        },
        // when window width is >= 480px
        480: {
            slidesPerView: 3,
        },
        // when window width is >= 640px
        640: {
            slidesPerView: 3,
        },
        // when window width is >= 640px
        1024: {
            slidesPerView: 5,
        }
    },
    navigation: {
        nextEl: ".right_button",
        prevEl: ".left_button",
    },
});


/*------------------------------------------
    = Counter Odometter (virticle)
-------------------------------------------*/

function custom_count() {
    var flag = true;
    $('.leistungen-facts, .uber-facts-single').each(function () {
        if ($(this).isInViewport()) { // Here we check perticular section is in the viewport or number-counter-section
            if (flag) {
                var odo = $(".odometer");
                odo.each(function () {
                    var countNumber = $(this).attr("data-count");
                    $(this).html(countNumber);
                });
                flag = false;
            }
        } else {}
    });
}

// for check the section in view port or not;
$.fn.isInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
    console.log(elementBottom > viewportTop && elementTop < viewportBottom);
};

$(document).ready(function () {
    //  odometer section is on view-port or not
    custom_count();
    //resize-function
    $(window).resize(function () {
        custom_count();
    });

    $(window).on("scroll", function () {
        custom_count();
    });
});


// Load more content on click

$(document).ready(function () {
    size_li = $(".boxes .boxes-single").length;
    x = 6;
    $('.boxes .boxes-single:lt(' + x + ')').show();
    if (size_li < x) {
        $('.project-listing-button').hide();
    }
    $('#showmore').click(function (e) {
        e.preventDefault();
        x = (x + 3 <= size_li) ? x + 3 : size_li;
        $('.boxes .boxes-single:lt(' + x + ')').show();
        if (x == size_li) {
            $('.project-listing-button').hide();
        }
    });
});

// tab slider 

$('.slider-tab .slider-single:first-child').addClass('selected');
$('.selected').next().addClass('next');
$('.slider-content .slider-content-single:first-child').addClass('active');

// slider tab
// $('.slider-tab').slick({
//     infinite: true,
//     slidesToScroll: 3,
//     slidesToShow: 5,
//     variableWidth: true,
//     arrows: true,
//     dots: false,
//     nextArrow: '.slider-next',
//     prevArrow: '.slider-prev',
//     draggable: false,
//     swipe: false,
//     responsive: [{
//             breakpoint: 1400,
//             settings: {
//                 slidesToShow: 4,
//                 slidesToScroll: 3,
//                 variableWidth: true,
//             },
//         },
//         {
//             breakpoint: 767,
//             settings: {
//                 slidesToShow: 3,
//                 slidesToScroll: 3,
//                 variableWidth: true,
//             },
//         },
//         {
//             breakpoint: 575,
//             settings: {
//                 slidesToShow: 2,
//                 slidesToScroll: 2,
//                 variableWidth: true,
//             },
//         },
//     ],
// });

$(document).on('click', '.filter-option .slider-single', function () {
    $('.filter-option .slider-single').removeClass('selected');
    $(this).addClass('selected');

    var cat = $(this).attr('data-category');
    if (cat !== 'all') {
        $('.slider-content .filter-item').each(function () {
            $(this).removeClass('selected');
        });
        $('.slider-content .filter-item[data-match=' + cat + ']').addClass('selected');
    } else {
        $('.slider-content .filter-item').each(function () {
            $(this).addClass('selected');
        });
    }
});

// Custom drop down js
/*
Reference: http://jsfiddle.net/BB3JK/47/
*/

$('.filter-select').change(function () {
    console.log("object");

    var filter = $(this).val();
    if (!filter == '1') {
        console.log("test");
        $('download-single').show();


    } else {
        $('download-single').hide();

        $('download-single[data-filter="' + filter + '"]').show();

    }
});

// Custom drop down js
/*
Reference: http://jsfiddle.net/BB3JK/47/
*/

// $('select').each(function () {
//     var $this = $(this),
//         numberOfOptions = $(this).children('option').length;

//     $this.addClass('select-hidden');
//     $this.wrap('<div class="select"></div>');
//     $this.after('<div class="select-styled"></div>');

//     var $styledSelect = $this.next('div.select-styled');
//     $styledSelect.text($this.children('option').eq(0).text());

//     var $list = $('<ul />', {
//         'class': 'select-options'
//     }).insertAfter($styledSelect);

//     for (var i = 0; i < numberOfOptions; i++) {
//         $('<li />', {
//             text: $this.children('option').eq(i).text(),
//             rel: $this.children('option').eq(i).val()
//         }).appendTo($list);
//     }

//     var $listItems = $list.children('li');

//     $styledSelect.click(function (e) {
//         e.stopPropagation();
//         $('div.select-styled.active').not(this).each(function () {
//             $(this).removeClass('active').next('ul.select-options').hide();
//         });
//         $(this).toggleClass('active').next('ul.select-options').toggle();
//     });

//     $listItems.click(function (e) {
//         e.stopPropagation();
//         $styledSelect.text($(this).text()).removeClass('active');
//         $this.val($(this).attr('rel'));
//         $list.hide();
//         //console.log($this.val());
//     });

//     $(document).click(function () {
//         $styledSelect.removeClass('active');
//         $list.hide();
//     });

// });

$('.home-slide_single').mouseenter(function () {
    $(this).addClass('isActive');
    $('.home-slide_holder').addClass('isExpanded');
});

$('.home-slide_single').mouseleave(function () {
    $('.home-slide_single').removeClass('isActive');
    $('.home-slide_holder').removeClass('isExpanded');
});

$('.home-slide_item').click(function () {
    $(this).next().slideToggle();
    $('.home-slide_content').not($(this).next()).slideUp();
});

$('.home-investment_item').mouseenter(function () {
    $(this).parent().addClass('isActive');
    $('.home-investment_wrapper').addClass('isExpanded');
    $('.home-investment').addClass('isActive');
    var imageDataSource = $(this).attr('data-src');
    $('#bgimage').attr('src', imageDataSource);
    $('.home-investment_image').addClass('isActive');
});
$('.home-investment_item').mouseleave(function () {
    $('.home-investment_single').removeClass('isActive');
    $('.home-investment_wrapper').removeClass('isExpanded');
    $('.home-investment').removeClass('isActive');
    $('.home-investment_image').removeClass('isActive');
    $('#bgimage').attr('src', '');
});

$(".aktuelles-projekt__item").slice(0, 9).show();
$("#loadMore").on('click', function (e) {
    e.preventDefault();
    $(".aktuelles-projekt__item:hidden").slice(0, 3).slideDown();
    if ($(".aktuelles-projekt__item:hidden").length == 0) {
        $(".aktuelles-projekt__button").fadeOut('slow');
    }
    $('html,body').animate({
        scrollTop: $(this).offset().top - 800
    }, 1500);
});

/* Lightbox */

// lightbox.option({
//   'albumLabel': "Bild %1 von %2"
// });


$(".home-gallary__single").slice(0, 10).show();
$('.home-gallary__filter [data-filter]').click(function (e) {
    $('.home-gallary__filter [data-filter].active').removeClass('active');
    if ($(this).hasClass('active')) {
        $(".home-gallary__filter [data-filter]").removeClass('active');
    } else {
        $(this).addClass('active');
    }
    e.preventDefault();
    var value = $(this).attr('id');

    $('[data-events]').each(function () {
        if (value == '*' || $(this).data('cat') == value) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});


// $('.product__sizes li').click(function(e) {
$(document).on('click', '.product__sizes li', function () {
    $('.product__sizes li.active').removeClass('active');
    if ($(this).hasClass('active')) {
        $(".product__sizes li").removeClass('active');
    } else {
        $(this).addClass('active');
    }
});

$('.product__color li').click(function (e) {
    $('.product__color li.active').removeClass('active');
    if ($(this).hasClass('active')) {
        $(".product__color li").removeClass('active');
    } else {
        $(this).addClass('active');
    }
});

$('.product__packs li').click(function (e) {
    $('.product__packs li.active').removeClass('active');
    if ($(this).hasClass('active')) {
        $(".product__packs li").removeClass('active');
    } else {
        $(this).addClass('active');
    }
});

$(document).on('click', '.product__sizes li', function () {
    var cat = $(this).attr('data-price');
    $('.price_val').text(cat);
});

// $(document).on('click', '.filter__toggle', function(){
//   $('.product__filter__bar').slideToggle();
//   $(this).toggleClass('active');
// });

$('.product__filter__bar-list input[type="checkbox"]').click(function () {
    var inputValue = $(this).attr("pro-filter");
    if (this.checked) {
        $('.filter__data').append('<span class="filter__button" filter-data="' + inputValue + '">' + inputValue + '<i></i></span>');
    } else {
        $('.filter__button').remove();
    }
});

$(document).on('click', '.filter__button', function () {
    var filterValue = $(this).attr('filter-data');
    $(this).remove();
});

var value = 1

$(".product__enquire .counter").val(value);
$('.product__enquire .increment').on("click", function () {
    value = parseInt(value + 1);
    $(".product__enquire .counter").val(value);
});
$('.product__enquire .decrement').on("click", function () {
    if (value > 1) {
        value = parseInt(value - 1);
        $(".product__enquire .counter").val(value);
    }
});



var controller = new ScrollMagic.Controller();

$producttext = $('.home-product__text');
var producttextmove = new TimelineMax();
producttextmove
    .from($producttext, 1, {
        yPercent: -100,
        ease: Power4.linear
    })

// iPhone back to stylesheet position
new ScrollMagic.Scene({
        duration: '90%',
        triggerElement: ".home-product",
        triggerHook: 0.9,
    })
    .setTween(producttextmove)
    .addTo(controller);

$producttitle = $('.home-product__holder__title');
var producttitlemove = new TimelineMax();
producttitlemove
    .from($producttitle, 1, {
        yPercent: -400,
        opacity: 0,
        ease: Power4.linear
    })

// iPhone back to stylesheet position
new ScrollMagic.Scene({
        duration: '90%',
        triggerElement: ".home-product",
        triggerHook: 0.4,
    })
    .setTween(producttitlemove)
    .addTo(controller);

$collectionbefore = $('.home-collection__before');
var collectionbeforemove = new TimelineMax();
collectionbeforemove
    .to($collectionbefore, 1, {
        width: "28%",
        ease: Power4.linear
    })

// iPhone back to stylesheet position
new ScrollMagic.Scene({
        duration: '90%',
        triggerElement: ".home-collection",
        triggerHook: 0.9,
    })
    .setTween(collectionbeforemove)
    .addTo(controller);

$collectiontext = $('.home-collection__text');
var collectiontextmove = new TimelineMax();
collectiontextmove
    .from($collectiontext, 1, {
        yPercent: 200,
        ease: Power4.linear
    })

// iPhone back to stylesheet position
new ScrollMagic.Scene({
        duration: '90%',
        triggerElement: ".home-collection",
        triggerHook: 0.9,
    })
    .setTween(collectiontextmove)
    .addTo(controller);


$categorytext = $('.home-category__text');
var categorytextmove = new TimelineMax();
categorytextmove
    .from($categorytext, 1, {
        yPercent: -150,
        ease: Power4.linear
    })

// iPhone back to stylesheet position
new ScrollMagic.Scene({
        duration: '90%',
        triggerElement: ".home-category",
        triggerHook: 0.4,
    })
    .setTween(categorytextmove)
    .addTo(controller);


$('#filter, .filter__toggle').click(function (e) {
    e.preventDefault();
    $('.product__holder').toggleClass('active');
    $('.product__filter').toggleClass('active');
});
$('.filter__close').click(function (e) {
    e.preventDefault();
    $('.product__holder').toggleClass('active');
    $('.product__filter').toggleClass('active');
});

$('#shippingaddress').click(function () {
    $('.shipping-address').toggleClass('d-none');
});


$('input[name="existing_billing_address"]').click(function () {
    var billing_city = $(this).attr('billing_city');
    var billing_state = $(this).attr('billing_state');
    var billing_country = $(this).attr('billing_country');
    var billing_address = $(this).attr('billing_address');
    var billing_pin = $(this).attr('billing_pin');
    var billing_landmark = $(this).attr('billing_landmark');
    $("input[name='billing_city']").val(billing_city);
    $("input[name='billing_state']").val(billing_state);
    $("input[name='billing_country']").val(billing_country);
    $("input[name='billing_address']").val(billing_address);
    $("input[name='billing_pin']").val(billing_pin);
    $("input[name='billing_landmark']").val(billing_landmark);
});

$('#search_toggle').click(function () {
    $('.search_wrap').toggleClass('active');

    setTimeout(() => {
        $('input[name="query"]').focus();
    }, 500);
});
$('.search_close').click(function () {
    $('.search_wrap').removeClass('active');
});
$('.wishlist_btn').click(function () {
    $(this).toggleClass('active');
});

jQuery('.faq_content').hide();
jQuery('.faq_content:first').show();
jQuery('.faq_heading:first').addClass('active');
jQuery('.faq_heading').click(function () {
    if (!jQuery(this).hasClass('active')) {
        jQuery('.faq_heading.active').removeClass('active');
        jQuery(this).addClass('active');
    } else {
        jQuery(this).removeClass('active');
    }
    jQuery(this).next().slideToggle();
    jQuery('.faq_content').not(jQuery(this).next()).slideUp();
});

jQuery('.specification').hide();
jQuery('.specification:first').show();
jQuery('.spec_head:first').addClass('active');
jQuery('.spec_head').click(function () {
    if (!jQuery(this).hasClass('active')) {
        jQuery('.spec_head.active').removeClass('active');
        jQuery(this).addClass('active');
    } else {
        jQuery(this).removeClass('active');
    }
    jQuery(this).next().slideToggle();
    jQuery('.specification').not(jQuery(this).next()).slideUp();
});


jQuery('.account-list li span').click(function() {
    if (!jQuery(this).hasClass('active')) {
        jQuery('span.active').removeClass('active');
        jQuery(this).addClass('active');
    } else {
        jQuery(this).removeClass('active');
    }
    jQuery(this).next().slideToggle();
    jQuery('.account-item').not(jQuery(this).next()).slideUp();
});

var url = window.location;
jQuery('.account-item a[href="'+url+'"]').parents('li').addClass('active');
jQuery('.account-item a').filter(function(){
    return this.href==url;
}).parents('li').addClass('active');


jQuery('.overlay_menu li a').click(function() {
    if (!jQuery(this).hasClass('active')) {
        jQuery('.overlay_menu li a.active').removeClass('active');
        jQuery(this).addClass('active');
    } else {
        jQuery(this).removeClass('active');
    }
    jQuery(this).next().slideToggle();
    jQuery('.overlay_submenu').not(jQuery(this).next()).slideUp();
});

jQuery('.career_list li a').click(function(){
    if (!jQuery(this).hasClass('active')) {
        jQuery('.career_list li a.active').removeClass('active');
        jQuery(this).addClass('active');
    } else {
        jQuery(this).removeClass('active');
    }
    jQuery(this).parents('li').next().slideToggle();
    jQuery('.cms_context').not(jQuery(this).parents('li').next()).slideUp();
});


jQuery('[data-fancybox="gallery"]').fancybox({
    // Options will go here
});


jQuery('.management_more').click(function() {
  jQuery(this).prev().slideToggle();
  if (jQuery(this).text() == "Read more") {
    console.log('test');
    jQuery(this).text("Read less");
  } else {
    console.log('rest');
    jQuery(this).text("Read more");
  }
  jQuery('.management_profile content').not(jQuery(this).prev()).slideUp();
});

// checkout fields validation
$("input[name=billing_pin]").on('keyup', (e) => {
	if($('input[name=billing_pin]').val().length == 0 || $('input[name=billing_pin]').val() == null || $('input[name=billing_pin]').val().length != 6 || Number.isSafeInteger(parseInt($('input[name=billing_pin]').val())) == false) {
		e.preventDefault();
		return false;
   	}
});