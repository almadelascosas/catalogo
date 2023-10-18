function obtenerPedidosMercadoPago(){
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'pedidos/obtenerPedidosMercadoPago/',
        data: "data='asd'",
        success: function(res){
            //console.log(res.results);
            var envio = JSON.stringify(res.results);
            var pedidos = [];
            var estatus = [];
            for (let index = 0; index < res.results.length; index++) {
                var pedido = res.results[index].description;
                var estatu = res.results[index].status;
                pedidos.push(pedido);
                estatus.push(estatu);
            }
            verPedidos(pedidos,estatus);
        }
    });
}

function verPedidos(pedidos,estatus){
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'pedidos/verPedidosMercadoPago/',
        data: "pedidos="+pedidos+"&estatus="+estatus,
        success: function(res){
            $("#pedidos_nuevos .table-responsive").html(res);
        }
    });
}