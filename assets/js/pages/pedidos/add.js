var item_nro_guia = undefined;
var item_nombre_empresa = undefined;

function popEmpresa(item){
    event.preventDefault();
    var valor = $(item).val();
    let idObj = $(item).attr('data-modal-id')

    if (valor=="Enviado") {
        var nro_guia = $(item).siblings("[name='nro_guia[]']").val();
        var nombre_empresa = $(item).siblings("[name='nombre_empresa[]']").val();
        item_nro_guia = $(item).siblings("[name='nro_guia[]']");
        item_nombre_empresa = $(item).siblings("[name='nombre_empresa[]']");

        $("#nro_guia").val(nro_guia);
        $("#nombre_empresa").val(nombre_empresa);
        
        $("#ModalGuia_"+idObj).modal("show");
        $(item).siblings(".btn-edit-guia").removeClass("d-none");
    }else if(valor=="En preparación"){
        $('#ModalEnPreparacion').modal()
    }else{
        $(item).siblings(".btn-edit-guia").addClass("d-none");
    }
    
}

function dejarPreparacion(){
    $('#coordinadora_estado').val('')
    $('#ModalEnPreparacion').modal('hide')

}

function envioCoordinadora(){
    $('#coordinadora_estado').val('1')
    $('#ModalEnPreparacion').modal('hide')
}

function editarGuia(){
    var nro_guia = $("#nro_guia").val();
    var nombre_empresa = $("#nombre_empresa").val();
    
    if (item_nro_guia!=undefined) {
        $(item_nro_guia).val(nro_guia);
    }
    if (item_nombre_empresa!=undefined) {
        $(item_nombre_empresa).val(nombre_empresa);
    }

    $("#ModalGuia").modal("hide");

}
/*
$('.chat-notas-pedidos .cuerpo').animate({
    scrollTop: $(".col-8:last").offset().top
}, 500);
*/

function mostrar_cuerpo_vendedor(vendedor){
    event.preventDefault();
    if (vendedor!=undefined) {
        $(".cuerpo-mostrar-vendedores").addClass("d-none");
        $("#cuerpo-vendedor-"+vendedor).removeClass("d-none");
        $("#btn-reelegir_vendedores").removeClass("d-none");
        $(".pie-chat").removeClass("d-none");
        $("[name='notas_pedidos_usuario_dirigido']").val(vendedor);       
    }
    setTimeout(function(){
        $("#cuerpo-vendedor-"+vendedor).animate({
            scrollTop: $("#cuerpo-vendedor-"+vendedor+" .col-8:last").offset().top
        }, 500);
    },100);
}
function reelegir_vendedores() {
    $(".cuerpo-mostrar-vendedores").removeClass("d-none");
    $(".chat-notas-pedidos .cuerpo").addClass("d-none");
    $("#btn-reelegir_vendedores").addClass("d-none");
    $(".pie-chat").addClass("d-none");
    $("[name='notas_pedidos_usuario_dirigido']").val(0);
}

function enviarMensaje() {
    event.preventDefault();
    var mensaje = "";
    mensaje += '<div class="mensaje-enviado mensaje-cargando offset-4 col-8 mb-4">';
    mensaje += '<p class="mensaje">';
    mensaje += 'Enviando...';
    mensaje += '</p>';
    mensaje += '</div>';
    $(".chat-notas-pedidos .cuerpo").not(".d-none").append(mensaje);
    var notas_pedidos_pedido_id = $('[name="notas_pedidos_pedido_id"]').val();
    var notas_pedidos_usuario_dirigido = $('[name="notas_pedidos_usuario_dirigido"]').val();
    var notas_pedidos_tipo = $('[name="notas_pedidos_tipo"]').val();
    var notas_pedidos_mensaje = $('[name="notas_pedidos_mensaje"]').val();

    $("#notas_pedidos_tipo").val(0);
    $("#notas_pedidos_mensaje").val("");
    $('.chat-notas-pedidos .cuerpo').animate({
        scrollTop: $(".col-8:last").offset().top
    }, 500);

    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'pedidos/addNota/',
        data: "notas_pedidos_pedido_id="+notas_pedidos_pedido_id+"&notas_pedidos_usuario_dirigido="+notas_pedidos_usuario_dirigido+"&notas_pedidos_mensaje="+notas_pedidos_mensaje+"&notas_pedidos_tipo="+notas_pedidos_tipo,
        success: function(res){
            $(".mensaje-cargando").remove();
            var notifi = "";
            if (notas_pedidos_tipo==1) {
                notifi=" notificacion "
            }
            var mensaje = "";
            mensaje += '<div class="mensaje-enviado '+notifi+' offset-4 col-8 mb-4">';
            mensaje += '<p class="autor-fecha">';
            mensaje += '<strong>'+res.autor+'</strong>';
            mensaje += '<span class="fecha"> '+res.fecha+'</span>';
            mensaje += '</p>';
            mensaje += '<p class="mensaje">';
            mensaje += ''+res.mensaje+'';
            mensaje += '</p>';
            mensaje += '</div>';
            $(".chat-notas-pedidos .cuerpo").not(".d-none").append(mensaje);
            $('.chat-notas-pedidos .cuerpo').animate({
                scrollTop: $(".col-8:last").offset().top
            }, 500);
        }
    }); 
}