 $(".main__nav ul li a").each(function() {
    if ($(this).next().length > 0) {
        $(this).addClass("parent");
    };
})
$(".main__nav ul li").unbind('mouseenter mouseleave');
$(".main__nav ul li a.parent").unbind('click').bind('click', function(e) {
    // must be attached to anchor element to prevent bubbling
    e.preventDefault();
    if($(this).parent("li").hasClass("active")) {
        $(this).parent("li").removeClass("active");
        return;
    }
    $(".main__nav ul li").removeClass('active');
    $(this).parent("li").parent().parent().addClass("active");
    $(this).parent("li").toggleClass("active");
});


var url = window.location;
$('.main__nav a[href="'+url+'"]').parent().addClass('active');
$('.main__nav a').filter(function(){
    return this.href==url;
}).parent().addClass('active');

function statusToggle(route) {
    $.ajax({
        url: route,
        success: function(resp) {
            if (resp.status == 200) {
                toastFire('success', resp.message);
            } else {
                toastFire('error', resp.message);
            }
        }
    });
}

