function leerPadres(){
    var agg = "";
    agg+='<option value="0">Ninguno</option>';
    $("#drag-elements .rounded").each(function(){
        var valor = $("[name='menu_id[]']", this).val();
        var texto = $("[name='menu_texto[]']", this).val();
        var padre = $("[name='menu_padre[]']", this).val();
        if (padre==0) {
            agg+='<option value="'+valor+'">'+texto+'</option>';
        }
    });
    $("#menu_padre_agg").html(agg);
}
function agregarMenu(){
    suma_padre++;
    var texto = $("#menu_texto_agg").val();
    var enlace = $("#menu_enlace_agg").val();
    var padre = $("#menu_padre_agg").val();
    if (enlace=="" || texto=="") {
        alert("Debes completar los campos obligatorios (Texto y Enlace)");
    }else{
        if (padre!=0) {
            $("#item-"+padre).append('<div class="col-11 offset-1 p-2 mt-2 rounded border"><a target="_blank" href="'+enlace+'">'+texto+'</a><span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x<span></span></span><input value="'+texto+'" type="hidden" name="menu_texto[]"><input value="'+enlace+'" type="hidden" name="menu_enlace[]"><input value="'+padre+'" type="hidden" name="menu_padre[]"><input value="'+suma_padre+'" type="hidden" name="menu_id[]"></div>');
        }else{
            $("#drag-elements").append('<div id="item-'+suma_padre+'" class="rounded border"><a target="_blank" href="'+enlace+'">'+texto+'</a><span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x<span></span></span><input value="'+texto+'" type="hidden" name="menu_texto[]"><input value="'+enlace+'" type="hidden" name="menu_enlace[]"><input value="'+padre+'" type="hidden" name="menu_padre[]"><input value="'+suma_padre+'" type="hidden" name="menu_id[]"></div>');
        }
        $("#menu_texto_agg").val("");
        $("#menu_enlace_agg").val("");
        leerPadres();
    }
}

$('#productos_video').dropify({
    messages: {
        'default': 'Click o arrastre para subir vídeo',
        'remove':  '<i class="flaticon-close-fill">X</i>',
        'replace': 'Click o arrastre para subir vídeo'
    }
});

function selectImage(mediosid,mediosurl){
    $(elementoGaleria).attr("src",mediosurl);
    $("#banner_imagen").attr("value",mediosid);
}
function uploadImage(valor){
    $(".mask-semi").css("display","block");
    var formData = new FormData();
    var files = $(valor)[0].files[0];
    formData.append('medios_attachment',files);
    $.ajax({
        url: base_url+'medios/guardarAjax',
        type: 'post',
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response.medio);
            var medios = response.medio;            
            medios = medios.replace("{","");            
            medios = medios.replace("}","");            
            medios = medios.split("\",\"");
            
            var medios_id = medios[0];
            medios_id = medios_id.replace(/\"/g,'');
            medios_id = medios_id.replace('medios_id:','');

            var medios_url = medios[1];
            medios_url = medios_url.replace(/\"/g,'');
            medios_url = medios_url.replace('medios_url:','');
            
            $("#btn-select-imgmini").attr("onclick","selectImage("+medios_id+",'"+base_url+medios_url+"')");
            $("#select-imgmini").removeClass("d-none");
            $(".mask-semi").css("display","none");
        }
    });
    return false;
}

function selectImageMini(mediosid,mediosurl){
    $(elementoGaleriaMini).attr("src",mediosurl);
    $("#banner_imagen_mobile").attr("value",mediosid);
}
function uploadImageMini(valor){
    $(".mask-semi").css("display","block");
    var formData = new FormData();
    var files = $(valor)[0].files[0];
    formData.append('medios_attachment',files);
    $.ajax({
        url: base_url+'medios/guardarAjax',
        type: 'post',
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response.medio);
            var medios = response.medio;            
            medios = medios.replace("{","");            
            medios = medios.replace("}","");            
            medios = medios.split("\",\"");
            
            var medios_id = medios[0];
            medios_id = medios_id.replace(/\"/g,'');
            medios_id = medios_id.replace('medios_id:','');

            var medios_url = medios[1];
            medios_url = medios_url.replace(/\"/g,'');
            medios_url = medios_url.replace('medios_url:','');
            
            $("#btn-select-imgmini").attr("onclick","selectImageMini("+medios_id+",'"+base_url+medios_url+"')");
            $("#select-imgmini").removeClass("d-none");
            $(".mask-semi").css("display","none");
        }
    });
    return false;
}


function n(id) {
    return document.getElementById(id);
}

dragula([n('drag-elements')], {
    revertOnSpill: true
});
  /*.on('drop', function(el) {
    if ($('drop-target').children.length > 0) {
      $('display').innerHTML = $('drop-target').innerHTML;
    } else {
      $('display').innerHTML = "Display";
    }
  
  });*/
  