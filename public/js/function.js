var ctrl = new ScrollMagic.Controller({
    globalSceneOptions: {
        triggerHook: 'onLeave'
    }
});
$("input[name$='paymentMethod']").click(function() {
    var test = $(this).val();

    $("button.checkout-btn").hide();
    $("#method" + test).show();
});
$("section").each(function() {
    new ScrollMagic.Scene({
        triggerElement: this,
        duration: '50%'
    })
    .setPin(this)
    .addTo(ctrl);
});
var home_bannerHeight = $('.home__banner').outerHeight();
var scene1 = new ScrollMagic.Scene({
    triggerElement: "#banner",
    duration: 0,
    triggerHook: 0,
})
// .setTween("#x-icon", { top: "550px", scale: 0.07 })
.on("enter", function() {
    $(".home__banner").addClass('setopacity');
    $(".home-about").removeClass('setopacity');
})
.on("leave", function() {
    $(".home__banner").removeClass('setopacity');
    $(".home-about").addClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about").offset().top - 100},
    //     'slow');
})
.addTo(ctrl);
scene1.addIndicators();

var home_aboutHeight = $('.home-about').outerHeight();
var scene2 = new ScrollMagic.Scene({
    triggerElement: "#about",
    duration: 0,
    triggerHook: 0,
})
.on("enter", function() {
    $(".home-about").addClass('setopacity');
    $(".service__wrapper").removeClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about_pattern").offset().top - 200},
    //     'slow');
})
.on("leave", function() {
    $(".home-about").removeClass('setopacity');
    $(".service__wrapper").addClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about_pattern").offset().top - 100},
    //     'slow');
})
.addTo(ctrl);
scene2.addIndicators();


var home_aboutHeight = $('.service__wrapper').outerHeight();
var scene3 = new ScrollMagic.Scene({
    triggerElement: ".service__wrapper",
    duration: 0,
    triggerHook: 0,
})
.on("enter", function() {
    $(".home-about").addClass('setopacity');
    $(".service__wrapper").removeClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about_pattern").offset().top - 200},
    //     'slow');
})
.on("leave", function() {
    $(".home-about").removeClass('setopacity');
    $(".service__wrapper").addClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about_pattern").offset().top - 100},
    //     'slow');
})
.addTo(ctrl);
scene3.addIndicators();

var home_aboutHeight = $('.dual__section__wrapper').outerHeight();
var scene4 = new ScrollMagic.Scene({
    triggerElement: ".dual__section__wrapper",
    duration: 0,
    triggerHook: 0,
})
.on("enter", function() {
    $(".home-about").addClass('setopacity');
    $(".service__wrapper").removeClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about_pattern").offset().top - 200},
    //     'slow');
})
.on("leave", function() {
    $(".home-about").removeClass('setopacity');
    $(".service__wrapper").addClass('setopacity');
    // $('html,body').animate({
    //     scrollTop: $(".home-about_pattern").offset().top - 100},
    //     'slow');
})
.addTo(ctrl);
scene4.addIndicators();