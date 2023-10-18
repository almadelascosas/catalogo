function show_hide_element(ele1,ele2,ele3){
    jQuery(ele1).css("display","none");
    jQuery(ele2).css("display","block");
    jQuery(".btn").removeClass("active");
    jQuery(ele3).addClass("active");
  }

	function solicitarRetiro(){
		var valor_solicitado = jQuery("#valor-retiro").val();
		valor_solicitado = valor_solicitado.replace(/\./g,"");
		valor_solicitado = valor_solicitado.replace(",",".");
		if (valor_solicitado > cantidad_maxima || valor_solicitado <= 0) {
			alert("El monto no debe exceder el saldo disponible, ni debe ser menor o igual a 0");
			jQuery("#valor-retiro").val("");
			jQuery("#valor-retiro").focus();
		}else {
			show_hide_element('#button-1','#button-2','');
			jQuery.ajax({
				type: 'POST',
				dataType: "html",
				url: url_solicitar_retiro,
				data: "idusu="+idusu+"&valor_solicitado="+valor_solicitado,
				success: function(res){
					show_hide_element('#button-2','#button-3','');
					setTimeout(function(){
						window.location.reload();
					},1000);
				}
			});
		}
	}
	function ordenarTabla(){
		var html = jQuery("#tabla-saldos").html();
		//console.log(html);
		var htmlAr1 = html.split("<tr>");
		var fechasAsig = [];
		var htmlAsig = "";
		for (var i = 0; i < htmlAr1.length; i++) {
			var htmlAr2 = htmlAr1[i].split("<td>");
			if (htmlAr2[1]!=undefined && htmlAr2[1]!="") {
				var fecha = htmlAr2[1].replace("</td>","");
				fechasAsig[i] = new Date(fecha);
			}
		}
		fechasAsig.sort((a, b) => b - a);
		for (var i = 0; i < fechasAsig.length; i++) {
			for (var is = 0; is < htmlAr1.length; is++) {
				var htmlAr2 = htmlAr1[is].split("<td>");
				if (htmlAr2[1]!=undefined && htmlAr2[1]!="") {
					var fecha = htmlAr2[1].replace("</td>","");
				}
				if (Date.parse(new Date(fecha)) == Date.parse(fechasAsig[i])) {
					htmlAsig += "<tr>"+htmlAr1[is];
				}
			}
		}
		jQuery("#tabla-saldos").html(htmlAsig);
	}
	jQuery("#valor-retiro").on({
		"focus": function (event) {
			jQuery(event.target).select();
		},
		"keyup": function (event) {
			jQuery(event.target).val(function (index, value ) {
				return value.replace(/\D/g, "")
				.replace(/([0-9])([0-9]{2})$/, '$1,$2')
				.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
			});
		}
	});
    //jQuery(window).on("load", ordenarTabla);
    
    function aprobarRetiro(nombre,valor,balance_id){
        $("#pagar_a_p").html(nombre);
        $("#valor_solicitado_p").html(valor);
        $("#balance_id").val(balance_id);
        $("#ModalRetiro").modal("show");
    }

    function confirmarRetiro(){
        var balance_id = $("#balance_id").val();
        var nota = $("#balance_pagos_nota").val();
        jQuery.ajax({
            type: 'POST',
            dataType: "html",
            url: url_confirmar_retiro,
            data: "balance_id="+balance_id+"&nota="+nota,
            success: function(res){
                if (res=="success") {
                    alert("El retiro ha sido confirmado con éxito");
                }else{
                    alert("Ha ocurrido un error.");
                }
                window.location.reload();
            }
        });
    }