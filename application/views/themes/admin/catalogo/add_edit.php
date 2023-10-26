<style type="text/css">
    .itemcata{
        position: relative;
        border-radius: 15px;
        border: 2px solid #D4D4D4;
        /*padding: 10px 0;*/
        margin-bottom: 40px;
    }
</style>
<div class="content">
    <div class="offset-md-1 col-md-10">
        <div class="heading">
            <div class="col-12 mt-5">
                <h3>Catalogo <?=(!isset($datoCatalogo))?'Nuevo':'Editar'?></h3>
            </div>
        </div>
        
    </div>
    <div class="offset-md-1 col-md-10">
        <div class="card">
            <form action="<?=base_url($action)?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-md-5 col-12 form-group">
                        <label>Documento PDF</label>
                        <input type="file" class="form-control" name="filCat" <?=(!isset($datoCatalogo))?'required':''?>>
                    </div>
                    <div class="col-md-5 col-12 form-group">
                        <label>Imagen Portada</label>
                        <input type="file" class="form-control" name="filPor">
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-10 form-group">
                        <label>Nombre (*)</label>
                        <input type="text" class="form-control" name="txtNom" value="<?=(isset($datoCatalogo))?$datoCatalogo['catalogo_nombre']:''?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-md-5 col-12 form-group">
                        <label>Desde</label>
                        <input type="text" class="form-control" name="txtIni" value="<?=(isset($datoCatalogo))?$datoCatalogo['catalogo_fecha_inicio']:''?>">
                    </div>
                    <div class="col-md-5 col-12 form-group">
                        <label>Hasta</label>
                        <input type="text" class="form-control" name="txtFin" value="<?=(isset($datoCatalogo))?$datoCatalogo['catalogo_fecha_final']:''?>">
                    </div>
                </div> 
                <div class="row">
                    <div class="offset-md-1 col-md-10 col-12 form-group">
                        <label>Descripcion</label>
                        <textarea class="form-control" name="txtDes" rows="5"><?=(isset($datoCatalogo))?$datoCatalogo['catalogo_descripcion']:''?></textarea>
                    </div>
                </div>  
                <div class="row">
                    <div class="offset-md-1 col-md-10 col-12 form-group text-center">
                        <button name="btnGuardar" type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </div>  
            </form>       

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
