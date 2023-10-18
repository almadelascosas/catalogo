<div class="col-12 modal-header py-4 text-center">
    <h5>Seleccione o suba una imagen.</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link" id="subirimg-tab" data-toggle="tab" href="#subirimg" role="tab" aria-controls="profile" aria-selected="false">Subir una Imagen...</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" id="seleccione-tab" data-toggle="tab" href="#seleccione" role="tab" aria-controls="home" aria-selected="true">Seleccione...</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active p-4" id="seleccione" role="tabpanel" aria-labelledby="seleccione-tab">
        <div clas="col-12">
            <div class="row" id="galeria-result">
                <?php
                foreach ($medios as $key => $value) { ?>
                <div class="col-md-3 col-sm-4 col-6 image-gallery">
                    <label for="image-<?=$value['medios_id']?>" onclick="selectImageBanner(<?=$value['medios_id']?>,'<?=$value['medios_url']?>')">
                        <input type="radio" name="image" id="image-<?=$value['medios_id']?>" value="<?=$value['medios_id']?>">
                        <div class="rec-image">
                            <img src="<?=$value['medios_url']?>" alt="<?=$value['medios_titulo']?>" srcset="<?=$value['medios_url']?>">
                        </div>
                    </label>
                </div>
                <?php
                }
                if (count($medios) <= 0) { ?>
                <div class="col-12 text-center">
                    <h4>No hay elementos...</h4>
                </div>
                <?php
                }
                ?>
            </div>

            <input value="1" type="hidden" name="pageGallery" id="pageGallery">
            <div class="col-12 text-center">
                <div class="paginado">
                    <ul class="list-inline">
                        <li>
                            <a onclick="paginadoGaleria('prev');" class="d-none" id="prev-gallery" href="#?page=1">Anterior</a>
                        </li>
                        <li>
                            <span id="paginaGaleria" class="btn rounded border mx-2">1</span>
                        </li>
                        <li>
                            <a onclick="paginadoGaleria('next');" id="next-gallery" href="#?page=2">Siguiente</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-12 modal-footer text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
        </div>
    </div>
    <div class="tab-pane fade" id="subirimg" role="tabpanel" aria-labelledby="subirimg-tab">
        <input type="hidden" name="medios_id" id="medios_id" value="">
        <input type="file" onchange="uploadImageBanner('#medios-galeria')" name="medios_attachment" id="medios-galeria" data-default-file="" data-max-file-size="2M" class="dropify">
        <div class="col-12 d-none" id="select-imgmini">
            <button type="button" id="btn-select-imgmini" class="btn btn-primary" data-dismiss="modal">Seleccionar</button>
        </div>
    </div>

</div>
