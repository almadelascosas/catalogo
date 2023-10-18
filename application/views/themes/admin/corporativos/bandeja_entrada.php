<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Bandeja de Entrada - Corporativos</span>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="<?=base_url()?>assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfmfa icon-bell"></i>
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
                                    <!--
                                    <select name="" id="">
                                        <option value="">25</option>
                                        <option value="">50</option>
                                        <option value="">75</option>
                                        <option value="">100</option>
                                    </select>-->
                                    12 Items por página 
                                </label>
                            </div>
                            <div class="col-6 text-right d-none">
                                <a class="btn btn-default px-3 py-1" href="<?=base_url()?>corporativos">Agregar producto</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <span>Filtros</span>
                        <form action="<?=base_url()?>corporativos/admin" method="get">
                            <div class="form-group">
                                <?php
                                $search="";
                                if (isset($_GET['search'])) {
                                    $search=$_GET['search'];
                                }
                                ?>
                                <input class="form-control" type="text" name="search" value="<?=$search?>">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-sm">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 listado mt-5">
                    <div class=" table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="checkbox-control" name="" id="">
                                    </th>
                                    <th>
                                        Atendido
                                    </th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Número</th>
                                    <th>Mensaje</th>
                                    <!--<th>Acciones</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($corporativos->result_array() as $key => $value) { ?>
                                <tr>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td>
                                        <label class="w-100">
                                            <input onchange="revisado(<?=$value['bandeja_corporativos_id']?>,this);" <?php if($value['bandeja_corporativos_revisado']){ echo "checked"; } ?> class="check_online" name="bandeja_corporativos_revisado[]" type="checkbox" value="1">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?=$value['bandeja_corporativos_nombre']?></td>
                                    <td><?=$value['bandeja_corporativos_correo']?></td>
                                    <td><?=$value['bandeja_corporativos_numero']?></td>
                                    <td><?=$value['bandeja_corporativos_mensaje']?></td>
                                    <!--<td>
                                        <a class="btn btn-warning text-white btn-sm" href="#">Editar</a>
                                        <a class="btn btn-danger text-white btn-sm" onclick="confirmDelete(<?=$value['bandeja_corporativos_id']?>)" href="#">Eliminar</a>
                                    </td>-->
                                </tr>
                                <?php
                                }
                                if ($corporativos->num_rows() <= 0) { ?>
                                <tr>
                                    <td colspan="20" class="text-center">No hay registros</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 text-center py-4">
                        <div class="paginado">
                            <ul class="list-inline">
                                <?php
                                if (isset($_GET['page']) && $_GET['page'] > 1) { 
                                    ?>
                                <li>
                                    <a href="<?=base_url()?>corporativos/admin?page=<?php if(isset($_GET['page'])){ echo $_GET['page']-1; } ?>">Anterior</a>
                                </li>
                                <?php
                                }
                                if (isset($_GET['page'])) {
                                    echo '<span class="btn rounded border mx-2">'.$_GET['page'].'</span>';
                                }else{
                                    echo '<span class="btn rounded border mx-2">1</span>';
                                }
                                if ($corporativos->num_rows() == 12) { ?>
                                <li>
                                    <a href="<?=base_url()?>corporativos/admin?page=<?php if(isset($_GET['page'])){ echo $_GET['page']+1; }else{ echo 2; } ?>">Siguiente</a>
                                </li>
                                <?php  } ?>
                            </ul>
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
          <h5 class=" text-center">Estás seguro que deseas eliminar este elemento?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>