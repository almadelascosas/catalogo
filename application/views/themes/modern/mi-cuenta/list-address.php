<main class="cuerpo col-12 float-left">
    <div class="row">
        <div class="col-12 mb-5"></div>

        <div class="offset-md-1 col-md-3 offset-sm-1 col-sm-11">
            <div class="row">
                <div class="col-md-1 col-12"></div>
                <?php $this->view('themes/modern/mi-cuenta/menu'); ?>
            </div>
        </div>
        
        <div class="col-md-7 col-11 miinfo">
            <div class="d-block d-sm-block d-md-none"><div class="col-sm-12">&nbsp;</div></div>
            <div class="row">
                <div class="col-md-12 col-12">&nbsp;</div>
            </div>
            <div class="row ">
                <div class="offset-sm-1 col-10">
                    <div class="row">
                        <div class="col-sm-12 col-md-6"><h3 class="rale">Mis Direcciones</h3></div>
                        <div class="col-sm-12 offset-md-1 col-md-5"> <a href="<?=base_url('mi-cuenta/edit-address')?>" class="btn btn-green-alma w-100">+ Agregar dirección</a></div>
                        <div class="col-12"><hr></div>
                        <div class="col-12">&nbsp;</div>
                        
                            <?php 
                            if(count($address)>0){
                                foreach($address as $key => $addr): ?>
                            <div class="col-md-6 col-sm-12">
                                <div class="item-dir">
                                    <div class="row">
                                        <div class="col-8"><a href="<?=base_url('mi-cuenta/edit-address/'.$addr['id_dir'].'-'.limpiarUri($addr['nombre']))?>" class="btn-outline-primary">Editar</a></div>
                                        <div class="col-4 text-right"><a href="<?=base_url('mi-cuenta/delete-address/'.$addr['id_dir'])?>" onclick="return confirm('Estas seguro de eliminar esta dirección?')" class="btn-outline-danger">Eliminar</a></div>
                                        <div class="col-12"><hr></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-10">
                                            <b><?=$addr['nombre']?></b>
                                            <br>
                                            <?=$addr['direccion']?> <br>
                                            <?=$addr['barrio']?> <br>
                                            <?=getDepartamentoById($addr['id_dpto'])?>, <?=getMunicipioById($addr['id_muni'])?>
                                        </div>
                                        <div class="col-2">
                                            <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12"><hr></div>
                                        <?php if(intval($addr['prede'])===0){ ?>
                                        <div class="col-12 text-right"><a href="<?=base_url('mi-cuenta/direccion-predeterminada/'.$addr['id_dir'].'/'.limpiarUri($addr['nombre']))?>" class="btn-outline-success">Hacer predeterminada</a></div>
                                        <?php }else{ ?>
                                        <div class="col-12">&nbsp;</div>
                                    <?php } ?>
                                    </div>
                                    
                                </div>
                            </div>
                            <?php 
                                endforeach;
                            } 
                            ?>
                        

                    </div>
                </div>

                
            </div>
        </div>

    </div>
</main>