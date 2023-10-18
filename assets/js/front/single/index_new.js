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


/** Nueva función de agregado para pedidos nuevos **/


function addCartNew(idprod,cantidad){
    event.preventDefault();
    var cantidad = $(cantidad).val();
    cantidad = parseFloat(cantidad);
    if (cantidad==undefined) {
        cantidad = 1;
    }
    if (cantidad!=undefined && cantidad > 0) {
        cantidad = parseFloat(cantidad);
        $(".btn-addcart").attr("disabled","disabled");
        $(".btn-addcart").html("<img style='height:30px;' src='"+base_url+"assets/img/loading-buffering.gif'>");
        aumentarCargo(0);
        var productos_id = idprod;
        var productos_addons = $("#addons-producto").val();
        var productos_cantidad = cantidad;
        var productos_precio_sin_addons = $("#precio-producto").val();
        var productos_precio = $("#productos_precio").val();
        /* Envíos */
        var productos_envio_local = $("#productos_envio_local").val();
        var productos_valor_envio_local = $("#productos_valor_envio_local").val();
        var productos_ubicaciones_envio = $("#productos_ubicaciones_envio").val();
        var productos_envio_nacional = $("#productos_envio_nacional").val();
        var productos_valor_envio_nacional = $("#productos_valor_envio_nacional").val();

        var productos_fecha_programada = $("#fechaprogramada").val();

        var productos_vendedor = $("#productos_vendedor").val();
        
        var parametros = $("#parametros").serialize();

        if (parametros==undefined) {
            parametros = "";
        }
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'tienda/addcart/'+idprod,
            data: "parametros="+parametros+
                  "&productos_id="+productos_id+
                  "&productos_addons="+productos_addons+
                  "&productos_cantidad="+productos_cantidad+
                  "&productos_precio_sin_addons="+productos_precio_sin_addons+
                  "&productos_precio="+productos_precio+
                  "&productos_envio_local="+productos_envio_local+
                  "&productos_valor_envio_local="+productos_valor_envio_local+
                  "&productos_ubicaciones_envio="+productos_ubicaciones_envio+
                  "&productos_envio_nacional="+productos_envio_nacional+
                  "&productos_valor_envio_nacional="+productos_valor_envio_nacional+
                  "&productos_vendedor="+productos_vendedor+
                  "&productos_fecha_programada="+productos_fecha_programada,
            success: function(res){
                $('#content-cart').html(res);
                abrirCart();
                soundCart();
                $(".btn-addcart").removeAttr("disabled");
                $(".btn-addcart").html("Comprar");
            },
            error: function(){
                console.log("error de red");
            }
        }); //AJAX
    }else{
        alert("La cantidad debe ser mayor a 0."); // Modificar este pop, colocar un diseño más bonito.
    }
}

function aumentarCargo(valor){
    var valorTotal = $('#precio-producto').val();
    valorTotal = parseFloat(valorTotal);
    var addons = '';
    var count_addons = 0;
    $("#caja-addons .addons").each(function(){
        count_addons++;
        var addons_id = $(this).children('[name="addons_id[]"]').val();
        var addons_tipo = $(this).children('[name="addons_tipo[]"]').val();
        var addons_display = $(this).children('[name="addons_display[]"]').val();
        var addons_titulo = $(this).children('[name="addons_titulo[]"]').val();
        addons_titulo = addons_titulo.replaceAll('"','\'');
        
        var objeto_padre = $(this).children('[name="addons_respuesta_pedido[]"]')[0];

        if (count_addons==1) {
            addons = '{"addons":[{ "addons_id":'+addons_id;
            addons += ',"addons_titulo":"'+addons_titulo+'"';
        }else{
            addons += ',{ "addons_id":'+addons_id;
            addons += ',"addons_titulo":"'+addons_titulo+'"';
        }
        
        if ($(objeto_padre).hasClass('addons-texto')) {
            var valor=$(objeto_padre).val();
            valor = valor.replaceAll('"','\'');
            addons += ',"addons_respuesta":["'+valor+'"]';
            addons += ',"addons_valor":[0]';
        }

        var valorAgregado = '';
        var valorAdicional = '';
        var countAdicional = 0;
        $(this).children('label').each(function(){
            var seleccionado = 0;
            var seleccion = $(this).children('[name="addons_respuesta_pedido[]"]').val();
            var objeto = $(this).children('[name="addons_respuesta_pedido[]"]')[0];
            var objeto2 = $(this).children('[name="addons_respuesta_pedido[]"]');
            var valorExtra = $(this).children('[name="addons_respuesta_cargo[]"]').val();
            if(objeto2.prop('checked')){
                countAdicional++;
                if (countAdicional==1) {
                    valorAgregado = '"'+seleccion+'"';
                    valorAdicional = '"'+valorExtra+'"';
                }else{
                    valorAgregado = valorAgregado+',"'+seleccion+'"';
                    valorAdicional = valorAdicional+',"'+valorExtra+'"';
                }
                if(valorExtra!=undefined && valorExtra!="0" && valorExtra!=0 && valorExtra!=""){
                    valorTotal = valorTotal+parseFloat(valorExtra);
                }
            }
            
            if ($(objeto).hasClass('select-addons')) {
                var valor = $(objeto).val();
                valor = valor.split('/,/');
                valorAgregado = valor;
                valorAgregado = '"'+valor[0]+'"';
                if (valor[3]!=undefined && valor[3]!="" && valor[3]!=0) {
                    valorExtra=valor[3];
                    valorTotal = valorTotal+parseFloat(valorExtra);    
                    valorAdicional = '"'+valorExtra+'"';
                }
                if (valor[1]!=undefined && valor[1]!="" && valor[1]!=0) {
                    valorExtra=valor[1];
                    valorTotal = valorTotal+parseFloat(valorExtra);    
                    valorAdicional = '"'+valorExtra+'"';
                }
            }

        });

        if (valorAgregado!="") {
            addons += ',"addons_respuesta":['+valorAgregado+']';
            addons += ',"addons_valor":['+valorAdicional+']';
        }

    });
    addons += '}]}';
    $("#h4-valor").html("$ "+number_format(valorTotal,0,',','.'));
    $('#productos_precio').val(valorTotal);
    $('#addons-producto').val(addons);
}



// First we check if you support touch, otherwise it's click:
//let touchEvent = 'ontouchstart' in window ? 'touchstart' : 'click';
// Then we bind via thát event. This way we only bind one event, instead of the two as below
//document.getElementById('btn-addcart').addEventListener(touchEvent, someFunction);
// or if you use jQuery:
//$('#btn-addcart').on(touchEvent, someFunction);



    
