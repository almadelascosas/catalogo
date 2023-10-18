function revisado(id,elemento){
    $(".mask-semi").css("display","block");
    var estatus = 0;
    if ($(elemento).prop('checked')) {
        estatus = 1;
    }
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'corporativos/edit_revisado',
        data: "id="+id+"&estatus="+estatus,
        success: function(res){
            if(res.result==1)
            {
                $(".mask-semi").css("display","none");
                console.log(res.mensaje);
                alert("Éxito");
            }
            else {
                $(".mask-semi").css("display","none");
                alert("error");
            }
        },
        error: function(){
            $(".mask-semi").css("display","none");
            console.log("error de red");
            alert("error de red");
        }
    }); //AJAX
}