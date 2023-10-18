<main class="cuerpo col-12 float-left">
    <div class="row">
        <div class="col-12 mb-5"></div>
        <?php if(isset($_SESSION['name'])){ ?>
        <div class="offset-md-1 col-md-3 offset-sm-1 col-sm-11 ">
            <div class="row">
                <div class="col-md-1 col-sm-1"></div>
                <?php $this->view('themes/modern/mi-cuenta/menu'); ?>
            </div>
        </div>
        <?php } ?>
        
        <div class="col-md-7 col-11 miinfo " <?=(!isset($_SESSION['name']))?'style="margin:0 auto !important;"':''?> >
            <div class="row">
                <div class="col-md-12 col-12">&nbsp;</div>
                <div class="offset-md-1 col-10">
                    <div class="row">
                        <div class="col-md-9 col-12">
                            <h2 class="mb-0">Orden # <?=$pedido['data']['pedidos_id']?></h2>
                        </div>
                        <div class="col-3 d-none d-sm-none d-md-block">
                            <a class="btn btn-contact bg-success" target="_blank" href="https://api.whatsapp.com/send?phone=+573052633650&text=Hola Alma de las Cosas! Necesito ayuda con mi pedido"><span class="icon-whatsapp mr-1"></span> ¿Necesitas ayuda?</a>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="offset-md-1 col-10 mt-4 listado-productos-orders">
                    <?php
                    $cont = 1;
                    foreach ($pedido['data']['productos'] as $key => $value) {
                    ?>
                        <div class="card mb-4 col-12 py-3" data-toggle="collapse" href="#collapse-producto-<?=$cont?>" role="button" aria-expanded="false" aria-controls="collapse-producto-<?=$cont?>">
                            <div class="row">
                                <div class="col-4 col-md-2 image mt-3">
                                    <div class="w-100 h-100 d-table">
                                        <div class="d-table-cell align-middle">
                                            <img src="<?=$value['productos_imagen']?>" alt="Image" sizes="68x68" srcset="<?=$value['productos_imagen']?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8 col-md-10 mt-3">
                                    <div class="w-100 h-100 d-table">
                                        <div class="d-table-cell align-middle">
                                            <p class="mb-2"><?=$value['productos_titulo']?></p>
                                            <p><strong><?=$value['productos_precio_seteado']?></strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 collapse" id="collapse-producto-<?=$cont?>">
                                    <div class="row">
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <ul class="listado-estatus pl-md-5">
                                                <?php  if ($value['productos_estatus']['fecha_reembolsado']!="") {  ?>
                                                <li class="pb-0">
                                                    <span class="number">1</span>
                                                    <p><strong>Pedido Cancelado</strong></p>
                                                    <?php
                                                    if ($value['productos_estatus']['fecha_reembolsado']!="") { ?>
                                                    <p class="light"><span class="fecha"><?=$value['productos_estatus']['fecha_reembolsado']?></span></p>
                                                    <?php
                                                    }
                                                    ?>
                                                </li>
                                                <?php } else{ ?>                                                    
                                                <li class="<?php if ($value['productos_estatus']['fecha_confirmado']=="") { echo "disabled"; } ?>">
                                                    <span class="number">1</span>
                                                    <p><strong>Pedido Recibido</strong></p>
                                                    <?php
                                                    if ($value['productos_estatus']['fecha_confirmado']!="") { ?>
                                                    <p class="light"><span class="fecha"><?=$value['productos_estatus']['fecha_confirmado']?></span></p>
                                                    <?php
                                                    }
                                                    ?>
                                                </li>
                                                <li class="<?php if ($value['productos_estatus']['fecha_preparacion']=="") { echo "disabled"; } ?>">
                                                    <span class="number">2</span>
                                                    <p><strong>Pedido en preparación</strong></p>
                                                    <?php
                                                    if ($value['productos_estatus']['fecha_preparacion']!="") { ?>
                                                    <p class="light"><span class="fecha"><?=$value['productos_estatus']['fecha_preparacion']?></span></p>
                                                    <?php
                                                    }
                                                    ?>
                                                </li>
                                                <li class="<?php if ($value['productos_estatus']['fecha_enviado']=="") { echo "disabled"; } ?>">
                                                    <span class="number">3</span>
                                                    <p><strong>Pedido enviado</strong></p>
                                                    <?php
                                                    if ($value['productos_estatus']['fecha_enviado']!="") { ?>
                                                    <p class="light"><span class="fecha"><?=$value['productos_estatus']['fecha_enviado']?></span></p>
                                                    <?php
                                                    }
                                                    ?>
                                                </li>
                                                <!--<li class="<?php if ($value['productos_estatus']['fecha_entregado']=="") { echo "disabled"; } ?>">
                                                    <span class="number">4</span>
                                                    <p><strong>Pedido entregado</strong></p>
                                                    <?php
                                                    if ($value['productos_estatus']['fecha_entregado']!="") { ?>
                                                    <p class="light"><span class="fecha"><?=$value['productos_estatus']['fecha_entregado']?></span></p>
                                                    <?php
                                                    }
                                                    ?>
                                                </li>-->
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>

                                        <?php if($value['productos_estatus']['nro_guia']!==''){ ?>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-5 col-6"><b>No. de Guia:</b></div>
                                                <div class="col-md-7 col-6"><span style="color:#F4B127; font-weight: bold;"><?=$value['productos_estatus']['nro_guia']?></span></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 col-6"><b>Transportadora:</b></div>
                                                <div class="col-md-7 col-6"><?=$value['productos_estatus']['empresa']?></div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <!--  
                                            <div class="row">
                                                <div class="col-4"><b>No. de guia:</b></div>
                                                <div class="col-8" style="color:orange"><b>9999999999</b></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4"><b>Empresa:</b></div>
                                                <div class="col-8">Coordinadora</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4"><b>Entrega estimada:</b></div>
                                                <div class="col-8"><?=date('d-m-Y')?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12"><hr></div>
                                            </div>
                                            -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <b>Dirección:</b><br>
                                                    <b><?=$pedido['data']['pedidos_nombre_cliente']?></b><br>
                                                    <?=$pedido['data']['pedidos_direccion']?><br>
                                                    <?php //print $pedido['data']['pedidos_barrio']?><br>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 <?=($value['productos_estatus']['fecha_reembolsado']!=="")?"d-none":"";?>">
                                            <div class="row">
                                                <!--  
                                                <div class="col-6">
                                                    <p><strong>No. de guía:</strong></p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="color-primary"><?php //print $value['productos_estatus']['nro_guia']?></p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>Empresa:</strong></p>
                                                </div>
                                                <div class="col-6">
                                                    <p><?php //print $value['productos_estatus']['empresa']?></p>
                                                </div>
                                                -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $cont++;
                    }
                    ?>
                </div>

                <div class="offset-md-1 col-10 mt-4" style="margin-bottom: 100px;">
                    <p>Dirección:</p>
                    <p>
                        <strong><?=$pedido['data']['pedidos_nombre_cliente']?></strong><br>
                        <span><?=$pedido['data']['pedidos_direccion']?></span>
                        <span><?=$pedido['data']['pedidos_municipio']?>, <?=$pedido['data']['pedidos_departamento']?>, Colombia.</span>
                    </p>
                    <hr>                        
                </div>
                <div class="col-12 d-block d-sm-block d-md-none">
                    <a class="btn btn-contact bg-success" target="_blank" href="https://api.whatsapp.com/send?phone=+573052633650&text=Hola Alma de las Cosas! Necesito ayuda con mi pedido"><span class="icon-whatsapp mr-1"></span> ¿Necesitas ayuda?</a>
                </div>

            </div>
        </div>
    </div>
</main>
            