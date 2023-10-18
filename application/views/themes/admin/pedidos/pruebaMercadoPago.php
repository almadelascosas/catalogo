<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Pedidos</span>
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
                                    12
                                    Items por página 
                                </label>
                            </div>                           
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <form action="<?=base_url()?>pedidos" method="get" class="col-12">
                            <div class="row">
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="w-100">
                                            Buscador
                                        </label>
                                        <?php
                                        $search="";
                                        if (isset($_GET['search'])) {
                                            $search=$_GET['search'];
                                        }
                                        ?>
                                        <input class="form-control" type="text" name="search" value="<?=$search?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="w-100" for="search-type">
                                            Buscar por:
                                        </label>
                                        <?php
                                        $searchType = "";
                                        if (isset($_REQUEST['search-type'])) {
                                            $searchType = $_REQUEST['search-type'];
                                        }
                                        ?>
                                        <select class="form-control" name="search-type" id="search-type">
                                            <option <?php if($searchType==0){ echo "selected"; } ?> value="0">Por Nro de Pedido</option>
                                            <option <?php if($searchType==1){ echo "selected"; } ?> value="1">Por Cliente</option>
                                            <?php
                                            if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                                            <option <?php if($searchType==2){ echo "selected"; } ?> value="2">Por Vendedor</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="w-100">
                                            --
                                        </label>
                                        <button type="submit" class="btn btn-success btn-sm">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="#" onclick="obtenerPedidosMercadoPago();">Obtener Pedidos de Mercado Pago</a>
                <div class="cajetin-pedidos">
                <div class="col-12 pedidos-top">
                        <ul class="list-inline">
                            <li>
                                <a class="mostrar_pedidos active" href="#pedidos_nuevos">Pedidos Nuevos</a>
                            </li>
                            <li>
                                <a class="mostrar_pedidos" href="#pedidos_antiguos">Pedidos Antiguos</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-12 listado card" id="pedidos_nuevos">
                        <div class=" table-responsive">

                        </div>
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
                                if (count($pedidos_new['pedidos']) == 12) { ?>
                                <li>
                                    <a href="<?=$urlActualNextPage?>">Siguiente</a>
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
</div>