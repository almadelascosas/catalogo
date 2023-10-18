<style>
    .dropdown-toggle{
        overflow:hidden;
    }
    .dropdown-item-inner[aria-selected=true] {
        background: #ccc;
    }
</style>
<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Permisos</span>
            <span class="wcfm_menu_dash_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
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
              <div class="heading">
                <h5>Menú Dashboard</h5>
              </div>
              <form action="<?=base_url()?>permisos/guardarMenu" method="post" class="col-12 mt-5">
                <div class="row">
                  <div class="col-md-3 col-12 form-group">
                    <label class="w-100">
                      Texto del hipervínculo<sup style="color:red;">*</sup>:
                      <input class="form-control" type="text" name="menu_dash_texto_agg" id="menu_dash_texto_agg" placeholder="Texto del hipervínculo.">
                    </label>
                  </div>
                  <div class="col-md-3 col-12 form-group">
                    <label class="w-100">
                      Hipervínculo/Enlace<sup style="color:red;">*</sup>:
                      <input class="form-control" type="text" name="menu_dash_enlace_agg" id="menu_dash_enlace_agg" placeholder="https://....">
                    </label>
                  </div>
                  <div class="col-md-3 col-12 form-group">
                    <label class="w-100">
                      Padre:
                      <select class="form-control" name="menu_dash_padre_agg" id="menu_dash_padre_agg">
                        <option value="0">Ninguno</option>
                        <?php
                          foreach ($menu->result_array() as $key => $value) { 
                            if ($value['menu_dash_padre']==0) {
                              ?>
                        <option value="<?=$value['menu_dash_id']?>"><?=$value['menu_dash_texto']?></option>
                        <?php
                            }
                          }?>
                      </select>
                    </label>
                  </div>
                  <div class="col-md-3 col-12 form-group">
                    <label class="w-100">
                        Accesos:
                        <select class="selectpicker" name="menu_dash_permiso_agg" multiple>
                            <?php
                            foreach ($tipo_accesos->result_array() as $key => $value) { 
                              ?>
                            <option value="<?=$value['tipos_accesos_id']?>"><?=$value['tipos_accesos_nombre']?></option>
                            <?php
                            }?>
                        </select>
                    </label>
                  </div>
                  
                  <div class="col-12 text-right">
                    <button type="button" onclick="agregarMenu();" class="btn btn-primary">Agregar</button>
                  </div>

                  <div class="col-12 mt-5">
                    <div class="row">
                      <div class="col-sm-6 col-12">
                        <div id="drag-elements" class="dragula col-12">
                          <?php
                          foreach ($menu->result_array() as $key => $value) {
                            if ($value['menu_dash_padre']==0) {
                            ?>
                            <div id="item-<?=$value['menu_dash_id']?>" class="rounded border">
                              <a target="_blank" href="<?=$value['menu_dash_enlace']?>"><?=$value['menu_dash_texto']?></a>
                              <span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x</span>
                              <input value="<?=$value['menu_dash_texto']?>" type="hidden" name="menu_dash_texto[]">
                              <input value="<?=$value['menu_dash_enlace']?>" type="hidden" name="menu_dash_enlace[]">
                              <input value="<?=$value['menu_dash_padre']?>" type="hidden" name="menu_dash_padre[]">
                              <input value="<?=$value['menu_dash_id']?>" type="hidden" name="menu_dash_id[]">
                              <input value="<?=$value['menu_dash_permiso']?>" type="hidden" name="menu_dash_permiso[]">
                              <?php
                              foreach ($menu->result_array() as $key2 => $value2) {
                                if ($value2['menu_dash_padre']==$value['menu_dash_id']) {
                                ?>
                              <div class="col-11 offset-1 p-2 mt-2 rounded border">
                                <a target="_blank" href="<?=$value2['menu_dash_enlace']?>"><?=$value2['menu_dash_texto']?></a>
                                <span style="float: right;cursor:pointer;height: 100%;width: 20px;text-align: center;" onclick="$(this).parent().remove();leerPadres();">x</span>
                                <input value="<?=$value2['menu_dash_texto']?>" type="hidden" name="menu_dash_texto[]">
                                <input value="<?=$value2['menu_dash_enlace']?>" type="hidden" name="menu_dash_enlace[]">
                                <input value="<?=$value2['menu_dash_padre']?>" type="hidden" name="menu_dash_padre[]">
                                <input value="<?=$value2['menu_dash_id']?>" type="hidden" name="menu_dash_id[]">
                                <input value="<?=$value2['menu_dash_permiso']?>" type="hidden" name="menu_dash_permiso[]">
                              </div>
                              <?php
                                }
                              }
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