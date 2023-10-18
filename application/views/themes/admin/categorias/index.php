<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Categorias</span>
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
                <div class="heading">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6 text-left">
                                <label for="">
                                    <select name="" id="">
                                        <option value="">25</option>
                                        <option value="">50</option>
                                        <option value="">75</option>
                                        <option value="">100</option>
                                    </select>
                                    Items por página 
                                </label>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-default px-3 py-1" href="<?=base_url()?>categorias/agregar">Añadir Nuevo</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <span>Filtros</span>
                    </div>
                </div>
                <div class="col-12 listado mt-5">
                    <div class=" table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="checkbox-control" name="" id="">
                                    </th>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody >
                                <?php
                                foreach ($categorias->result_array() as $key => $value) { ?>
                                <tr>
                                    <td><input type="checkbox" class="checkbox-control" name="" id=""></td>
                                    <td><?=$value['categorias_nombre']?></td>
                                    <td><?=$value['categorias_slug']?></td>
                                    <td><?php
                                    if ($value['categorias_status']==1) {
                                        echo "Activa";
                                    }else{
                                        echo "Inactiva";
                                    }
                                    ?></td>
                                    <td>
                                        <a class="btn btn-warning text-white btn-sm" href="<?=base_url()?>categorias/editar/<?=$value['categorias_id']?>/<?=limpiarUri($value['categorias_nombre'])?>">Editar</a>
                                        <a class="btn btn-danger text-white btn-sm" onclick="confirmDelete(<?=$value['categorias_id']?>)" href="#">Eliminar</a>
                                    </td>
                                </tr>
                                <?php
                                }
                                if ($categorias->num_rows() <= 0) { ?>
                                <tr>
                                    <td colspan="20" class="text-center">No hay registros</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
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
          <h5 class=" text-center">Estás seguro que deseas eliminar este elemento?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>