$(document).ready(function() {
    $('.required').blur(function(){
        // input[class=required]
        let vacios = 0
        $('.required').each(function(){
            if($(this).val() === "") vacios++
        })
        if(vacios===0) $('#btn-relizar-pedido').attr('disabled', false).removeClass('btn-secondary').addClass('btn-green-alma')
    })
});

function confirmDelete(valor){
    $("#btnEliminar").attr("onclick","del("+valor+");");
    $('#ModalDel').modal('toggle')
}

function del(valor){
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'productos/delete',
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
