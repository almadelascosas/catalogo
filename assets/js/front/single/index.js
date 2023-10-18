$('#carrusel-miniproduct').owlCarousel({
    loop:false,
    margin:4,
    nav:true,
    dots:false,
    responsiveClass:true,
    responsive:{
        0:{
            items:4
        }
    }
});
var owl = $('#carrusel-img-principal').owlCarousel({
    loop:true,
    margin:4,
    nav:false,
    responsiveClass:true,
    dotsContainer: '#carousel-custom-dots',
    responsive:{
        0:{
            items:1
        }
    }
});
$('.owl-dot-2').click(function () {
    owl.trigger('to.owl.carousel', [$(this).index(), 300]);
  });