/* Nueva función check */
function cambioEstatus(valor){
    if ($(valor).prop("checked")) {
        $(valor).siblings("[name='alma_metodos_pagos_estatus[]']").val("1");
    }else{
        $(valor).siblings("[name='alma_metodos_pagos_estatus[]']").val("0");
    }
}
/*/ */


/** WhatsApp **/

function addnumberWhatsapp(){
    var contacto = "";
    contacto += '<div class="col-12 form-group card mb-4">';
    contacto += '<div class="row">';
    contacto += '<input type="hidden" name="whatsapp_plugin_id[]" value="">';
    contacto += '<div class="col-12">'
    contacto += '<label class="w-100 my-4">'
    contacto += '<input class="check_online" checked name="whatsapp_plugin_estatus[]" type="checkbox" value="1">'
    contacto += '<span></span>'
    contacto += '</label>'
    contacto += '</div>'
    contacto += '<div class="col-md-1 col-2">';
    contacto += '<a class="btn btn-danger" onclick="$(this).parent().parent().parent().remove();" href="#delete-whatsapp">';
    contacto += '<span>X</span>';
    contacto += '</a>';
    contacto += '</div>';
    contacto += '<div class="col-md-3 col-10">';
    contacto += '<label class="w-100">';
    contacto += 'Número de WhatsApp:';
    contacto += '<input type="tel" class="form-control" value="" name="whatsapp_plugin_telefono[]">';
    contacto += '</label>';
    contacto += '</div>';
    contacto += '<div class="col-md-3 col-12">';
    contacto += '<label class="w-100">';
    contacto += 'Nombre:';
    contacto += '<input type="text" class="form-control" maxlength="20" name="whatsapp_plugin_nombre[]" value="">';
    contacto += '</label>';
    contacto += '</div>';
    contacto += '<div class="col-md-3 col-12">';
    contacto += '<label class="w-100">';
    contacto += 'Mensaje:';
    contacto += '<textarea class="form-control" name="whatsapp_plugin_mensaje[]" rows="3"></textarea>';
    contacto += '</label>';
    contacto += '</div>';
    contacto += '<div class="col-md-2 col-12">';
    contacto += '<label class="w-100">';
    contacto += 'Número de atendidos:';
    contacto += '<input type="number" class="form-control" name="whatsapp_plugin_cantidad[]" value="">';
    contacto += '</label>';
    contacto += '</div>';
    contacto += '</div>';
    contacto += '</div>';
    $("#whatsapp-numbers").append(contacto);
}

/** /WhatsApp **/


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
    var categoria = $("#menu_categoria_agg").val();
    if (categoria=="" || texto=="") {
        alert("Debes completar los campos obligatorios (Texto y Enlace)");
    }else{
        if (padre!=0) {
            $("#item-"+padre).append('<div class="col-11 offset-1 p-2 mt-2 rounded border"><a target="_blank" href="'+enlace+'">'+texto+'</a><span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x<span></span></span><input value="'+texto+'" type="hidden" name="menu_texto[]"><input value="'+enlace+'" type="hidden" name="menu_enlace[]"><input value="'+padre+'" type="hidden" name="menu_padre[]"><input value="'+suma_padre+'" type="hidden" name="menu_id[]"></div>');
        }else{
            $("#drag-elements").append('<div id="item-'+suma_padre+'" class="rounded border"><a target="_blank" href="'+enlace+'">'+texto+'</a><span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x<span></span></span><input value="'+texto+'" type="hidden" name="menu_texto[]"><input value="'+enlace+'" type="hidden" name="menu_enlace[]"><input value="'+padre+'" type="hidden" name="menu_padre[]"><input value="'+suma_padre+'" type="hidden" name="menu_id[]"><input value="'+categoria+'" type="hidden" name="menu_categorias_id[]"></div>');
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
  


var elementoGaleriaBanner = "";
function galeriaBanner(elemento){
    elementoGaleriaBanner = elemento;
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'medios/galeriaBanner',
        data: "conf=si",
        success: function(res){
            $('#content-gallery').html(res);
            $('#medios-galeria').dropify({
                messages: {
                    'default': 'Click para subir o Arrastre',
                    'remove':  '<i class="flaticon-close-fill">X</i>',
                    'replace': 'Subir o Arrastre'
                }
            });
            $('#ModalGallery').modal('show');
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}

function selectImageBanner(mediosid,mediosurl){
    $(elementoGaleriaBanner).children(".principal").attr("src",mediosurl);
    $(elementoGaleriaBanner).children("input[type='hidden']").attr("value",mediosid);
}
function uploadImageBanner(valor){
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
            
            $("#btn-select-imgmini").attr("onclick","selectImageBanner("+medios_id+",'"+base_url+medios_url+"')");
            $("#select-imgmini").removeClass("d-none");
            $(".mask-semi").css("display","none");
        }
    });
    return false;
}

function tipo_redireccion(elemento){
    var valorElemento = $(elemento).val();
    $(elemento).parent().parent().siblings(".tipos_red").addClass("d-none");
    $(elemento).parent().parent().siblings(".tipos_red").siblings(".tipos_red").addClass("d-none");
    if (valorElemento==0) {
    
        $(elemento).parent().parent().siblings(".id_redireccion").removeClass("d-none");
        var add = 'ID de redirección: <select class="form-control" name="banner_app_id_redireccion[]">';
        for (let index = 0; index < categorias.length; index++) {
            add+='<option value="'+categorias[index].categorias_id+'">'+categorias[index].categorias_nombre+'</option>';
        }
        add += '</select>';
        $(elemento).parent().parent().siblings(".id_redireccion").children("label").html(add);
    
    }else if(valorElemento==1){

        $(elemento).parent().parent().siblings(".id_redireccion").removeClass("d-none");
        var add = 'ID de redirección: <select class="form-control" name="banner_app_id_redireccion[]">';
        for (let index = 0; index < productos.length; index++) {
            add+='<option value="'+productos[index].productos_id+'">'+productos[index].productos_titulo+'</option>';
        }
        add += '</select>';
        $(elemento).parent().parent().siblings(".id_redireccion").children("label").html(add);
    
    }else{
        $(elemento).parent().parent().siblings(".tipos_red").siblings(".url_redireccion").removeClass("d-none");    
    }
}





