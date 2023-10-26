<style type="text/css">
    .itemcata{
        position: relative;
        border-radius: 30px;
        border: 2px solid #D4D4D4;
        /*padding: 10px 0;*/
        margin-bottom: 40px;
    }
    .imgcatalogo{
        border-top-left-radius: 30px;
        border-bottom-left-radius:  30px;
        overflow:hidden;
        margin-left: -16px;
        max-width: 145px;
        height: 190px;
    }
    .imgcatalogo img{
        width: 100%;
        height: 100%;
    }
</style>
<div class="content">
    <div class="offset-md-1 col-md-10">
        <div class="heading">
            <div class="col-12 mt-5">
                <h3>Catalogo </h3>
            </div>
        </div>
        
    </div>
    <div class="offset-md-1 col-md-10">
        <div class="card">
            <div class="row">
                <div class="offset-md-1 col-10 text-right">
                    <br>
                    <a href="<?=base_url('catalogo/agregar')?>" class="btn btn-success"><h5>+ Agregar nuevo</h5></a>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-12">&nbsp;</div>
            </div>
            <div class="row">
                <?php 
                if(count($catalogos)>0){
                    foreach($catalogos as $key => $cat):
                        $imgPortada='assets/uploads/catalogo/'.$cat['catalogo_id'].'.png';
                        if(!file_exists($imgPortada)) $imgPortada='assets/img/vistaPdf.fw.png';
                ?>
                <div class="offset-md-1 col-md-10 itemcata">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="imgcatalogo">
                                <img src="<?=$imgPortada.'?'.rand()?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4><?=$cat['catalogo_nombre']?></h4>
                            <?php if($cat['catalogo_fecha_inicio']) print 'Desde '.$cat['catalogo_fecha_inicio'].'<br>'; ?>
                            <?php if($cat['catalogo_fecha_final']) print 'Hasta '.$cat['catalogo_fecha_final']; ?>
                        </div>
                        <div class="col-md-2 text-center">
                            <a href="javascript:void(0)" class="btn btn-success">Vigente</a>
                        </div>
                        <div class="col-md-2 text-center">
                            <a href="<?=base_url('catalogo/descargar/'.$cat['catalogo_id'].'/'.limpiarUri($cat['catalogo_nombre']))?>" target=""><i class="fas fa-download fa-2x"></i></a>
                        </div>
                        <div class="col-md-2 text-center">
                            <a href="<?=base_url('catalogo/editar/'.$cat['catalogo_id'].'/'.limpiarUri($cat['catalogo_nombre']))?>"><i class="fas fa-copy fa-2x"></i></a>
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


<div class="modal fade" id="ModalDel" tabindex="-1" role="dialog" aria-labelledby="ModalDelLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <h5 class=" text-center">Estás seguro que deseas eliminar este producto?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
