function loadedbutton(boton){
    $(boton).attr("disabled","disabled");
    $(boton).html("<img style='height:40px;' src='"+base_url+"assets/img/loading-buffering.gif'>");
}

function ModalNotificacion(elemento){
    event.preventDefault();
    let pedidos_id = $(elemento).attr("pedidoid");
    let url = $(elemento).attr("href");

    $("#pedidos_id_notificacion").val(pedidos_id);
    $("#btn-ir-pedido").attr("href",url);

    $("#ModalNotificacion").modal("show")
    
}

function cambiotxt(item1,item2){
    var valor = $(item1).val();
    $(item2).html(valor);
}

function enviarNotificacion(){
    event.preventDefault();   
    let pedidos_id = $("#pedidos_id_notificacion").val();    
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        url: base_url+'panel/notificarPedidoRetrasado',
        data: "pedidos_id="+pedidos_id,
        success: function(res){
            Snackbar.show({
                text: res.mensaje,
                actionText: 'X',
                pos: 'bottom-right'
            });
            $("#ModalNotificacion").modal("hide")
            $("#btn_notificacion").html("Enviar notificación");
            $("#btn_notificacion").removeAttr("disabled");
        }
    });
}

var elementoGaleria = "";
function galeria(elemento){
    elementoGaleria = elemento;
    $(".mask-semi").css("display","block");
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'medios/galeria',
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
            $(".mask-semi").css("display","none");
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}
var elementoGaleriaMini = "";
function galeriaMini(elemento){
    elementoGaleriaMini = elemento;
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'medios/galeriaMini',
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

function paginadoGaleria(tipo){
    $('#galeria-result').html('<div class="col-12 text-center"><img src="'+base_url+'assets/img/new-loading-recortado.gif"></div>');
    if (tipo=="next") {
        var pagina = $("#pageGallery").val();
        pagina = parseInt(pagina)+1;
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'medios/paginadoGaleria',
            data: "page="+pagina,
            success: function(res){
                $('#galeria-result').html(res);
                $("#pageGallery").val(pagina);
                $("#paginaGaleria").html(pagina);
                if (pagina > 1) {
                    $("#prev-gallery").removeClass("d-none");
                }else{
                    $("#prev-gallery").addClass("d-none");
                }
            },
            error: function(){
                console.log("error de red");
            }
        }); //AJAX
    }
    if (tipo=="prev") {
        var pagina = $("#pageGallery").val();
        pagina = parseInt(pagina)-1;
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'medios/paginadoGaleria',
            data: "page="+pagina,
            success: function(res){
                $('#galeria-result').html(res);
                $("#pageGallery").val(pagina);
                $("#paginaGaleria").html(pagina);
                if (pagina > 1) {
                    $("#prev-gallery").removeClass("d-none");
                }else{
                    $("#prev-gallery").addClass("d-none");
                }
            },
            error: function(){
                console.log("error de red");
            }
        }); //AJAX
    }
}

function crearElemento(element,appened){
  var crear = $(element).html();
  $(appened).append(crear);
}

$('#ModalGallery').on('hidden.bs.modal', function (e) {
    $('#content-gallery').html("");
})
function aggOption(element){
    var elementoC = $(element).parent().parent().children('.opciones');
    var agg = '<div class="col-12 opcion px-0 mb-2">'
        agg+= '    <div class="col-12 bg-secondary mb-2 py-2 text-white top-option">'
        agg+= '        <span>Opción</span>'
        agg+= '    </div>'
        agg+= '    <div class="col-12 fila-option">'
        agg+= '        <div class="row">'
        agg+= '            <div class="col-sm-5 col-12">'
        agg+= '                <input type="text" name="option_text_fila[]" class="option-text form-control" placeholder="Ingresa una opción">'
        agg+= '            </div>'
        agg+= '            <div class="col-sm-3 col-12">'
        agg+= '                <select name="option_tipo_fila[]" class="form-control option_tipo">'
        agg+= '                    <option value="1">Flat Fee</option>'
        agg+= '                </select>'
        agg+= '            </div>'
        agg+= '            <div class="col-sm-3 col-12">'
        agg+= '                <input type="number" name="option_precio_fila[]" class="form-control precio_opcion">'
        agg+= '            </div>'
        agg+= '            <div class="col-sm-1 co-12 text-right">'
        agg+= '                <button onclick="$(this).parent().parent().parent().parent().remove();" class="btn btn-default text-white">X</button>'
        agg+= '            </div>'
        agg+= '        </div>'
        agg+= '    </div>'
        agg+= '</div>'
        elementoC.append(agg);

}

function agregarFila(element){
    filas++;
    var fila = '<div class="w-100 fila border mb-2">';
    fila+=      '<input type="hidden" name="addons_id[]">';
    fila+=      '<div class="card col-12 top-fila collapsed" data-toggle="collapse" data-target="#collapse-fila-'+filas+'" aria-expanded="false" aria-controls="collapseExample">';
    fila+=      '    <div class="row">';
    fila+=      '        <div class="col-6">';
    fila+=      '            <p class="mb-0 ml-4 py-2">Fila</p>';
    fila+=      '        </div>';
    fila+=      '        <div class="col-6 text-right">';
    fila+=      '            <button type="button" onclick="event.preventDefault();$(this).parent().parent().parent().parent().remove();" class="btn border mr-2 btn-classic">Remove</button>';
    fila+=      '        </div>';
    fila+=      '    </div>';
    fila+=      '</div>';
    fila+=      '<div class="collapse" id="collapse-fila-'+filas+'" style="">';
    fila+=      '    <div class="col-12">';
    fila+=      '        <div class="form-group">';
    fila+=      '            <label class="w-100">';
    fila+=      '                Type';
    fila+=      '                <select name="addons_tipo[]" class="form-control">';
    fila+=      '                    <option value="multiple">Multiple Choice</option>';
    fila+=      '                    <option value="checkboxes">Checkboxes</option>';
    fila+=      '                    <option value="short_text">Texto corto</option>';
    fila+=      '                    <option value="long_text">Texto Largo</option>';
    fila+=      '                    <option value="dropdowns">Dropdowns</option>';
    fila+=      '                    <option value="radio_btn">Radio Buttons</option>';
    fila+=      '                </select>';
    fila+=      '            </label>';
    fila+=      '        </div>';
    fila+=      '        <div class="form-group">';
    fila+=      '            <label class="w-100">';
    fila+=      '                Titulo:';
    fila+=      '                <input type="text" class="form-control" name="addons_titulo[]">';
    fila+=      '            </label>';
    fila+=      '        </div>';
    fila+=      '        <div class="form-group">';
    fila+=      '            <label class="w-100">';
    fila+=      '                Formato del titulo:';
    fila+=      '                <select name="addons_tipo_titulo[]" class="form-control">';
    fila+=      '                    <option value="label">Label</option>';
    fila+=      '                    <option value="heading">Heading</option>';
    fila+=      '                    <option value="hide">Hide</option>';
    fila+=      '                </select>';
    fila+=      '            </label>';
    fila+=      '        </div>';
    fila+=      '        <div class="form-group">';
    fila+=      '            <input value="1" type="checkbox" name="addons_agg_desc[]">';
    fila+=      '            Agregar descripción';
    fila+=      '            <textarea class="form-control" name="addons_descripcion[]" rows="4"></textarea>';
    fila+=      '        </div>';
    fila+=      '        <div class="form-group">';
    fila+=      '            <label class="w-100">';
    fila+=      '                <input value="1" type="checkbox" name="addons_requerido[]">';
    fila+=      '                Fila requerida';
    fila+=      '            </label>';
    fila+=      '        </div>';
    fila+=      '    </div>';
    fila+=      '    <div class="col-12 opciones">';
    fila+=      '        <div class="col-12 opcion px-0 mb-2">';
    fila+=      '            <div class="col-12 bg-secondary mb-2 py-2 text-white top-option">';
    fila+=      '                <span>Opción</span>';
    fila+=      '            </div>';
    fila+=      '            <div class="col-12 fila-option">';
    fila+=      '                <div class="row">';
    fila+=      '                    <div class="col-sm-5 col-12">';
    fila+=      '                        <input type="text" name="option_text_fila[]" class="option-text form-control" placeholder="Ingresa una opción">';
    fila+=      '                    </div>';
    fila+=      '                    <div class="col-sm-3 col-12">';
    fila+=      '                        <select name="option_tipo_fila[]" class="form-control option_tipo">';
    fila+=      '                            <option value="1">Flat Fee</option>';
    fila+=      '                        </select>';
    fila+=      '                    </div>';
    fila+=      '                    <div class="col-sm-3 col-12">';
    fila+=      '                        <input type="number" name="option_precio_fila[]" class="form-control precio_opcion">';
    fila+=      '                    </div>';
    fila+=      '                    <div class="col-sm-1 co-12 text-right">';
    fila+=      '                        <button type="button" onclick="$(this).parent().parent().parent().parent().remove();" class="btn btn-default text-white">X</button>';
    fila+=      '                    </div>';
    fila+=      '                </div>';
    fila+=      '            </div>';
    fila+=      '        </div>';
    fila+=      '    </div>';
    fila+=      '    <input type="hidden" class="addons_opciones" name="addons_opciones[]" value="">';
    fila+=      '    <div class="col-12 text-right mb-3 mr-2">';
    fila+=      '        <button type="button" onclick="aggOption(this);" class="btn btn-default">Agregar opcion</button>';
    fila+=      '    </div>     ';
    fila+=      '</div>';
    fila+=      '</div>';
    $(element).append(fila);
}

$('#check_otra_direccion').change(function(){
    if ($('#check_otra_direccion').prop('checked') ) {
        $("#pedidos_direccion_envio_conf").val(1);
        $("#div_otra_direccion").removeClass("d-none");
        $("#div_otra_direccion .required").attr("required","required");
    }else{
        $("#pedidos_direccion_envio_conf").val(0);
        $("#div_otra_direccion").addClass("d-none");
        $("#div_otra_direccion .required").removeAttr("required");
    }
});



$(window).on('load', function() {
    $(".mask").css("display","none");
})



