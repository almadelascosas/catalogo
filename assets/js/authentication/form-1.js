$(".controls a").click(function(){
    var elem = $(this).attr("href");
    $(".form-login").addClass("d-none");
    $(elem).removeClass("d-none");
    $(".controls a").removeClass("active");
    $(this).addClass("active");
});