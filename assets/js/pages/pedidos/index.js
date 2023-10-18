$(document).ready(function(){
    $(".mostrar_pedidos").click(function(){
        event.preventDefault();
        $(".mostrar_pedidos").removeClass("active");
        $(this).addClass("active");
        var mostrar = $(this).attr("href");
        $(".cajetin-pedidos .listado").addClass("d-none");
        $(mostrar).removeClass("d-none");
    });
});

function actualizarPedidosMercadoPago(){
    $('.span1').removeClass('d-none')
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'pedidos/obtenerPedidosMercadoPago/',
        data: "data='asd'",
        success: function(res){
            var envio = JSON.stringify(res.results);
            var pedidos = [];
            var estatus = [];
            for (let index = 0; index < res.results.length; index++) {
                var pedido = res.results[index].description;
                var estatu = res.results[index].status;
                pedidos.push(pedido);
                estatus.push(estatu);
            }
            $('.span1').addClass('d-none')
            verPedidos(pedidos,estatus);
        }
    });
}

function verPedidos(pedidos,estatus){
    $('.span2').removeClass('d-none')
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'pedidos/verPedidosMercadoPago/',
        data: {
            "pedidos":pedidos,
            "estatus":estatus
        },
        success: function(res){
            $('.span2').addClass('d-none')
            $('.span3').removeClass('d-none')
            setTimeout(function() { 
                location.reload()
            }, 3000);
        }
    });
}

$('#pedidos_fecha_inicio').change(function(){
    let baseurl = $('#baseurl').val()
    let fini = $('#pedidos_fecha_inicio').val()

    if(fini!==''){
        $.ajax({
            type: "POST",
            url: baseurl+"pedidos/calffin",
            data: {
                fini: fini
            },
            success: function(datos) {
                let json = JSON.parse(datos)
                if(json.resp==='ok'){ 
                    $('#pedidos_fecha_final').val(json.ffin)
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $.Notification.notify('error', 'top left', 'Error!', '!Ups, ocurrio un error al tratar de calcular la fecha y hora de llegada<br>'+textStatus+'<br>'+errorThrown);
            }
        })  
    }
})