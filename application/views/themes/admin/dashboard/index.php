<?php
if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL ) {
    $page = $_REQUEST['page'];
    $pageNext = $page+1;
    $pagePrev = $page-1;
}else{
    $page = 1;
    $pageNext = 2;
    $pagePrev = 0;
}
if (isset($_REQUEST['page_prod']) && $_REQUEST['page_prod']!=NULL ) {
    $page_prod = $_REQUEST['page_prod'];
    $pageNext_prod = $page_prod+1;
    $pagePrev_prod = $page_prod-1;
}else{
    $page_prod = 1;
    $pageNext_prod = 2;
    $pagePrev_prod = 0;
}
$uri = explode("?",$_SERVER["REQUEST_URI"]);
$sinreq = $uri[0];
$varequest = "";
$varequestPrev = "";
$conteo = 0;
foreach ($_REQUEST as $key => $value) {
    if ($key!="ci_session") {
        $conteo++;
        if (is_array($value)) {
            if ($conteo==1) {
                $conteo2=0;
                foreach ($value as $key2 => $value2) {
                    $conteo2++;
                    if ($conteo2==1) {
                        $varequest.=$key."%5B%5D=".$value2;
                        $varequestPrev.=$key."%5B%5D=".$value2;
                    }else{
                        $varequest.="&".$key."%5B%5D=".$value2;
                        $varequestPrev.="&".$key."%5B%5D=".$value2;
                    }
                }
            }else{
                foreach ($value as $key2 => $value2) {
                    $varequest.="&".$key."%5B%5D=".$value2;
                    $varequestPrev.="&".$key."%5B%5D=".$value2;
                }
            }
        }else{
            if ($conteo==1) {
                if ($key=="page") {
                    $nvalue=$value+1;
                    $varequest.=$key."=".$nvalue;
                    $pvalue=$value-1;
                    $varequestPrev.=$key."=".$pvalue;
                }else{
                    $varequest.=$key."=".$value;
                    $varequestPrev.=$key."=".$value;
                }
            }else{
                if ($key=="page") {
                    $nvalue=$value+1;
                    $varequest.="&".$key."=".$nvalue;
                    $pvalue=$value-1;
                    $varequestPrev.="&".$key."=".$pvalue;
                }else{
                    $varequest.="&".$key."=".$value;
                    $varequestPrev.="&".$key."=".$value;
                }
            }
        }
    }
}
$uri_prod = explode("?",$_SERVER["REQUEST_URI"]);
$sinreq_prod = $uri_prod[0];
$varequest_prod = "";
$varequestPrev_prod = "";
$conteo = 0;
foreach ($_REQUEST as $key => $value) {
    if ($key!="ci_session") {
        $conteo++;
        if (is_array($value)) {
            if ($conteo==1) {
                $conteo2=0;
                foreach ($value as $key2 => $value2) {
                    $conteo2++;
                    if ($conteo2==1) {
                        $varequest_prod.=$key."%5B%5D=".$value2;
                        $varequestPrev_prod.=$key."%5B%5D=".$value2;
                    }else{
                        $varequest_prod.="&".$key."%5B%5D=".$value2;
                        $varequestPrev_prod.="&".$key."%5B%5D=".$value2;
                    }
                }
            }else{
                foreach ($value as $key2 => $value2) {
                    $varequest_prod.="&".$key."%5B%5D=".$value2;
                    $varequestPrev_prod.="&".$key."%5B%5D=".$value2;
                }
            }
        }else{
            if ($conteo==1) {
                if ($key=="page") {
                    $nvalue=$value+1;
                    $varequest_prod.=$key."=".$nvalue;
                    $pvalue=$value-1;
                    $varequestPrev_prod.=$key."=".$pvalue;
                }else{
                    $varequest_prod.=$key."=".$value;
                    $varequestPrev_prod.=$key."=".$value;
                }
            }else{
                if ($key=="page") {
                    $nvalue=$value+1;
                    $varequest_prod.="&".$key."=".$nvalue;
                    $pvalue=$value-1;
                    $varequestPrev_prod.="&".$key."=".$pvalue;
                }else{
                    $varequest_prod.="&".$key."=".$value;
                    $varequestPrev_prod.="&".$key."=".$value;
                }
            }
        }
    }
}

if (array_key_exists('page',$_REQUEST)) {
    $urlActualNextPage = base_url($sinreq."?".$varequest);
    $urlActualPrevPage = base_url($sinreq."?".$varequestPrev);
}else{
    if ($varequest!="") {
        $urlActualNextPage = base_url($sinreq."?".$varequest."&page=2");
        $urlActualPrevPage = base_url($sinreq."?".$varequestPrev);
    }else{
        $urlActualNextPage = base_url($sinreq."?page=2");
        $urlActualPrevPage = base_url($sinreq);
    }
}
if (array_key_exists('page_prod',$_REQUEST)) {
    $urlActualNextPage_prod = base_url($sinreq_prod."?".$varequest_prod);
    $urlActualPrevPage_prod = base_url($sinreq_prod."?".$varequestPrev_prod);
}else{
    if ($varequest_prod!="") {
        $urlActualNextPage_prod = base_url($sinreq_prod."?".$varequest_prod."&page_prod=2");
        $urlActualPrevPage_prod = base_url($sinreq_prod."?".$varequestPrev_prod);
    }else{
        $urlActualNextPage_prod = base_url($sinreq_prod."?page_prod=2");
        $urlActualPrevPage_prod = base_url($sinreq_prod);
    }
}
?>

<div class="content ">
    <div class="col-12 pl-md-5">

        <div class="col-12 nwcfm-page-headig pt-4">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text mt-4">Escritorio</span>
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
        <div class="row my-5">
            <?php
            if ($pedidos->num_rows() > 0) { ?>
            <div class="col-12 col-md-7 tabla-pedidos pt-0">
                <h3 class="mb-4">Pedidos Atrasados</h3>
                <table class="table table-responsive px-0 pt-0">
                    <thead class="text-center">
                        <tr>
                            <th>
                                <p>Foto</p>
                            </th>
                            <th>
                                <p># Pedido</p>
                            </th>
                            <th>
                                <p>Fecha</p>
                            </th>
                            <th>
                                <p>Retraso</p>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($pedidos->result_array() as $key => $value) { 
                            $img=delBackslashIni($value['medios_url']);
                            if(!file_exists($img)) $img='assets/img/Not-Image.png'; 
                        ?>
                        <tr class="text-center">
                            <td>
                                <img class="lazy" data-src="<?=$img.'?'.rand()?>" src="assets/img/loading/yellow_3.gif" alt="Image">
                            </td>
                            <td>
                                Orden # <?=$value['pedidos_id']?>
                            </td>
                            <td>
                                <?=fecha_esp($value['pedidos_fecha_creacion'])?>
                            </td>
                            <td>
                                <?php
                                $fecha = $value['pedidos_fecha_creacion'];
                                $fecha_actual = date("Y-m-d");
                                $productos_entrega = array(1, 1, 3, 4, 5, 6, 8);
                                
                                /* Iniciamos la validación de ubicación ya sea local o nacional */
                                $add_nacional = 0;
                                if ($value['productos_envio_nacional']==0) {
                                    $add_nacional = 0;
                                    $productos_entrega = array(1, 1, 2, 3, 4, 5, 7, 8);
                                }else{
                                    $ubicaciones = explode("/",$value['productos_ubicaciones_envio']);
                                    $municipios = array();
                                    for ($i=0; $i < count($ubicaciones); $i++) {
                                        $ub = explode(",", $ubicaciones[$i]);
                                        if (isset($ub[1])) {
                                            array_push($municipios, $ub[1]);
                                        }
                                    }
                                    if (in_array($value['pedidos_municipio'],$municipios)) {
                                        $productos_entrega = array(1, 1, 3, 4, 5, 6, 8);
                                        $add_nacional = 0;
                                    }else{
                                        $productos_entrega = array(3, 3, 4, 5, 6, 8);
                                        $add_nacional = 1;
                                    }
                                }
                                if ($add_nacional==1) {
                                    /** En caso de ser nacional */
                                    $fecha = strtotime($fecha."+ ".$productos_entrega[$value['productos_entrega_nacional']]." days");
                                }else{
                                    /** En caso de ser local */
                                    $fecha = strtotime($fecha."+ ".$productos_entrega[$value['productos_entrega_local']]." days");
                                }
                                
                                ?>
                                <span class="recuadro-retrasos relative-center"><?=timeago($fecha);?></span>
                            </td>
                            <td>
                                <a
                                <?php
                                if ($_SESSION['tipo_accesos']=="0" || $_SESSION['tipo_accesos']=="1") {
                                    echo 'href="'.base_url().'pedidos/editar_new/'.$value['pedidos_id'].'" onclick="ModalNotificacion(this);" pedidoid="'.$value['pedidos_id'].'" ';
                                }else{
                                    echo ' href="'.base_url().'pedidos/editar_new/'.$value['pedidos_id'].'"';
                                }
                                ?> 
                                class="btn btn-primary text-white relative-center">
                                    Gestionar
                                </a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        
                    </tbody>
                </table>
                <div class="col-12 text-center py-4">
                    <div class="paginado">
                        <ul class="list-inline">
                        <?php
                        if (isset($_GET['page']) && $_GET['page'] > 1) { 
                            ?>
                        <li>
                            <a href="<?=$urlActualPrevPage?>">Anterior</a>
                        </li>
                        <?php
                        }
                        if (isset($_GET['page'])) {
                            echo '<span class="btn rounded border mx-2">'.$_GET['page'].'</span>';
                        }else{
                            echo '<span class="btn rounded border mx-2">1</span>';
                        }
                        if ($pedidos->num_rows() == 12) { ?>
                        <li>
                            <a href="<?=$urlActualNextPage?>">Siguiente</a>
                        </li>
                        <?php  } ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <?php
            }
            if ($productos_agotados->num_rows() > 0) { ?>
            <div class="col-12 col-md-5 tabla-pedidos pt-0">
                <h3 class="mb-4">Productos Agotados</h3>
                <table class="table table-responsive px-0 pt-0">
                    <thead class="text-center">
                        <tr>
                            <th>
                                <p>Foto</p>
                            </th>
                            <th>
                                <p>Titulo</p>
                            </th>
                            <th>
                                <p>Acciones</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($productos_agotados->result_array() as $key => $value) { 
                            $img=delBackslashIni($value['medios_url']);
                            if(!file_exists($img)) $img='assets/img/Not-Image.png'; 
                        ?>
                        <tr class="text-center">
                            <td>
                                <img class="lazy" data-src="<?=$img.'?'.rand()?>" src="assets/img/loading/yellow_3.gif" alt="Image">
                            </td>
                            <td>
                                <?=$value['productos_titulo']?>
                            </td>
                            <td>
                                <a
                                href="<?=base_url()?>productos/editar/<?=$value['productos_id']?>/<?=$value['productos_slug']?>"
                                class="btn btn-primary text-white relative-center">
                                    Gestionar
                                </a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div class="col-12 text-center py-4">
                    <div class="paginado">
                        <ul class="list-inline">
                        <?php
                        if (isset($_GET['page_prod']) && $_GET['page_prod'] > 1) { 
                            ?>
                        <li>
                            <a href="<?=$urlActualPrevPage_prod?>">Anterior</a>
                        </li>
                        <?php
                        }
                        if (isset($_GET['page_prod'])) {
                            echo '<span class="btn rounded border mx-2">'.$_GET['page_prod'].'</span>';
                        }else{
                            echo '<span class="btn rounded border mx-2">1</span>';
                        }
                        if ($productos_agotados->num_rows() == 12) { ?>
                        <li>
                            <a href="<?=$urlActualNextPage_prod?>">Siguiente</a>
                        </li>
                        <?php  } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade ModalNotificacion" id="ModalNotificacion" tabindex="-1" role="dialog" aria-labelledby="ModalNotificacionTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalNotificacionLongTitle">Acciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="col-12">
                    <input type="hidden" value="" id="pedidos_id_notificacion" name="pedidos_id_notificacion">
                    <button id="btn_notificacion" class="btn btn-primary new mb-4" onclick="enviarNotificacion();loadedbutton(this);">Enviar notificación</button>
                </div>
                <div class="col-12">
                    <a id="btn-ir-pedido" class="btn btn-secondary new" href="#">Ir a pedido</a>
                </div>
            </div>
        </div>
    </div>
</div>