$( document ).ready(function() {
    $('.checkpadre').click(function(){
        var fields = $("input[class='checkpadre']").serializeArray(); 
        console.log(fields)
        if (fields.length > 2) { 
            toastr.warning("Solo puedes seleccionar 2 categorias y 2 subcategorias")
            return false;
        }

        //Si Descheckeamos... los hijos tbn quitamos check
        let estadoCheckPadre = $(this).is(':checked')
        let conteo = $(this).attr('data-conteo')
        if(!estadoCheckPadre){
            $("input[data-catpadre='"+conteo+"']").prop('checked', false)
        }
    })

    $('.checkhijo').click(function(){
        let idIndex = $(this).attr('data-catpadre')
        //Pero primero verificamos que no esten seleccionados mas de 2 categorias (fuera de el padre seleccionado)
        let estadoCheckPadre = $('#categorias_padre-'+idIndex).is(':checked')

        var fields = $("input[class='checkhijo']").serializeArray(); 
        if (fields.length > 2) { 
            toastr.warning("solo puedes seleccionar 2 subcategorias")
            return false;
        }

        if(!estadoCheckPadre){
            var fieldsPadre = $("input[class='checkpadre']").serializeArray(); 
            if (fieldsPadre.length >= 2) { 
                toastr.warning("Debes seleccionar subcategoria de las categorias seleccionadas")
                return false;
            }
        }  


        $('#categorias_padre-'+idIndex).prop('checked', true)
    })
})

$('#productos_video_upload').dropify({
    messages: {
        'default': 'Click o arrastre para subir vídeo',
        'remove':  '<i class="flaticon-close-fill">X</i>',
        'replace': 'Click o arrastre para subir vídeo'
    }
});

function selectImage(mediosid,mediosurl){
    $(elementoGaleria).attr("src",mediosurl);
    $("#productos_imagen_destacada").attr("value",mediosid);
}
function selectImageMini(mediosid,mediosurl){
    $(elementoGaleriaMini).attr("src",mediosurl);
    $(elementoGaleriaMini).siblings('.input-img').html("<input type='hidden' name='productos_imagenes[]' value='"+mediosid+"'>");
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
var subir_video = 0;
var form_val = 0;
function uploadVideo(){
    if (subir_video==1) {       
        $(".div-progress-bar").removeClass("d-none");
        var formData = new FormData();
        var files = $("#productos_video_upload")[0].files[0];
        formData.append('productos_video_upload',files);
        $.ajax({
            url: base_url+'productos/subirvideo',
            type: 'post',
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.onprogress = function (event) {
                    var perc = Math.round((event.loaded / event.total) * 100);
                    $("#p-nombre").text(files.name);
                    $("#progressBar1").text(perc + '%');
                    $("#progressBar1").css('width', perc + '%');
                };
                return xhr;
            },
            success: function(response) {
                $("#productos_video").val(response.medios_url);
                validar=1;
                $("#form-producto").submit();
            }
        });
    }else{
        validar=1;
        $("#form-producto").submit();
    }
}


var validar = 0;
function valFormProducto(){
    if (validar==0) {
        event.preventDefault();
        var val_dep = $("#productos_departamentos-0").val();
        var val_mun = $("#productos_municipios-0").val();
        var precio_normal = $("#productos_precio").val();
        var precio_oferta_or = $("#productos_precio_oferta").val();
        var precio_oferta = $("#productos_precio_oferta").val();

        precio_normal = parseInt(precio_normal);
        precio_oferta = parseInt(precio_oferta);

        var precio_validado = 0;
        
        if (precio_oferta_or=="" || precio_oferta==0 || (precio_oferta < precio_normal && precio_oferta > 0)) {
            var precio_validado = 1;
        }
        
        if (
            val_dep!=undefined
            && val_dep!=""
            && val_mun!=undefined
            && val_mun!=""
            ) {
                if (precio_validado==1) {
                    $(".fila").each(function(){
                        var valor = "";
                        $(".opcion", this).each(function(){
                            var texto = $("[name='option_text_fila[]']", this).val();
                            var tipo = $("[name='option_tipo_fila[]'], this").val();
                            var precio = $("[name='option_precio_fila[]']", this).val();
                            valor+=texto+"/,/"+tipo+"/,/"+precio+"],[";
                        });
                        console.log(valor);
                        $(".addons_opciones", this).val(valor);
                    });
                    uploadVideo();
                    validar=1;
                }else{
                    alert("El precio de oferta no puede ser mayor o igual al precio del producto, tampoco debe ser menor a 0");
                    setTimeout(function(){
                        var posicion = $("div.precios").offset().top;
                        $("html, body").animate({
                            scrollTop: posicion
                        }, 500); 
                    },500);
                }
        }else{
            alert("Debes seleccionar al menos una ubicación local");
            $("#v-envio-tab").click();
            setTimeout(function(){
                var posicion = $("#envio-tab").offset().top;
                $("html, body").animate({
                    scrollTop: posicion
                }, 500); 
            },500);
        }
    }else{
        $("#form-producto").submit();
    }
}


function cargarMuniProd(valor,municipio){
    var id_departamento = $("#productos_departamentos-"+valor).val();
    if (municipio==undefined) {
        municipio = 0;
    }
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'home/obtenerMunicipios/'+id_departamento+"/"+municipio,
        data: "conf=si",
        success: function(res){
            $('#productos_municipios-'+valor).html(res);
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}


function cargarDepaProd(elemento,valor){
    if (valor==undefined) {
        valor=0;
    }
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'home/obtenerDepartamentos/'+valor,
        data: "conf=si",
        success: function(res){
            $('#productos_departamentos-'+elemento).html(res);
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}

/*
if (cargaMuni==1) {
    cargarMuniProd(1);
}


if (Array.isArray(cargaMuni)==true) {
    for (let index = 0; index < cargaMuni.length; index++) {
        cargarMuniProd(cargaMuni[index],cargarMuniProdsMuni[index]);
    }
}
*/

function aggUbiProducto(){
    contDep++;
    var agg = "";
    agg += '<div class="row">';
    agg += '<div class="col-12">';
    agg += '<label>Ubicación '+contDep+':</label>';
    agg += '</div>';
    agg += '<div class="col-10">';
    agg += '<div class="row">';
    agg += '<div class="col-sm-6 pr-md-2 col-12">';
    agg += '<select onchange="cargarMuniProd('+contDep+');" id="productos_departamentos-'+contDep+'" name="productos_departamentos[]"  class="form-control">';
    agg += '<option value="">Seleccione...</option>';
    agg += '</select>';
    agg += '</div>';
    agg += '<div class="col-sm-6 px-md-2 col-12">';
    agg += '<select id="productos_municipios-'+contDep+'" name="productos_municipios[]"  class="form-control">';
    agg += '</select>';
    agg += '</div>';
    agg += '</div>';
    agg += '</div>';
    
    agg += '<div class="col-2">';
    agg += '<button onclick="$(this).parent().parent().remove();contDep--;" type="button" class="btn bg-danger text-white">×</button>';
    agg += '</div>';

    agg += '</div>';
    
    $("#ubicaciones-producto").append(agg);

    cargarDepaProd(contDep);

}

function buscarProducto(){
    var clave = $("#productos-vinculado").val();
    var productos = [];
    $("[name='productos_relacionados[]']").each(function(){
        var valor = $(this).val();
        productos.push(valor);
    });
    console.log(productos);
    
    if(clave!=""){
        $('#productos-search').html("...");
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'productos/buscarProductos/'+clave,
            data: "productos="+productos,
            success: function(res){
                $('#productos-search').html(res);
            },
            error: function(){
                console.log("error de red");
            }
        }); //AJAX
    }

}

function addProductRel(){
    var productos = [];
    $("#productos-search label").each(function(){
        var idProd = $(this).children("input").val();
        var titulo = $(this).children("span").html();
        if ($(this).children("input").prop('checked')) {
            productos.push({
                id:idProd,
                titulo:titulo
            });
        }
    });
    
    for (let i = 0; i < productos.length; i++) {
        var add = "";
        add+='<div class="card mb-2">';
        add+='    <input type="hidden" name="productos_relacionados[]" value="'+productos[i].id+'">';
        add+='    <p class="m-0">';
        add+=productos[i].titulo;
        add+='<span onclick="$(this).parent().parent().remove();buscarProducto();" class="p-2 float-right icon-cross"></span>';
        add+='    </p>';
        add+='</div>';
        $("#productos-selected").append(add);
    }

    buscarProducto();
    
}
