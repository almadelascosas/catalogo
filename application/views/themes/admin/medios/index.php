<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Medios</span>
            <span class="wcfm_menu_toggler wcfm fa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="/panel" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="/panel" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfm fa fa-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
            </div>	
        </div>
        <div class="col-12 my-5">
            <form action="<?=base_url('medios/deleteMultiple')?>" method="post">
                <div class="wcfm-container simple variable external grouped booking">
                    <div class="heading">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-4 text-left">
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
                                 <div class="col-4 text-center">
                                    <button id="btnElimMultiple" class="btn btn-danger d-none" type="submit">Eliminar seleccionados</button>
                                </div>
                                <div class="col-4 text-right">
                                    <a class="btn btn-default px-3 py-1" href="<?=base_url('medios/agregar')?>">Añadir Nuevo</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 mt-5">
                            <span>Filtros</span>
                        </div>
                        
                    </div>
                    <div class="col-12 listado mt-5">
                        <div class=" table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>
                                            <!-- <input type="checkbox" class="checkbox-control" name="" id=""> -->
                                            Seleccionar
                                        </th>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Subido Por</th>
                                        <th>Fecha/Hora</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <?php
                                    setlocale(LC_ALL,"es_ES");
                                    setlocale(LC_TIME, "spanish");

                                    foreach ($medios as $key => $value) {
                                        $img=delBackslashIni($value['medios_enlace_miniatura']);
                                        if(!file_exists($img)) $img=delBackslashIni($value['medios_url']);

                                        $link=base_url($value['medios_url']);

                                        if(!file_exists($img)) {
                                            $img='assets/img/Not-Image.png'; 
                                            $link='';
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox-control" name="ckMedio[]" id="ckMedio[]" value="<?=$value['medios_id']?>">
                                        </td>
                                        <td class="text-center"> 
                                            <img class="lazy" data-src="<?=$img.'?'.rand()?>" alt="<?=$value['medios_titulo']?>" src="assets/img/loading/yellow_3.gif" style="max-width:100px;max-height:50px;"> 
                                        </td>
                                        <td class="text-left"> 
                                            <?php if($link!==''){ ?> <a href="<?=$link?>" target="_blank" rel="noopener noreferrer"><?php } ?>
                                            <?=$value['medios_titulo']?>
                                            <?php if($link!==''){ ?></a> <?php } ?>
                                        </td>
                                        <td class="text-left"> 
                                            <?php
                                            $usuario = $mdUsuarios->single($value['medios_user']);
                                            print $usuario['name'];
                                            ?> 
                                        </td>
                                        <td class="text-left">
                                            <?php 
                                            $fechaBD = strtotime($value['medios_fecha_registro']);
                                            $fechaAhora = strtotime(date('Y-m-d H:i:s'));
                                            if(intval($fechaBD) > 1694705336){
                                                $newDate = date("d-m-Y", strtotime($value['medios_fecha_registro']));                
                                                $fecha = strftime("%A, %d de %B, %Y", strtotime($newDate));
                                                print $fecha.'<br>'.date("g:i a",strtotime($value['medios_fecha_registro']));;


                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning text-white btn-sm" href="<?=base_url('medios/editar/'.$value['medios_id'].'/'.limpiarUri($value['medios_titulo']));?>">Editar</a>
                                            <a class="btn btn-danger text-white btn-sm" onclick="confirmDelete(<?=$value['medios_id']?>)" href="javascript:void(0)">Eliminar</a>
                                        </td>
                                    </tr>
                                    <?php
                                    }

                                    if (count($medios) <= 0) { ?>
                                    <tr>
                                        <td colspan="20" class="text-center">No existen registros</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="col-12 text-center py-4">
                                <div class="paginado">
                                    <ul class="list-inline">
                                        <?php if (isset($_GET['page']) && $_GET['page'] > 1) {  ?>
                                        <li>
                                            <a href="<?=base_url()?>medios/?page=<?php if(isset($_GET['page'])){ echo $_GET['page']-1; } ?>">Anterior</a>
                                        </li>
                                        <?php
                                        }
                                        if (isset($_GET['page'])) {
                                            echo '<span class="btn rounded border mx-2">'.$_GET['page'].'</span>';
                                        }else{
                                            echo '<span class="btn rounded border mx-2">1</span>';
                                        }
                                        if (count($medios) == 10) { 
                                        ?>
                                        <li>
                                            <a href="<?=base_url()?>medios/?page=<?php if(isset($_GET['page'])){ echo $_GET['page']+1; }else{ echo 2; } ?>">Siguiente</a>
                                        </li>
                                        <?php  } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
          <h5 class=" text-center">Estás seguro que deseas eliminar este elemento?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
