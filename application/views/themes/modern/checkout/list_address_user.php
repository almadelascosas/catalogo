<main class="col-12 cuerpo float-left mb-5">
    <div class="d-none d-sm-none d-md-block">
        <div class="row">
            <div class="offset-md-3 col-md-4 col-sm-12 pt-5 mt-2">
                <br>
                <h3>Selecciona una dirección de envío</h3>
            </div>
        </div>
    </div>
    <div class="d-block d-sm-block d-md-none">
        <div class="row">
            <div class="offset-md-1 col-12"><br></div>
        </div>
    </div>
    <form action="<?=base_url('checkout/dirsel')?>" method="post" id="formListAddress">
        <div class="row">
            <div class="offset-md-2 col-md-8 col-sm-12 pt-5 mt-2">
                <div class="d-block d-sm-block d-md-none">
                    <div class="card-metodo-pago col-12">
                        <div class="row">
                            <!-- <div class="col-12 div-volver height-40">
                                <a href="<?=base_url('checkout')?>" class="btn-yellow-alma-simple">Volver al carrito</a>
                            </div> -->
                            <div class="offset-md-1 col-12">
                                <h5>Selecciona una dirección de envío</h5>  
                            </div>                     
                        </div>
                    </div>
                </div>
                
                <?php foreach($lista_direcciones as $key => $dir): ?>
                <div class="card-metodo-pago col-12">
                    <div class="row">
                        <div class="offset-md-1 col-md-1 col-sm-12 text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdDir" id="rdDir" value="<?=$dir['id_dir']?>" <?=(intval($dir['prede'])===1)?'checked':''?> required>
                                <div class="d-block d-sm-block d-md-none"><br></div>
                            </div>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <b><?=$dir['nombre']?></b><br>
                            <?=$dir['nombre']?><br>
                            <?=$dir['direccion']?><br>
                            <?=getDepartamentoById($dir['id_dpto'])?>, <?=getMunicipioById($dir['id_muni'])?>
                        </div>
                    </div>  
                </div>
                <?php endforeach; ?>
                
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4 col-12 text-center height-40">
                            <a href="javascript:{}" onclick="document.getElementById('formListAddress').submit(); return false;" class="btn-green-alma-simple">Continuar</a>
                        </div>
                        <div class="col-md-4 col-12 text-center height-40">
                            <a href="<?=base_url('checkout/nueva-direccion')?>" class="btn-green-alma-simple">Crear nueva</a>
                        </div>
                        <div class="col-md-4 col-12 text-center height-40">
                            <a href="<?=base_url('checkout')?>" class="btn-yellow-alma-simple">Volver al Carrito</a>
                        </div>
                    </div>
                </div>
            
            </div>
        
        </div>
    </form>
</main>