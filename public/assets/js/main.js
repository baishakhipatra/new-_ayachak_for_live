(function($){

    // offcanvas menu jquery
    $('.ham').on('click', function(){
        $('.offcanvas-menu').toggleClass('slide');
        $('.overlay').toggleClass('active');
    });

    $('.cross, .overlay').on('click', function(){
        $('.offcanvas-menu.slide').removeClass('slide');
        $('.overlay').removeClass('active');
    });


    // filter menu jquery
    $('.filter-toggle').on('click', function(){
        $('.filter-area').toggleClass('slide');
        $('.overlay').toggleClass('active');
    });

    $('.cross, .overlay').on('click', function(){
        $('.filter-area.slide').removeClass('slide');
        $('.overlay').removeClass('active');
    });


    // open modal start video
    document.addEventListener('DOMContentLoaded', function() {
    var videoModal = document.getElementById('videoModal');
    var video = document.getElementById('modalVideo');
    
    // When modal opens
    videoModal.addEventListener('shown.bs.modal', function () {
        video.play();
    });
    
    // When modal closes
    videoModal.addEventListener('hidden.bs.modal', function () {
        video.pause();
        video.currentTime = 0; // Optional: reset to start
    });
    });


    //match height
    equalheight = function(container) {
        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0,
            currentDiv; // declare currentDiv variable
        
        $(container).each(function() {
            $el = $(this);
            $el.height('auto');
            topPosition = $el.position().top; // fixed variable name
            
            if (currentRowStart != topPosition) {
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
                rowDivs.length = 0; // empty the array
                currentRowStart = topPosition;
                currentTallest = $el.height();
                rowDivs.push($el);
            } else {
                rowDivs.push($el);
                currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
            }
            
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }

    $(window).on('load', function() {
        equalheight('.grid-list li figcaption p');
    });

    $(window).on('resize', function() {
        equalheight('.grid-list li figcaption p');
    });


    // product details slider
    var swiper = new Swiper(".slider-thumb", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".slider-big", {
        loop: true,
        spaceBetween: 10,
        navigation: {
        nextEl: ".swiper-thumb-button-next",
        prevEl: ".swiper-thumb-button-prev",
        },
        thumbs: {
        swiper: swiper,
        },
    });


    // related post slider
    var swiper = new Swiper(".relared-pro-stack", {
      slidesPerView: 3,
      spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        360: {
          slidesPerView: 1,
          spaceBetween:9,
        },
        640: {
          slidesPerView: 2,
          spaceBetween:15,
        },
        768: {
          slidesPerView:2,
          spaceBetween:15,
        },
        1024: {
          slidesPerView:3,
          spaceBetween:15,
        },
      },
    });

    // related events
    var swiperevents = new Swiper(".relared-event-stack", {
      slidesPerView: 3,
      spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        360: {
          slidesPerView: 1,
          spaceBetween:9,
        },
        640: {
          slidesPerView: 2,
          spaceBetween:15,
        },
        768: {
          slidesPerView:2,
          spaceBetween:15,
        },
        1024: {
          slidesPerView:3,
          spaceBetween:15,
        },
      },
    });

    // toggle password to text
    $('.toggle-password').click(function() {
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        var input = $(this).siblings('.password-input');
        if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        } else {
        input.attr('type', 'password');
        }
    });

    
    // $(".radio-toggle").change(function() {
    //     //alert('eeee');
    //     if ($(this).is(":checked")=== 'true') {
  
    //         $(this).closest(".billing-group").next(".billing-form").slideToggle();
    //         alert('true');
    //     } else {
    //          $(this).closest(".billing-group").next(".billing-form").slideUp();
    //          console.log('uncheck');
    //          alert('false');
    //     }
    // });


    
    // When any radio in the group changes
    $('input[name="optradio"]').change(function() {
        if ($("#radio2").is(':checked')) {
            $(".billing-form").slideDown();
        } else {
            $(".billing-form").slideUp();
        }
    });

})(jQuery);