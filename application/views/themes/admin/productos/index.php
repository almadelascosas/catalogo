<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Productos</span>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="<?=base_url('assets/img/LOGO_ALMA.png')?>" data-tip="Perfil " data-hasqtip="1">
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
                                <a class="btn btn-default px-3 py-1" href="<?=base_url('productos/agregar')?>">Agregar producto</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <span>Filtros</span>
                        <form class="col-12" action="<?=base_url('productos/busqueda')?>" method="post">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="search" value="<?=isset($_SESSION['filtros']['where_arr']["productos_titulo"])?$_SESSION['filtros']['where_arr']["productos_titulo"]:''?>" placeholder="Frase o palabra">
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <select class="form-control" name="productos_estatus" id="productos_estatus">
                                            <option value="">Estado</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estatus"]) && intval($_SESSION['filtros']['where_arr']["productos_estatus"])===0)?"selected":"";?> value="0">Borrador</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estatus"]) && intval($_SESSION['filtros']['where_arr']["productos_estatus"])===1)?"selected":"";?> value="1">Público</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <select class="form-control" name="productos_estado_inv" id="productos_estado_inv">
                                            <option value="">Estado Inventario</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===0)?"selected":"";?> value="0">(Ninguno)</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===1)?"selected":"";?> value="1">Hay Existencias</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===2)?"selected":"";?> value="2">Agotado</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===3)?"selected":"";?> value="3">EnReserva</option>
                                        </select>
                                    </div>
                                </div>

                                <?php if (intval($_SESSION['tipo_accesos'])===0 || intval($_SESSION['tipo_accesos'])===1){ ?>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <select class="form-control" name="usuarios_id" id="usuarios_id">
                                            <option value="">Vendedor</option>
                                            <?php
                                            foreach($vendedores as $key => $user):
                                                $lastname = (trim($user['name'])===trim($user['lastname'])) ? $user['name'] : $user['name'].'-'.$user['lastname'];
                                                $selected=(isset($_SESSION['filtros']['where_arr']["usuarios_id"]) && intval($_SESSION['filtros']['where_arr']["usuarios_id"])===intval($user['usuarios_id'])) ? 'selected' : '';
                                                print '<option value="'.$user['usuarios_id'].'" '.$selected.'>'.$lastname.'</option>';
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>

                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <button type="submit" name="btnBuscar" class="btn btn-success">Buscar</button>
                                        <button type="submit" name="btnLimpiar" class="btn btn-warning">Limpiar</button>
                                    </div>
                                </div>

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
                                    <th>Nombre</th>
                                    <th>SKU</th>
                                    <th>Cantidad en inventario</th>
                                    <th>Estado</th>
                                    <th>Inventario</th>
                                    <th>Precio</th>
                                    <th>Taxonomías</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Vendido Por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($productos->result_array() as $key => $value) { ?>
                                <tr>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><?=$value['productos_titulo']?></td>
                                    <td><?=$value['productos_sku']?></td>
                                    <td><?=$value['productos_stock']?></td>
                                    <td><?=(intval($value['productos_estatus'])===0)?"Borrador":"Publicado";?></td>
                                    <td><?php
                                    if ($value['productos_estado_inv']==1) {
                                        echo "Hay existencia";
                                    }
                                    if ($value['productos_estado_inv']==2) {
                                        echo "Agotado";
                                    }
                                    if ($value['productos_estado_inv']==3) {
                                        echo "En reserva";
                                    }
                                    ?></td>
                                    <td><?=$value['productos_precio']?></td>
                                    <td>
                                        <?php
                                        $name_cat = "";
                                        $this_cat = explode("/,/",$value['productos_categorias']);
                                        foreach ($categorias->result_array() as $key2 => $value2) {
                                            if (in_array($value2["categorias_id"], $this_cat)) {
                                                if ($name_cat=="") {
                                                    $name_cat .= $value2['categorias_nombre'];
                                                }else{
                                                    $name_cat .= ", ".$value2['categorias_nombre'];
                                                }
                                            }
                                        }
                                        echo $name_cat;
                                        ?>
                                    </td>
                                    <td><?php
                                    switch ($value['productos_tipo_producto']) {
                                        case 1:
                                            echo "Producto Simple";
                                            break;
                                        case 2:
                                            echo "Producto Variable";
                                            break;
                                        case 3:
                                            echo "Producto Agrupado";
                                            break;
                                        case 4:
                                            echo "Producto Externo / Afiliado";
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                    ?></td>
                                    <td><?=$value['productos_fecha_creacion']?></td>
                                    <td><?=$value['name']?></td>
                                    <td>
                                        <a class="btn btn-info text-white btn-sm" href="<?=base_url('productos/editar/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <a class="btn btn-danger text-white btn-sm" onclick="confirmDelete(<?=$value['productos_id']?>)" href="javascript:void(0)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <?php
                                }
                                if ($productos->num_rows() <= 0) { ?>
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
                                    <a href="<?=base_url()?>productos/?page=<?php if(isset($_GET['page'])){ echo $_GET['page']-1; } ?>">Anterior</a>
                                </li>
                                <?php
                                }
                                if (isset($_GET['page'])) {
                                    echo '<span class="btn rounded border mx-2">'.$_GET['page'].'</span>';
                                }else{
                                    echo '<span class="btn rounded border mx-2">1</span>';
                                }
                                if ($productos->num_rows() == 12) { ?>
                                <li>
                                    <a href="<?=base_url()?>productos/?page=<?php if(isset($_GET['page'])){ echo $_GET['page']+1; }else{ echo 2; } ?>">Siguiente</a>
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
          <h5 class=" text-center">Estás seguro que deseas eliminar este producto?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>