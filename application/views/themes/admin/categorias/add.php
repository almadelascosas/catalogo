<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <?php
            if ($categoria['categorias_id']=="" || $categoria['categorias_id']==0 || $categoria['categorias_id']==NULL) { ?>
            <span class="wcfm-page-heading-text">Añadir Categoria</span>
            <?php
            }else { ?>
                <span class="wcfm-page-heading-text">Actualizar Categoria</span>
            <?php
            }
            ?>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="https://almadelascosas.com/wp-content/plugins/wc-frontend-manager/assets/images/user.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfmfa fa-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
            </div>	
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <form enctype="multipart/form-data" class="col-12 contact-form" action="<?=base_url()?>categorias/guardar" method="post">
                    <div class="row">
                        <input type="hidden" name="categorias_id" id="categorias_id" value="<?=$categoria['categorias_id']?>">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-4 col-12 form-group text-center">
                                    <label class="w-100">
                                        Imagen <sup style="color:red;">*</sup>:
                                        <div class="col-12 card-gallery border rounded mb-2">
                                            <?php
                                            if ($categoria['categorias_imagen']!=NULL && $categoria['categorias_imagen']!="" && $categoria['categorias_imagen']!=0) {
                                                ?>
                                            <img class="principal" onclick="galeria(this);" src="<?=base_url().$categoria['categorias_imagen_enlace']?>" alt="Placeholder">
                                                <?php
                                            }else{ ?>
                                            <img class="principal" onclick="galeria(this);" src="<?=base_url()?>assets/uploads/Placeholder.png" alt="Placeholder">
                                            <?php
                                            }
                                            ?>
                                            <input type="hidden" required id="categorias_imagen" name="categorias_imagen" value="<?=$categoria['categorias_imagen']?>">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4 col-12 form-group text-center">
                                    <label class="w-100">
                                        Imagen Banner (Escritorio)<sup style="color:red;">*</sup>:
                                        <div class="col-12 card-gallery border rounded mb-2">
                                            <?php
                                            if ($categoria['categorias_banner_desktop']!=NULL && $categoria['categorias_banner_desktop']!="" && $categoria['categorias_banner_desktop']!=0) {
                                                ?>
                                            <img class="principal" onclick="galeriaMini(this);" src="<?=base_url().$categoria['categorias_banner_desktop_enlace']?>" alt="Placeholder">
                                                <?php
                                            }else{ ?>
                                            <img class="principal" onclick="galeriaMini(this);" src="<?=base_url()?>assets/uploads/Placeholder.png" alt="Placeholder">
                                            <?php
                                            }
                                            ?>
                                            <input type="hidden" required id="categorias_banner_desktop" name="categorias_banner_desktop" value="<?=$categoria['categorias_banner_desktop']?>">
                                        </div>
                                    </label>

                                </div>
                                <div class="col-md-4 col-12 form-group text-center">
                                    <label class="w-100">
                                        Imagen Banner (Móvil)<sup style="color:red;">*</sup>:
                                        <div class="col-12 card-gallery border rounded mb-2">
                                            <?php
                                            if ($categoria['categorias_banner_mobile']!=NULL && $categoria['categorias_banner_mobile']!="" && $categoria['categorias_banner_mobile']!=0) {
                                                ?>
                                            <img class="principal" onclick="galeriaBanner(this);" src="<?=base_url().$categoria['categorias_banner_mobile_enlace']?>" alt="Placeholder">
                                                <?php
                                            }else{ ?>
                                            <img class="principal" onclick="galeriaBanner(this);" src="<?=base_url()?>assets/uploads/Placeholder.png" alt="Placeholder">
                                            <?php
                                            }
                                            ?>
                                            <input type="hidden" required id="categorias_banner_mobile" name="categorias_banner_mobile" value="<?=$categoria['categorias_banner_mobile']?>">
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="categorias_nombre">Nombre</label>
                                <input class="form-control" type="text" id="categorias_nombre" name="categorias_nombre" value="<?=$categoria['categorias_nombre']?>">
                            </div>
                            <div class="form-group">
                                <label for="categorias_padre">Categoria Padre:</label>
                                <select class="form-control" name="categorias_padre" id="categorias_padre">
                                    <option <?php if ($categoria['categorias_padre']==0) { echo "selected"; } ?> value="0">Ninguna</option>
                                    <?php
                                    foreach ($categorias->result_array() as $key => $value) { 
                                        if ($value['categorias_id']!=$categoria['categorias_id']) {
                                        ?>
                                        <option <?php if($value['categorias_id']==$categoria['categorias_padre']){ echo "selected"; } ?> value="<?=$value['categorias_id']?>"><?=$value['categorias_nombre']?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="categorias_ubicacion_requerido">Ubicación:</label>
                                <select name="categorias_ubicacion_requerido" id="categorias_ubicacion_requerido">
                                    <option <?php if($categoria['categorias_ubicacion_requerido']==0){ echo "selected"; } ?> value="0">No Requerida</option>
                                    <option <?php if($categoria['categorias_ubicacion_requerido']==1){ echo "selected"; } ?> value="1">Requerida</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="categorias_status">Estado de la categoría</label>
                                <select name="categorias_status" id="categorias_status">
                                    <option <?php if($categoria['categorias_status']==1){ echo "selected"; } ?> value="1">Activa</option>
                                    <option <?php if($categoria['categorias_status']==0){ echo "selected"; } ?> value="0">Inactiva</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <p class="mb-0"><span><?=base_url()?></span></p>
                                <h5 class="m-0"><a class="color-link-google" href="#" id="tltseo"><?=$categoria['categorias_titulo_seo']?></a></h5>
                                <p class="" id="metadesc"><?=$categoria['categorias_meta_descripcion']?></p>
                            </div>

                            <div class="form-group">
                                <label for="categorias_titulo_seo">Titulo de SEO</label>
                                <input class="form-control" onchange="cambiotxt(this,'#tltseo');" type="text" id="categorias_titulo_seo" name="categorias_titulo_seo" value="<?=$categoria['categorias_titulo_seo']?>">
                            </div>
                            <div class="form-group">
                                <label for="categorias_meta_descripcion">Metadescripcion</label>
                                <textarea class="form-control mb-0" onchange="cambiotxt(this,'#metadesc');" id="categorias_meta_descripcion" name="categorias_meta_descripcion"><?=$categoria['categorias_meta_descripcion']?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="categorias_titulo_h1">Titulo H1</label>
                                <input class="form-control" type="text" id="categorias_titulo_h1" name="categorias_titulo_h1" value="<?=$categoria['categorias_titulo_h1']?>">
                            </div>

                        </div>
                        <div class="col-12 mt-4 text-center">
                            <?php
                            if ($categoria['categorias_id']=="" || $categoria['categorias_id']==0 || $categoria['categorias_id']==NULL) { ?>
                            <button type="submit" class="btn btn-success btn-sm">Subir</button>
                            <?php
                            }else { ?>
                            <button type="submit" class="btn btn-success btn-sm">Actualizar</button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalGallery" tabindex="-1" role="dialog" aria-labelledby="ModalGalleryLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content" id="content-gallery">

    </div>
  </div>
</div>