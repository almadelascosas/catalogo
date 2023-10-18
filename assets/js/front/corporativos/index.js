$('#carrusel-clientes').owlCarousel({
    loop:false,
    dots:false,
    margin:10,
    responsiveClass:true,
    responsive:{
        0:{
            items:2,
            nav:true
        },
        800:{
            items:3,
            nav:true
        },
        1000:{
            items:4,
            nav:true
        },
        1200:{
            items:6,
            nav:true
        },
    }
})
$('#carrusel-clientes-mobile').owlCarousel({
    loop:true,
    dots:false,
    margin:10,
    autoplay:true,
    autoplayTimeout:2500,
    responsiveClass:true,
    responsive:{
        0:{
            items:2,
            nav:true
        }
    }
})


var altura = $("header").outerHeight();
$("body").css("padding-top",altura);

$( window ).resize(function() {
    var altura = $("header").outerHeight();
    $("body").css("padding-top",altura);
    
});   


var startNum;
var currentNum;
var intervalo = null

function addClassDelayed(jqObj, c, to) {    
    setTimeout(function() { jqObj.addClass(c); }, to);
}

function anim(link) { 
    addClassDelayed($("#countdown"), "puffer", 600);
    if (currentNum == 0) currentNum = startNum-1; else currentNum--;
    if(currentNum==0){
        //window.open(link, '_blank')
        //location.href=link
        clearInterval(intervalo);
        $('.bd-example-modal-lg').modal('toggle'); 
    }

    $('#countdown').html(currentNum+1);
    //$('#countdown').removeClass("puffer");
}
      
$(function() {
    $('.btnTranfer').click(function(){
        let link = $(this).attr('data-href')
        startNum = 7; 
        currentNum = startNum;
        $("#countdown").html(currentNum); // init first time based on n
        intervalo = setInterval(function(){anim(link)},1325);
    })

});
