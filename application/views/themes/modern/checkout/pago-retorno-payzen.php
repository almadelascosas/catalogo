<style type="text/css">
.altura-pago{
    height: 130px;
}
@media screen and (max-width: 650px) {
    .altura-pago{
        height: 160px;
    }
    .background-pago{
        background-image: url('<?=base_url($parametro['background'])?>');
        background-size: 100% auto;
    }
    
}
</style>
<main class="col-12 cuerpo float-left mb-5">
	<div class="row background-pago">
		<div class="col-12 altura-pago">&nbsp;</div>

        <div class="col-12 text-center">
        	<img src="<?=base_url($parametro['icono'])?>" style="max-width: 160px;">  
        </div>
        <div class="col-12 text-center normal">
        	<h5 class="text-center mt-2"><b><?=$parametro['titulo']?></b></h5>
        </div>
        <div class="col-2"></div>
        <div class="col-8 text-center normal">
            <?=$parametro['descrip']?>
        </div>        
        <div class="col-12" style="height:30px">&nbsp;</div>
        
        <?php if($tipo==='exitoso'){ ?>
        <div class="col-md-5 col-2">&nbsp;</div>
        <div class="col-md-2 col-8 checkout-boton-next text-center py-4">
            <button id="btn-next-pedido" type="button" class="btn btn-green-alma w-100 text-white uppercase">Volver al inicio</button>
        </div>
        <?php }else if($tipo==='error' || $tipo==='rechazado' || $tipo==='cancelado'){ ?>
        <div class="col-md-3 col-1">&nbsp;</div>
        <div class="col-md-6 col-10 checkout-boton-next text-center py-4">
            <button id="btn-next-pedido" type="button" class="btn btn-error-alma w-100 text-white uppercase">Contactar por WhatsApp</button>
        </div>
        <div class="col-md-3 col-1">&nbsp;</div>

        <div class="col-md-5 col-1">&nbsp;</div>
        <div class="col-md-2 col-10 checkout-boton-next text-center py-4">
            <button id="btn-next-pedido" type="button" class="btn btn-neutro-alma w-100 uppercase">Volver al inicio</button>
        </div>
        <?php } ?>
    </div>
</main>