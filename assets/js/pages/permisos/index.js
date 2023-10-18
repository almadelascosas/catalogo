function leerPadres(){
    var agg = "";
    agg+='<option value="0">Ninguno</option>';
    $("#drag-elements .rounded").each(function(){
        var valor = $("[name='menu_dash_id[]']", this).val();
        var texto = $("[name='menu_dash_texto[]']", this).val();
        var padre = $("[name='menu_dash_padre[]']", this).val();
        if (padre==0) {
            agg+='<option value="'+valor+'">'+texto+'</option>';
        }
    });
    $("#menu_dash_padre_agg").html(agg);
}
function agregarMenu(){
    suma_padre++;
    var texto = $("#menu_dash_texto_agg").val();
    var enlace = $("#menu_dash_enlace_agg").val();
    var padre = $("#menu_dash_padre_agg").val();
    var permisos = "";
    var permiso = $("[name='menu_dash_permiso_agg']").val();
    for (let index = 0; index < permiso.length; index++) {
        if (index==0) {
            permisos+=permiso[index];
        }else{
            permisos+=","+permiso[index];
        }
    }
    if (enlace=="" || texto=="") {
        alert("Debes completar los campos obligatorios (Texto y Enlace)");
    }else{
        if (padre!=0) {
            $("#item-"+padre).append('<div class="col-11 offset-1 p-2 mt-2 rounded border"><a target="_blank" href="'+enlace+'">'+texto+'</a><span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x<span></span></span><input value="'+texto+'" type="hidden" name="menu_dash_texto[]"><input value="'+enlace+'" type="hidden" name="menu_dash_enlace[]"><input value="'+padre+'" type="hidden" name="menu_dash_padre[]"><input value="'+suma_padre+'" type="hidden" name="menu_dash_id[]"><input type="hidden" name="menu_dash_permiso[]" value="'+permisos+'"></div>');
        }else{
            $("#drag-elements").append('<div id="item-'+suma_padre+'" class="rounded border"><a target="_blank" href="'+enlace+'">'+texto+'</a><span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x<span></span></span><input value="'+texto+'" type="hidden" name="menu_dash_texto[]"><input value="'+enlace+'" type="hidden" name="menu_dash_enlace[]"><input value="'+padre+'" type="hidden" name="menu_dash_padre[]"><input value="'+suma_padre+'" type="hidden" name="menu_dash_id[]"><input type="hidden" name="menu_dash_permiso[]" value="'+permisos+'"></div>');
        }
        $("#menu_dash_texto_agg").val("");
        $("#menu_dash_enlace_agg").val("");
        leerPadres();
    }
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
  