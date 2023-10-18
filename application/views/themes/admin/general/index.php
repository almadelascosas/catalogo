<script>
    var productos = [];
var categorias = [];

<?php
foreach ($categorias->result_array() as $key => $value) { ?>
categorias.push({
  'categorias_id':'<?=$value['categorias_id']?>',
  'categorias_nombre':'<?=$value['categorias_nombre']?>'
});
<?php
}
foreach ($productos->result_array() as $key => $value) { ?>
productos.push({
  'productos_id':'<?=$value['productos_id']?>',
  'productos_titulo':'<?=$value['productos_titulo']?>'
});
<?php
}
?>

</script>
<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">General</span>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
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
                <div class="heading">
                    <h5>Gestión de metodos de Pago</h5>
                </div>
                <form action="general/guardarMetodos" method="post" class="col-12 mt-5">
                    <div class="col-12 ">
                        <ul>
                            <?php foreach ($metodos_pagos->result_array() as $key => $value) { ?>
                              <li class="card my-2 p-2 d-inline-block col-md-4 col-12">
                                  <label class="w-100 my-4">
                                      <input type="hidden" name="alma_metodos_pagos_id[]" value="<?=$value['alma_metodos_pagos_id']?>">
                                      <input type="hidden" name="alma_metodos_pagos_titulo[]" value="<?=$value['alma_metodos_pagos_titulo']?>">
                                      <input <?php if ($value['alma_metodos_pagos_estatus']==1) { echo "checked" ; } ?> class="check_online" name="estatus[]" onchange="cambioEstatus(this);" type="checkbox" value="1">
                                      <span></span>
                                      <input type="hidden" name="alma_metodos_pagos_estatus[]" value="<?php  if ($value['alma_metodos_pagos_estatus']==1) { echo " 1"; }else{ echo "0" ; } ?>">
                                  </label>
                                  <p>
                                      <?=$value['alma_metodos_pagos_titulo']?>
                                  </p>
                              </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <h5>WhatsApp Plugin</h5>
                </div>
                <form action="general/guardarWhatsapp" method="post" class="col-12 mt-5">
                    <!--  
                <div class="col-12 my-4">
                  <button type="button" class="btn btn-default" onclick="addnumberWhatsapp();">Agregar Contacto</button>
                </div>
                -->
                    <div class="row" id="whatsapp-numbers">
                        <?php
                        $cont = 0;
                        foreach ($whatsapp_plugin->result_array() as $key => $value) {
                          $cont++; 
                        ?>
                        <div class="col-12 form-group card mb-4">
                            <div class="row">
                                <input type="hidden" name="whatsapp_plugin_id[]" value="<?=$value['whatsapp_plugin_id']?>">
                                <div class="col-12">
                                    <label class="w-100 my-4">
                                        <input class="check_online" <?php if($value['whatsapp_plugin_estatus']==1){ echo "checked" ; } ?> name="whatsapp_plugin_estatus[]" type="checkbox" value="1">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-md-1 col-2">
                                    <?php if ($cont > 1) { ?>
                                      <a class="btn btn-danger" onclick="$(this).parent().parent().parent().remove();" href="#delete-whatsapp">
                                          <span>X</span>
                                      </a>
                                    <?php } ?>
                                </div>
                                <div class="col-md-3 col-10">
                                    <label class="w-100">
                                        Número de WhatsApp:
                                        <input type="tel" class="form-control" value="<?=$value['whatsapp_plugin_telefono']?>" name="whatsapp_plugin_telefono[]">
                                    </label>
                                </div>
                                <div class="col-md-3 col-12">
                                    <label class="w-100">
                                        Nombre:
                                        <input type="text" class="form-control" maxlength="20" name="whatsapp_plugin_nombre[]" value="<?=$value['whatsapp_plugin_nombre']?>">
                                    </label>
                                </div>
                                <div class="col-md-3 col-12">
                                    <label class="w-100">
                                        Mensaje:
                                        <textarea class="form-control" name="whatsapp_plugin_mensaje[]" rows="3"><?=$value['whatsapp_plugin_mensaje']?></textarea>
                                    </label>
                                </div>
                                <div class="col-md-2 col-12">
                                    <label class="w-100">
                                        Número de atendidos:
                                        <input type="number" class="form-control" name="whatsapp_plugin_cantidad[]" value="<?=$value['whatsapp_plugin_cantidad']?>">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <a name="menu"></a>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <h5>Menú</h5>
                </div>
                <form action="general/guardarMenu" method="post" class="col-12 mt-5">
                    <div class="row">
                        <div class="col-md-3 col-12 form-group">
                            <label class="w-100">Texto del hipervínculo<sup style="color:red;">*</sup>:</label>
                            <input class="form-control" type="text" name="menu_texto_agg" id="menu_texto_agg" placeholder="Texto del hipervínculo.">
                        </div>
                        <div class="col-md-3 col-12 form-group">
                            <label class="w-100">Categoría <sup style="color:red;">*</sup>:</label>
                            <select class="form-control" name="menu_categoria_agg" id="menu_categoria_agg">
                                <?php foreach ($categorias_menu->result_array() as $key => $value) { ?>
                                  <option value="<?=$value['categorias_id']?>"><?=$value['categorias_nombre']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-12 form-group d-none">
                            <label class="w-100">Hipervínculo/Enlace<sup style="color:red;">*</sup>:</label>
                            <input class="form-control" type="text" name="menu_enlace_agg" id="menu_enlace_agg" placeholder="https://....">
                        </div>
                        <div class="col-md-3 col-12 form-group d-none">
                            <label class="w-100">Padre:</label>
                            <select class="form-control" name="menu_padre_agg" id="menu_padre_agg">
                                <option value="0">Ninguno</option>
                                <?php
                                foreach ($menu['menu']->result_array() as $key => $value) { 
                                  if ($value['menu_padre_id']==0) {
                                ?>
                                <option value="<?=$value['menu_id']?>"><?=$value['menu_texto']?></option>
                                <?php
                                  }
                                }
                                ?>
                            </select>
                            
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" onclick="agregarMenu();" class="btn btn-primary">Agregar</button>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div id="drag-elements" class="dragula col-12">
                                        <?php
                                        foreach ($menu['menu']->result_array() as $key => $value) {
                                          if ($value['menu_padre_id']==0) {
                                        ?>
                                        <div id="item-<?=$value['menu_id']?>" class="rounded border">
                                            <a target="_blank" href="<?=$value['menu_enlace']?>"><?=$value['menu_texto']?></a>
                                            <span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x</span>
                                            <input value="<?=$value['menu_texto']?>" type="hidden" name="menu_texto[]">
                                            <input value="<?=$value['menu_enlace']?>" type="hidden" name="menu_enlace[]">
                                            <input value="<?=$value['menu_padre_id']?>" type="hidden" name="menu_padre[]">
                                            <input value="<?=$value['menu_categorias_id']?>" type="hidden" name="menu_categorias_id[]">
                                            <input value="<?=$value['menu_id']?>" type="hidden" name="menu_id[]">
                                            <?php
                                            /*foreach ($menu->result_array() as $key2 => $value2) {
                                              if ($value2['menu_padre_id']==$value['menu_id']) {
                                              ?>
                                              <div class="col-11 offset-1 p-2 mt-2 rounded border">
                                                  <a target="_blank" href="<?=$value2['menu_enlace']?>">
                                                      <?=$value2['menu_texto']?></a>
                                                  <span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x</span>
                                                  <input value="<?=$value2['menu_texto']?>" type="hidden" name="menu_texto[]">
                                                  <input value="<?=$value2['menu_enlace']?>" type="hidden" name="menu_enlace[]">
                                                  <input value="<?=$value2['menu_padre_id']?>" type="hidden" name="menu_padre[]">
                                                  <input value="<?=$value2['menu_id']?>" type="hidden" name="menu_id[]">
                                              </div>
                                              <?php
                                              }
                                            }*/
                                            ?>
                                        </div>
                                        <?php
                                          }
                                        }     
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <a name="bannerhome"></a>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <h5>Banner Home</h5>
                </div>
                <form action="<?=base_url('general/guardarBannerHome')?>" method="post">
                    <div class="row">
                        <div class="col-md-8 col-12 form-group">
                            <label class="w-100">
                                Imagen <sup style="color:red;">*</sup>:
                                <div class="col-12 card-gallery border rounded mb-2 text-center">
                                    <?php $bannerHome=($banner_home['banner_imagen']!=NULL && $banner_home['banner_imagen']!="" && $banner_home['banner_imagen']!=0)?$banner_home['banner_imagen_url']:'assets/uploads/Placeholder.png'; ?>
                                    <img class="principal " onclick="galeria(this);" src="<?=$bannerHome.'?'.rand()?>" alt="Placeholder">
                                    <input type="hidden" required id="banner_imagen" name="banner_imagen" value="<?=$banner_home['banner_imagen']?>">
                                </div>
                            </label>
                          </div>
                          <div class="col-md-4 col-12 form-group">
                            <label class="w-100">
                                Imagen en Mobile <sup style="color:red;">*</sup>:
                                <div class="col-12 card-gallery border rounded mb-2 text-center">
                                    <?php $bannerMobilHome=($banner_home['banner_imagen_mobile']!=NULL && $banner_home['banner_imagen_mobile']!="" && $banner_home['banner_imagen_mobile']!=0) ? $banner_home['banner_imagen_mobile_url'] : ''; ?>
                                    <img class="principal " onclick="galeriaMini(this);" src="<?=$bannerMobilHome.'?'.rand()?>" alt="Placeholder">
                                    <input type="hidden" required id="banner_imagen_mobile" name="banner_imagen_mobile" value="<?=$banner_home['banner_imagen_mobile']?>">
                                </div>
                            </label>
                        </div>
                        <div class="col-12 form-group">
                            <label class="w-100">
                                Hipervínculo/Enlace<sup style="color:red;">*</sup>:
                                <input required class="form-control" type="text" value="<?=$banner_home['banner_enlace']?>" name="banner_enlace" id="banner_enlace" placeholder="https://....">
                            </label>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <a name="corporativos"></a>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <h5>Banner Corporativos</h5>
                </div>
                <form action="<?=base_url('general/guardarBannerCorporativos')?>" method="post">
                    <div class="row">
                        <div class="col-8 form-group">
                            <label class="w-100">
                                Imagen <sup style="color:red;">*</sup>:
                                <div class="col-12 card-gallery border rounded mb-2 text-center">
                                    <?php $bannerCorporativo = ($banner_corporativos['banner_imagen']!=NULL && $banner_corporativos['banner_imagen']!="" && $banner_corporativos['banner_imagen']!=0) ? $banner_corporativos['banner_imagen_url'] : 'assets/uploads/Placeholder.png'; ?>
                                    <div class="col-12" onclick="galeriaBanner(this);">
                                        <img class="principal" src="<?=$bannerCorporativo.'?'.rand()?>" alt="Placeholder">
                                        <input type="hidden" required id="" name="banner_imagen" value="<?=$banner_corporativos['banner_imagen']?>">
                                    </div>
                                </div>
                            </label>
                          </div>
                          <div class="col-4 form-group">
                            <label class="w-100"> Imagen en Mobile <sup style="color:red;">*</sup>:</label>
                            <div class="col-12 card-gallery border rounded mb-2 text-center">
                                <?php $bannerMobileCorp = ($banner_corporativos['banner_imagen_mobile']!=NULL && $banner_corporativos['banner_imagen_mobile']!="" && $banner_corporativos['banner_imagen_mobile']!=0) ? $banner_corporativos['banner_imagen_mobile_url'] : ''; ?>
                                <div class="col-12" onclick="galeriaBanner(this);">
                                    <img class="principal" src="<?=$bannerMobileCorp.'?'.rand()?>" alt="Placeholder">
                                    <input type="hidden" required id="" name="banner_imagen_mobile" value="<?=$banner_corporativos['banner_imagen_mobile']?>">
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-12 col-md-8 form-group">
                            <label class="w-100">Hipervínculo/Enlace <sup style="color:red;">*</sup>:</label>
                            <input required class="form-control" type="text" value="<?=$banner_corporativos['banner_enlace']?>" name="banner_enlace" id="banner_enlace" placeholder="https://....">
                        </div>
                        <div class="col-12 col-md-4 form-group">
                            <label class="w-100">Target <sup style="color:red;">*</sup>:</label>
                            <select class="form-control" name="banner_target">
                                <option value="_top" <?=($banner_corporativos['banner_target']==='_top')?'selected':''?>>Top</option>
                                <option value="_blank" <?=($banner_corporativos['banner_target']==='_blank')?'selected':''?>>Blank</option>
                                <option value="_self" <?=($banner_corporativos['banner_target']==='_self')?'selected':''?>>Self</option>
                                <option value="_parent" <?=($banner_corporativos['banner_target']==='_parent')?'selected':''?>>Parent</option>
                            </select>
                            
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <h5>Banner App</h5>
                </div>
                <div id="ex-banner-add" class="d-none">
                    <div class="col-md-6 col-12 my-2 form-group">
                        <div class="w-100 d-table card-gallery text-center border rounded mb-2" style="height:140px;">
                            <div class="d-table-cell align-middle" onclick="galeriaBanner(this);">
                                <img class="principal" src="/assets/uploads/Placeholder.png" alt="Placeholder">
                                <input type="hidden" required id="banner_app_imagen[]" name="banner_app_imagen[]" value="">
                            </div>
                            <div class="w-100 controls-gallery">
                                <span onclick="$(this).parent().parent().parent().remove()">x</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="w-100">
                                Texto Banner:
                                <input type="text" class="form-control" name="banner_app_texto[]" placeholder="Texto Banner">
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="w-100">
                                Tipo de Banner:
                                <select onchange="tipo_redireccion(this);" class="form-control" name="banner_app_tipo[]">
                                    <option value="0">Categoría</option>
                                    <option value="1">Producto</option>
                                    <option value="2">Enlace Externo</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group tipos_red id_redireccion" id="">
                            <label class="w-100">
                                ID de redirección:
                                <select class="form-control" name="banner_app_id_redireccion[]">
                                    <?php
                      foreach ($categorias->result_array() as $key => $value) { ?>
                                    <option value="<?=$value['categorias_id']?>">
                                        <?=$value['categorias_nombre']?>
                                    </option>
                                    <?php
                      }
                      ?>
                                </select>
                            </label>
                        </div>
                        <div class="form-group tipos_red d-none url_redireccion" id="">
                            <label class="w-100">
                                Enlace Redirección:
                                <input type="text" name="banner_app_enlace_redireccion[]" class="form-control">
                            </label>
                        </div>
                    </div>
                </div>
                <form action="general/guardarBannerApp" method="post" class="col-12 mt-5">
                    <div class="row" id="formBannerApp">
                        <div class="col-12 my-2 form-group">
                            <button onclick="crearElemento('#ex-banner-add','#formBannerApp');" type="button" class="btn btn-sm btn-success">
                                Agregar Slide <span class="icon-plus"></span>
                            </button>
                        </div>
                        <?php
                if($banner_app->num_rows() > 0){
                  $cont = 0;
                  foreach ($banner_app->result_array() as $key => $value) {
                    $cont++; 
                    ?>
                        <div class="col-md-6 col-12 form-group my-2">
                            <?php
                  if ($cont!=1) { ?>
                            <div class="w-100 controls-gallery">
                                <span onclick="$(this).parent().parent().remove()">x</span>
                            </div>
                            <?php
                  }
                  ?>
                            <div class="w-100 d-table card-gallery text-center border rounded mb-2" style="height:140px;">
                                <div class="d-table-cell align-middle" onclick="galeriaBanner(this);">
                                    <img class="principal" src="<?=base_url().$value['image_url']?>" alt="Placeholder">
                                    <input type="hidden" required id="banner_app_imagen[]" name="banner_app_imagen[]" value="<?=$value['banner_app_imagen_url']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="w-100">
                                    Texto Banner:
                                    <input type="text" class="form-control" name="banner_app_texto[]" placeholder="Texto Banner" value="<?=$value['banner_app_texto']?>">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="w-100">
                                    Tipo de Banner:
                                    <select onchange="tipo_redireccion(this);" class="form-control" name="banner_app_tipo[]">
                                        <option <?php if($value['banner_app_tipo']==0){ echo "selected" ; } ?> value="0">Categoría</option>
                                        <option <?php if($value['banner_app_tipo']==1){ echo "selected" ; } ?> value="1">Producto</option>
                                        <option <?php if($value['banner_app_tipo']==2){ echo "selected" ; } ?> value="2">Enlace Externo</option>
                                    </select>
                                </label>
                            </div>
                            <div class="form-group tipos_red <?php if($value['banner_app_tipo']!=0 && $value['banner_app_tipo']!=1){ echo " d-none"; } ?> id_redireccion" id="">
                                <label class="w-100">
                                    ID de redirección:
                                    <select class="form-control" name="banner_app_id_redireccion[]">
                                        <?php
                        if ($value['banner_app_tipo']==0 || $value['banner_app_tipo']==2) {
                          foreach ($categorias->result_array() as $key2 => $value2) { ?>
                                        <option <?php if($value2['categorias_id']==$value['banner_app_id_redireccion']){ echo "selected" ; } ?> value="
                                            <?=$value2['categorias_id']?>">
                                            <?=$value2['categorias_nombre']?>
                                        </option>
                                        <?php
                          }
                        }else{
                          foreach ($productos->result_array() as $key2 => $value2) { ?>
                                        <option <?php if($value2['productos_id']==$value['banner_app_id_redireccion']){ echo "selected" ; } ?> value="
                                            <?=$value2['productos_id']?>">
                                            <?=$value2['productos_titulo']?>
                                        </option>
                                        <?php
                          }
                        }
                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="form-group tipos_red <?php if($value['banner_app_tipo']!=2){ echo " d-none"; } ?> url_redireccion" id="">
                                <label class="w-100">
                                    Enlace Redirección:
                                    <input type="text" name="banner_app_enlace_redireccion[]" class="form-control" value="<?=$value['banner_app_enlace_redireccion']?>">
                                </label>
                            </div>
                        </div>
                        <?php
                  } 
                }else{ ?>
                        <div class="col-md-6 col-12 form-group my-2">
                            <div class="w-100 d-table card-gallery text-center border rounded mb-2" style="height:140px;">
                                <div class="d-table-cell align-middle" onclick="galeriaBanner(this);">
                                    <img class="principal" src="/assets/uploads/Placeholder.png" alt="Placeholder">
                                    <input type="hidden" required id="banner_app_imagen[]" name="banner_app_imagen[]" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="w-100">
                                    Texto Banner:
                                    <input type="text" class="form-control" name="banner_app_texto[]" placeholder="Texto Banner">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="w-100">
                                    Tipo de Banner:
                                    <select onchange="tipo_redireccion(this);" class="form-control" name="banner_app_tipo[]">
                                        <option value="0">Categoría</option>
                                        <option value="1">Producto</option>
                                        <option value="2">Enlace Externo</option>
                                    </select>
                                </label>
                            </div>
                            <div class="form-group tipos_red id_redireccion" id="">
                                <label class="w-100">
                                    ID de redirección:
                                    <select class="form-control" name="banner_app_id_redireccion[]">
                                        <?php
                        foreach ($categorias->result_array() as $key => $value) { ?>
                                        <option value="<?=$value['categorias_id']?>">
                                            <?=$value['categorias_nombre']?>
                                        </option>
                                        <?php
                        }
                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="form-group tipos_red d-none url_redireccion" id="">
                                <label class="w-100">
                                    Enlace Redirección:
                                    <input type="text" name="banner_app_enlace_redireccion[]" class="form-control">
                                </label>
                            </div>
                        </div>
                        <?php
                }
                ?>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <h5>Banner Noticias Home</h5>
                </div>
                <form action="general/guardarBannerMensajes" method="post" class="col-12 mt-5">
                    <div class="row">
                        <?php
                foreach ($bannerMensajes->result_array() as $key => $value) { ?>
                        <div class="col-12 form-group border p-3">
                            <div class="row">
                                <input type="hidden" name="banner_home_noticias_id[]" value="<?=$value['banner_home_noticias_id']?>">
                                <div class="col-md-3 col-12">
                                    <div class="w-100 d-table card-gallery text-center border rounded mb-2">
                                        <div class="d-table-cell align-middle" onclick="galeriaBanner(this);">
                                            <img class="principal" src="<?=base_url().$value['image_url']?>" alt="Placeholder">
                                            <input type="hidden" required name="banner_home_noticias_imagen[]" value="<?=$value['banner_home_noticias_imagen']?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 col-12">
                                    <label class="w-100">
                                        Texto:
                                        <input type="text" required class="form-control" value="<?=$value['banner_home_noticias_texto']?>" name="banner_home_noticias_texto[]">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php
                }
                ?>
                        <div class="col-12 text-center">
                            <button class="btn btn-success btn-sm">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
  if ($max_id!=NULL) { ?>
    var suma_padre = <?=$max_id?>;
  <?php
  }else{ ?>
    var suma_padre = 0;
  <?php
  }
  ?>
</script>
<div class="modal fade" id="ModalDel" tabindex="-1" role="dialog" aria-labelledby="ModalDelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class=" text-center">Estás seguro que deseas eliminar este usuario?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
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