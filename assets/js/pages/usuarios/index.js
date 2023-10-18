function confirmDelete(valor){
    $("#btnEliminar").attr("onclick","del("+valor+");");
    $('#ModalDel').modal('toggle')
}
function del(valor){
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'usuarios/delete',
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