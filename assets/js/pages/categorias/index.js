/*$('.dropify').dropify({
    messages: { 
        'default': 'Click para subir o Arrastre', 
        'remove':  '<i class="flaticon-close-fill">X</i>', 
        'replace': 'Subir o Arrastre' 
    }
});*/
function confirmDelete(valor){
    $("#btnEliminar").attr("onclick","del("+valor+");");
    $('#ModalDel').modal('toggle')
}
function del(valor){
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'categorias/delete',
        data: "id="+valor,
        success: function(res){
            if(res.result==1)
            {
                alert(res.mensaje);
                location.reload();
            }
            else {
                console.log("error");
            }
        },
        error: function(){
            console.log("error de red");
            
        }
    }); //AJAX
}

$(window).on('load', function() {
    $(".mask").css("display","none");
})