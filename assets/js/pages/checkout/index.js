$(document).ready(function() {
    

    $('#pedidos_municipio').change(function(){
        let idMun = $(this).val()
        $.ajax({
            type: "post",
            data:{ 
                idmun: idMun,
                subt: $("#pedidos_precio_subtotal").val()
            },
            url: 'checkout/recalcularEnvio',
            dataType:"json",
            success: function(respuesta){
                $("#tableEnvios tbody").empty().append(respuesta.html);
                $("#span_pedidos_precio_envio").text(respuesta.enviototalformat)
                $("#pedidos_precio_envio").val(respuesta.enviototal)

                $("#pedidos_precio_total").val(respuesta.totalcompra)
                $('.precioTotal').text('$'+respuesta.totalcompraformat)

            }
        });
    })
});

$("#tableEnvios tbody").empty().append(html);