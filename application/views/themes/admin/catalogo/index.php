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
                <h3>Catalogo</h3>
            </div>
        </div>
        
    </div>
    <div class="offset-md-1 col-md-10">
        <div class="card">
            <div class="row">
                <div class="offset-md-1 col-10 text-right">
                    <h5>+ Agregar nuevo</h5>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-10 itemcata">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="assets/img/vistaPdf.jpg">
                        </div>
                        <div class="col-md-3">
                            <h4>Nombre</h4>
                            Desde 01-oct-2023 <br>
                            Hasta 15-nov-2023 
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="#" class="btn btn-success">Vigente</a>
                        </div>
                        <div class="col-md-2 text-center">
                            <i class="fas fa-download fa-2x"></i>
                        </div>
                        <div class="col-md-2 text-center">
                            <i class="fas fa-copy fa-2x"></i>
                        </div>
                    </div>
                </div>
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
