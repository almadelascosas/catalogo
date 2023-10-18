<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            
            <span class="wcfm-page-heading-text"><?=($medio['medios_id']=="" || $medio['medios_id']==0 || $medio['medios_id']==NULL)?'Añadir':'Actualizar';?> Medio</span>

            <span class="wcfm_menu_toggler wcfm fa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="/panel" target="_blank">Mi tienda.</a></span>
            <div class="wcfm_header_panel">
                <a href="/panel" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfm fa fa-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
            </div>	
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <form enctype="multipart/form-data" class="col-12 contact-form" action="<?=base_url('medios/guardar')?>" method="post">
                    <div class="row">
                        <input type="hidden" name="medios_id" id="medios_id" value="<?=(isset($medio))?$medio['medios_id']:''?>">

                        <div class="col-12">
                            <input type="file" name="medios_attachment" id="medios_attachment" data-default-file="" data-max-file-size="20M" class="dropify">
                        </div>

                        <?php if ($medio['medios_id']!=="" || $medio['medios_id']!==0 || $medio['medios_id']!==NULL) { ?>
                        
                        <div class="col-12 text-center">
                            <label>Si desea modificar la imagen, cargue el nuevo archivo</label>
                            <br>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="h-100 w-100 d-table">
                                <div class="d-table-cell align-middle text-center">
                                    <img src="<?=$medio['medios_url'].'?'.rand()?>" alt="<?=$medio['medios_titulo']?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="medios_url">Ubicación Imagen Principal</label>
                                <input class="form-control" type="text" name="medios_url" id="medios_url" value="<?=$medio['medios_url']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="medios_url">Ubicación Imagen Miniatura</label>
                                <input class="form-control" type="text" name="medios_enlace_miniatura" id="medios_enlace_miniatura" value="<?=$medio['medios_enlace_miniatura']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="medios_titulo">Titulo:</label>
                                <input class="form-control" type="text" name="medios_titulo" id="medios_titulo" value="<?=$medio['medios_titulo']?>">
                            </div>
                            <div class="form-group">
                                <label for="medios_txt_alternativo">Texto Alternativo:</label>
                                <input class="form-control" type="text" name="medios_txt_alternativo" id="medios_txt_alternativo" value="<?=$medio['medios_txt_alternativo']?>">
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        </div>
                        <div class="col-12 mt-4 text-center">                            
                            <button type="submit" class="btn btn-success btn-sm"><?=($medio['medios_id']=="" || $medio['medios_id']==0 || $medio['medios_id']==NULL) ? 'Guardar' : 'Actualizar'?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>