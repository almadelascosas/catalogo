
var html5Slider = document.getElementById('uislider');

var mindefault = $("#mindefault").val();
mindefault = parseFloat(mindefault);
var maxdefault = $("#maxdefault").val();
maxdefault = parseFloat(maxdefault);

var minprice = $("#minprice").val();
minprice = parseFloat(minprice);
var maxprice = $("#maxprice").val();
maxprice = parseFloat(maxprice);
noUiSlider.create(html5Slider, {
    start: [ minprice, maxprice ],
    connect: true,
    step: 100,
    tooltips: false,
    range: {
        'min': mindefault,
        'max': maxdefault
    }
});
html5Slider.noUiSlider.on('update', function ( values, handle, unencoded, isTap, positions ) {
    console.log(values[0]);
    $("#min-p").html(values[0]);
    $("#max-p").html(values[1]);
    $("#minprice").val(values[0]);
    $("#maxprice").val(values[1]);
});