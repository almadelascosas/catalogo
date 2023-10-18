$(document).ready(function(){
    $('[name="pedidos_metodo_pago"]').change(function(){
        $(".card-metodo-pago").removeClass("active");
        $(this).parent("label").parent(".card-metodo-pago").addClass("active");
    });

    $(".required").change(function(){
        $(this).parent(".form-group").children(".text-danger").remove();
    });
    $(".main-toggle").click(function (){
        menu("abrir");
    });
    $(".close-menu").click(function(){
        menu("cerrar");
    });

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

    $(document).on("scroll", function(){
        
        var desplazamientoActual = $(document).scrollTop();
        
        if(desplazamientoActual > 50){
            $(".div-buscador").addClass("oculto");
            $(".search-li").removeClass("d-none");
        }else{
            $(".div-buscador").removeClass("oculto");
            $(".search-li").addClass("d-none");
        }
        
        var altura = $("header").outerHeight();
        $("body").css("padding-top",altura);
    });

    $("#btn-whatsapp-2").click(function(){
        var cantidad = $(this).attr("data-service_number");
        var id = $(this).attr("data-service_client_id");
        $.ajax({
            type: 'POST',
            dataType: "JSON",
            url: base_url+'home/suma_boton_whatsapp/',
            data: "whatsapp_plugin_id="+id+"&whatsapp_plugin_cantidad="+cantidad,
            success: function(res){
                if (res.result==1) {
                    console.log(res.mensaje);
                }else{
                    console.log(res.mensaje);
                }
            },
            error: function(){
                console.log("error de red");
            }
        }); //AJAX
    });

    var anchos = $(".products .image").width();
    $(".products .image").css("height",anchos);
    
    var anchos = $(".scroll-mini .miniatura").width();
    $(".scroll-mini .miniatura").css("height",anchos);

    var anchos = $(".image-principal").width();
    $(".image-principal").css("height",anchos);
    
    var anchos = $(".categorias .image").width();
    $(".categorias .image").css("height",anchos);
});

function cambiotxt(item1,item2){
    var valor = $(item1).val();
    $(item2).html(valor);
}

function number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function aumentarCargo(valor){
    var valorTotal = $('#precio-producto').val();
    valorTotal = parseFloat(valorTotal);
    var addons = "";
    var count_addons = 0;
    $("#caja-addons .addons").each(function(){
        count_addons++;
        var addons_id = $(this).children('[name="addons_id[]"]').val();
        var addons_tipo = $(this).children('[name="addons_tipo[]"]').val();
        var addons_display = $(this).children('[name="addons_display[]"]').val();
        
        var objeto_padre = $(this).children('[name="addons_respuesta_pedido[]"]')[0];

        if (count_addons==1) {
            addons = addons_id+"/,/"+addons_tipo+"/,/"+addons_display;
        }else{
            addons = addons+"],["+addons_id+"/,/"+addons_tipo+"/,/"+addons_display;
        }

        if ($(objeto_padre).hasClass('addons-texto')) {
            var valor=$(objeto_padre).val();
            addons = addons+"/,/"+valor;
        }

        var valorAgregado = "";
        $(this).children('label').each(function(){
            var seleccionado = 0;
            var seleccion = $(this).children('[name="addons_respuesta_pedido[]"]').val();
            var objeto = $(this).children('[name="addons_respuesta_pedido[]"]')[0];
            var objeto2 = $(this).children('[name="addons_respuesta_pedido[]"]');
            var valorExtra = $(this).children('[name="addons_respuesta_cargo[]"]').val();
            if($(this).children('[name="addons_respuesta_pedido[]"]').prop('checked')){
                valorAgregado = valorAgregado+"/./"+seleccion;
                if(valorExtra!=undefined && valorExtra!="0" && valorExtra!=0 && valorExtra!=""){
                    valorTotal = valorTotal+parseFloat(valorExtra);
                }
            }
            
            if ($(objeto).hasClass('select-addons')) {
                var valor = $(objeto).val();
                valor = valor.split('/,/');
                valorAgregado = valor;
                if (valor[3]!=undefined && valor[3]!="" && valor[3]!=0) {
                    valorExtra=valor[3];
                    valorTotal = valorTotal+parseFloat(valorExtra);    
                }
            }

        });

        if (valorAgregado!="") {
            addons = addons+"/,/"+valorAgregado;
        }

    });
    
    $("#h4-valor").html("$ "+number_format(valorTotal,0,',','.'));
    $('#productos_precio').val(valorTotal);
    $('#addons-producto').val(addons);
}

function menu(act){
    if (act=="abrir") {
        $(".menu").addClass("active");
    }
    if (act=="cerrar") {
        $(".menu").removeClass("active");
    }
}

$( window ).resize(function() {
    var anchos = $(".products .image").width();
    $(".products .image").css("height",anchos);
    
    var anchos = $(".scroll-mini .miniatura").width();
    $(".scroll-mini .miniatura").css("height",anchos);

    var anchos = $(".categorias .image").width();
    $(".categorias .image").css("height",anchos);
    
    var anchos = $(".image-principal").width();
    $(".image-principal").css("height",anchos);
    
});   

$( window ).resize(function() {
    var altura = $("header").outerHeight();
    $("body").css("padding-top",altura);
});   

function abrirfiltros(){
    $(".filtros-in-mb").addClass("active");
}
function cerrarfiltros(){
    $(".filtros-in-mb").removeClass("active");
}
function abrirCart(){
    //event.preventDefault();
    $(".cart").addClass("active");
}
function soundCart(){
    $("#cart_add_sound")[0].play();
}
function cerrarCart(){
    event.preventDefault();
    $(".cart").removeClass("active");
}
function sumar(elem){
    var valor = $(elem).val();
    valor = parseFloat(valor);
    var maximo = $(elem).attr("max");
    maximo = parseFloat(maximo);
    if (valor <= maximo) {
    }
    valor = valor+1;
    $(elem).val(valor);
}
function restar(elem){
    var valor = $(elem).val();
    valor = parseFloat(valor);
    var maximo = $(elem).attr("min");
    maximo = parseFloat(maximo);
    if (valor >= maximo && valor > 1) {
        valor = valor-1;
        $(elem).val(valor);
    }
}

function cargarMas(){
    event.preventDefault();
    var page = $("#page").val();
    page = parseFloat(page);
    page = page+1;
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'tienda/mostrarMas',
        data: $("#form-filtros").serialize()+"&page="+page,
        success: function(res){
            $('#loop-products').append(res);
            var anchos = $(".products .image").width();
            $(".products .image").css("height",anchos);
            $("#page").val(page);
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}

function addCart(idprod,cantidad){
    var cantidad = $(cantidad).val();
    cantidad = parseFloat(cantidad);
    if (cantidad==undefined) {
        cantidad = 1;
    }
    if (cantidad!=undefined && cantidad > 0) {
        $(".btn-addcart").attr("disabled","disabled");
        $(".btn-addcart").html("<img style='height:30px;' src='"+base_url+"assets/img/loading-buffering.gif'>");
        aumentarCargo(0);
        var precio = $("#productos_precio").val();
        var ubicacion = $("#productos_ubicaciones_envio").val();
        var envio_nacional = $("#productos_envio_nacional").val();
        var addons = $("#addons-producto").val();
        var parametros = $("#parametros").serialize();
        if (parametros==undefined) {
            parametros = "";
        }
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'tienda/addcart/'+idprod,
            data: "parametros="+parametros+"&cantidad="+cantidad+"&precio="+precio+"&addons="+addons+"&ubicacion="+ubicacion+"&envio_nacional="+envio_nacional,
            success: function(res){
                $('#content-cart').html(res);
                abrirCart();
                soundCart();
                $(".btn-addcart").removeAttr("disabled");
                $(".btn-addcart").html("Comprar");
            },
            error: function(){
                console.log("error de red");
            }
        }); //AJAX
    }else{
        alert("La cantidad debe ser mayor a 0.");
    }
}

function delitem(elem,precio){
    event.preventDefault();
    
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'tienda/delitem/'+elem+'/'+precio,
        data: "idprod="+elem+"&precio="+precio,
        success: function(res){
            $('#content-cart').html(res);
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}

function ordernarPor(){
    var valor = $("#orderby-m").val();
    $("#form-filtros #orderby").val(valor);
    setTimeout(function() {
        $("#form-filtros").submit();
    },200);
}




$(window).on('load', function() {
    setTimeout(function(){
        $(".products .image img").removeClass("d-none");
        $(".products .image").addClass("loaded");
        $("#loop-products .col-6").each(function(){
            var alto = $(this).children(".products").children("a").children(".image").children("img").height();
            var ancho = $(this).children(".products").children("a").children(".image").children("img").width();
            
            if (alto > ancho) {
                $(this).children(".products").children("a").children(".image").children("img").css("width","102%");
                $(this).children(".products").children("a").children(".image").children("img").css("height","auto");
            }else{
                $(this).children(".products").children("a").children(".image").children("img").css("height","102%");
                $(this).children(".products").children("a").children(".image").children("img").css("width","auto");
            }
        });
    },100);
    var altura = $("header").outerHeight();
    $("body").css("padding-top",altura);
});


function selectInfo(item1,item2){
    $(".selectores-single span").removeClass("tag-descripcion");
    $(item1).addClass("tag-descripcion");
    $(".seleccionados-pr").addClass("d-none");
    $(item2).removeClass("d-none");
}

function cargarMunicipios(id_departamento, item, id_municipio){
    $(".btn_ubi").addClass("d-none");
    if (id_departamento==undefined) var id_departamento = $("#departamento_session").val();

    var url = base_url+'home/obtenerMunicipios/'+id_departamento;
    if (id_municipio!=undefined) {
        var url = base_url+'home/obtenerMunicipios/'+id_departamento+"/"+id_municipio;
    }
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: url,
        data: "conf=si",
        success: function(res){
            if (item==undefined) {
                $('#municipio_session').html(res);
            }else{
                $(item).html(res);
            }
            $(".btn_ubi").removeClass("d-none");
        },
        error: function(){
            console.log("error de red");
        }
    }); //AJAX
}

function obtenerMunicipios(item_dep, item_mun, val_mun){
    var id_departamento = $.trim($(item_dep).val());
    if (id_departamento!=="" && id_departamento!==undefined) {
        var url = base_url+'home/obtenerMunicipios/'+id_departamento;
        if (val_mun!==undefined)  var url = base_url+'home/obtenerMunicipios/'+id_departamento+"/"+val_mun;
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: url,
            data: "conf=si",
            success: function(res){
                $(item_mun).html(res);
            },
            error: function(){
                console.log("error de red");
            }
        });
    }else{

    }
}

/*
if (parseInt(cargaMun) === 1)  cargarMunicipios();
if (parseInt(checkMun) === 1)  obtenerMunicipios(itemDep,itemMun,valorMun);
*/

function ubicacionNueva(departamento,municipio){

    if (departamento==undefined && municipio==undefined) {
        var departamento_session = $("#departamento_session").val();
        var municipio_session = $("#municipio_session").val();
    }else{
        var departamento_session = $(departamento).val();
        var municipio_session = $(municipio).val();
    }
    var envio_conf = $("#pedidos_direccion_envio_conf").val();
    if (departamento=="#pedidos_departamento" && envio_conf==1) {
        
    }else{
        if (departamento_session!="" && municipio_session!="") {
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                url: base_url+'home/nuevaUbicacion/'+departamento_session+'/'+municipio_session,
                data: "conf=si",
                success: function(res){
                    $(".modal").modal("hide");
                    if (res.result==1) {
                        $('#ubicacion-mobile').html('<span class="icon-location"></span> Enviar a '+res.nombre_departamento+', '+res.nombre_municipio+'');
                        $('#ubicacion-mobile').addClass('one-line-ellipsis');
                        $('#ubicacion-desktop').html('<span class="icon-location"></span> '+res.nombre_departamento+', '+res.nombre_municipio+'');
                        $('#ubicacion-desktop').addClass('one-line-ellipsis');
                    }else{
                        console.log(res.mensaje);
                    }
                    location.reload();
                },
                error: function(){
                    $(".modal").modal("hide");
                    console.log("error de red");
                }
            }); //AJAX
        }else{
            alert("Debe llenar todos los campos requeridos");
        }
    }

}

function vendorCat(categoria_id){
    event.preventDefault();
    $(".menu-vendedores-unicos-li").removeClass("active");
    $('[href="#categoria-por-vendedor-'+categoria_id+'"]').parent("li").addClass("active");
    $(".contenedor-vendedores").removeClass("d-flex");
    $(".contenedor-vendedores").addClass("d-none");
    if($("#categoria-por-vendedor-"+categoria_id).length){
        $("#categoria-por-vendedor-"+categoria_id).removeClass("d-none");
        $("#categoria-por-vendedor-"+categoria_id).addClass("d-flex");
    }else{
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'home/obtenerVendorCat/'+categoria_id,
            data: "conf=si",
            success: function(res){
                $('#vendedores-por-categoria').append(res);
                $("#categoria-por-vendedor-"+categoria_id).removeClass("d-none");
                $("#categoria-por-vendedor-"+categoria_id).addClass("d-flex");
            },
            error: function(){
                console.log("error de red");
                $("#categoria-por-vendedor-"+categoria_id).addClass("d-flex");
                $("#categoria-por-vendedor-"+categoria_id).removeClass("d-none");
            }
        }); //AJAX    
    }
}

function modalUbicacion(titulo,parrafo){
    event.preventDefault();
    $("#titulo_ubi").html(titulo);
    $("#parrafo_ubi").html(parrafo);
    $("#modalUbicacion").modal("show");
}

function modalCambioUbi(){
    event.preventDefault();
    
    var departamento_session = $("#departamento_session").val();
    var municipio_session = $("#municipio_session").val();
    
    if (departamento_session!="" && municipio_session!="") {
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'tienda/productosReqUbi/',
            data: "",
            success: function(res){
                $('#prodReqUbi').html(res);
                $(".modal").modal("hide");
                $(".pop-cambio-ubi").addClass("active");
            },
            error: function(){
                console.log("error de red");
            }
        });
    }else{
        alert("Debe llenar todos los campos requeridos");        
    }


}
function closeCambioUbi(){
    $(".pop-cambio-ubi").removeClass("active");
}
function vacioUbi(){
    $.ajax({
        type: 'POST',
        dataType: "HTML",
        url: base_url+'tienda/vaciarCarrito/',
        data: "",
        success: function(res){
            $('#content-cart').html(res);
            ubicacionNueva();
            closeCambioUbi();
        },
        error: function(){
            console.log("error de red");
        }
    });
}

function loadedbutton(boton){
    $(boton).attr("disabled","disabled");
    $(boton).html("<img style='height:40px;' src='"+base_url+"assets/img/loading-buffering.gif'>");
}


function ubicacionCheckout(departamento,municipio){
    var envio_conf = $("#pedidos_direccion_envio_conf").val();
    
    if (departamento=="#pedidos_departamento_envio" || (departamento=="#pedidos_departamento" && envio_conf==0)) {
        var departamento = $(departamento).val();
        var municipio = $(municipio).val();
        $('#resume-cart').html("<img style='height:40px;' src='"+base_url+"assets/img/loading-buffering.gif'>");
        $.ajax({
            type: 'POST',
            dataType: "HTML",
            url: base_url+'checkout/recalculateCheckout/'+departamento+"/"+municipio,
            data: "",
            success: function(res){
                $('#resume-cart').html(res);
            },
            error: function(){
                console.log("error de red");
            }
        });       
    }
}


function nextCheck(){
    var seguir = 1;
    var posicion = $("body").offset().top;
    $(".form-checkout .form-group").each(function(){
        if($(this).children(".required")[0].value==""){
            seguir=0;
            $(this).append("<p class='text-danger text-sm'>Este campo es requerido *</p>");
        }else{
            $(this).append("");
        }
    });
    if (seguir==1) {
        
        $(".btn-back-check").removeClass("d-none");
        $(".btn-back-check").addClass("d-inline-block");
        
        $("#checkout-mod-envio").addClass("d-none");
        $("#checkout-mod-pago").removeClass("d-none");
        $(".checkout-boton-next").addClass("d-none");
        $(".checkout-boton-submit").removeClass("d-none");
        $(".linea-de-tiempo").addClass("next");
    }
    $("html, body").animate({
        scrollTop: posicion
    }, 500);
}


function prevCheck(){
    $(".btn-back-check").addClass("d-none");
    $(".btn-back-check").removeClass("d-inline-block");
    
    $("#checkout-mod-envio").removeClass("d-none");
    $("#checkout-mod-pago").addClass("d-none");
    $(".checkout-boton-next").removeClass("d-none");
    $(".checkout-boton-submit").addClass("d-none");
    $(".linea-de-tiempo").removeClass("next");
}