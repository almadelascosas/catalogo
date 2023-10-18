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

function selectImage(mediosid,mediosurl){
    $(elementoGaleria).attr("src",mediosurl);
    $("#categorias_imagen").attr("value",mediosid);
}
function uploadImage(valor){
    $(".mask-semi").css("display","block");
    var formData = new FormData();
    var files = $(valor)[0].files[0];
    formData.append('medios_attachment',files);
    $.ajax({
        url: 'medios/guardarAjax',
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
    $("#categorias_banner_desktop").attr("value",mediosid);
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

function selectImageBanner(mediosid,mediosurl){
    $(elementoGaleriaBanner).attr("src",mediosurl);
    $("#categorias_banner_mobile").attr("value",mediosid);
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