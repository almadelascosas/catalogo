function selectImage(mediosid,mediosurl){
    $(elementoGaleria).attr("src",mediosurl);
    $("#image_profile").attr("value",mediosid);
}

function selectImageMini(mediosid,mediosurl){
    $(elementoGaleriaMini).attr("src",mediosurl);
    $("#usuarios_banner").attr("value",mediosid);
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



function cargarMunicipios(){
    var id_municipio = $("#usuarios_departamento").val();
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'general/obtenerMunicipios/'+id_municipio,
        data: "conf=si",
        success: function(res){
            $('#usuarios_municipio').html(res);
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}
if (ubi==1) {
    cargarMunicipios();
}