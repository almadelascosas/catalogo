<main class="cuerpo col-12 float-left">
    <div class="row">
        <div class="col-12 mb-5"></div>

        <div class="offset-md-1 col-md-3 offset-sm-1 col-sm-11 ">
            <div class="row">
                <div class="col-md-1 col-sm-1"></div>
                <?php $this->view('themes/modern/mi-cuenta/menu'); ?>
            </div>
        </div>
        
        <div class="col-md-7 col-11 miinfo">
            <div class="row">
                <div class="col-md-12 col-12">&nbsp;</div>
                <div class="offset-sm-1 col-10"><h3 class="rale">Mis compras</h3></div>
                <div class="offset-sm-1 col-10"><hr></div>
                <div class="col-12">&nbsp;</div>

                <div class="offset-sm-1 col-10 listado-orders">
                    <div class="row">
                        <?php
                        if ($pedidos!=NULL) {
                            foreach ($pedidos['data'] as $key => $value) { 
                                $img=delBackslashIni($value['pedidos_imagen_producto']);
                                if(!file_exists($img)) $img='assets/img/Not-Image.png';
                        ?>
                        <div class="card col-12 py-3">

                            <?php if (isMobile()) { ?>
                            <a href="mi-cuenta/ordersview/<?=$value['pedidos_id']?>" style="text-decoration: none;">
                            <?php } ?>

                                <div class="row">
                                    <div class="col-6 border-bottom">
                                        <h5><?=$value['pedidos_fecha']?></h5>
                                    </div>
                                    <div class="col-6 border-bottom text-right">
                                        <h6>Orden # <?=$value['pedidos_id']?></h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 col-md-2 image mt-3">
                                        <div class="w-100 h-100 d-table">
                                            <div class="d-table-cell align-middle">
                                                <img class="lazy" data-src="<?=$img.'?'.rand()?>" alt="Image" sizes="68x68" src="assets/img/loading/yellow_3.gif">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 col-md-7 mt-3">
                                        <div class="row" style="line-height: 2em;">
                                            <div class="col-12">
                                                <p class=" titulo-producto"><?=$value['pedidos_titulo_producto']?></p>
                                                <?=($value['pedidos_mas_productos'] != 0)?"<p>+ ".$value['pedidos_mas_productos']." productos</p>":""; ?>
                                            </div>
                                            <div class="col-12">
                                                <b style="color:black;">Total: <?=$value['pedidos_precio_total_seteado']?></b>         
                                            </div>
                                            <div class="col-12">
                                                <?php
                                                if($value['pedidos_estatus']=="Rechazado"){ 
                                                    echo '<span class="estatus-order cancelado">Rechazado</span>';
                                                }
                                                if($value['pedidos_estatus']=="En Preparación"){ 
                                                    echo '<span class="estatus-order preparacion">En Preparación</span>';
                                                }
                                                if($value['pedidos_estatus']=="Esperando confirmación de pago"){ 
                                                    echo '<span class="estatus-order espera">Esperando confirmación de Pago</span>';
                                                }
                                                if($value['pedidos_estatus']=="Enviado"){ 
                                                    echo '<span class="estatus-order enviado">Enviado</span>';
                                                }
                                                if($value['pedidos_estatus']=="Cancelado"){ 
                                                    echo '<span class="estatus-order cancelado">Cancelado</span>';
                                                }
                                                if($value['pedidos_estatus']=="Entregado"){ 
                                                    echo '<span class="estatus-order entregado">Entregado</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!isMobile()) { ?>
                                    <div class="col-1 col-md-3 text-right">
                                        <div class="centro-vertical">
                                            <a href="<?=base_url('mi-cuenta/ordersview/'.$value['pedidos_id']);?>" class="btn btn-green-alma"><i class="fa fa-map" aria-hidden="true"></i> Rastrear pedido</a>
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                            <?php if (isMobile()) { ?>
                            </a>
                            <?php } ?>

                        </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
</main>