$(document).ready(function() {
    $('[name="ckMedio[]"]').click(function() {
        var arr = $('[name="ckMedio[]"]:checked').map(function(){
            return this.value;
        }).get();
        //var str = arr.join(',');

        if(parseInt(arr.length)===0) $('#btnElimMultiple').addClass('d-none')
        if(parseInt(arr.length)>0) $('#btnElimMultiple').removeClass('d-none')
    });

    $('#btnElimMultiple').click(function(){
        var arr = $('[name="ckMedio[]"]:checked').map(function(){
            return this.value;
        }).get();
        if(parseInt(arr.length)===0) return false;

        if(!confirm("Esta seguro de eliminar las imagenes seleccionadas?")){
            return false;
        }

    })
}); 

$('.dropify').dropify({
    messages: { 
        'default': 'Click para subir o Arrastre', 
        'remove':  '<i class="flaticon-close-fill">X</i>', 
        'replace': 'Subir o Arrastre' 
    }
});
function confirmDelete(valor){
    $("#btnEliminar").attr("onclick","del("+valor+");");
    $('#ModalDel').modal('toggle')
}

function del(valor){
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'medios/delete',
        data: "id="+valor,
        success: function(res){
            if(res.result==1) {
                location.reload();
            } else {
                console.log("error");
            }
        },
        error: function(){
            console.log("error de red");
            
        }
    });
}