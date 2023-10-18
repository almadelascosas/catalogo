<script>
    var idusu = <?=$_SESSION['usuarios_id']?>;
    var url_solicitar_retiro = "<?=base_url()?>balance/solicitar_retiro";
    var url_confirmar_retiro = "<?=base_url()?>balance/confirmar_retiro";
</script>
<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <?php
            if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
            <span class="wcfm-page-heading-text">Solicitudes de retirada</span>
            <?php
            }else{ ?>
            <span class="wcfm-page-heading-text">Historial de pagos</span>
            <?php
            }
            ?>
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
            <!--<div class="wcfm-container simple variable external grouped booking">
            </div>-->
                <?php
                if ($_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1) {
                    $saldo = 0;
                    foreach ($balances->result_array() as $key => $value) {
                        if ($value['balance_pagos_tipo']=="credito") {
                            $saldo = $saldo+$value['balance_pagos_valor'];
                        }elseif ($value['balance_pagos_tipo']=="debito" && $value['balance_pagos_estatus']!="rechazado") {
                            $saldo = $saldo-$value['balance_pagos_valor'];
                        }else{
                            $saldo = $saldo-$value['balance_pagos_valor'];
                        }
                    } 
                    ?>
                    <script>
                        var cantidad_maxima = <?=$saldo?>;
                    </script>
                <div class="wcfm-collapse-content mb-4">
                    <p class="px-40">Total disponible: <span class="pl-20 color-yellow max-number">$<?=number_format($saldo, 0, ',', '.')?></span></p>
                    <div class="wcfm-container p-0 controls-balance">
                        <a href="#" onclick="show_hide_element('.caja-tabla','#balance',this)" class="btn active">Balance</a>
                        <a href="#" onclick="show_hide_element('.caja-tabla','#retirada',this)" class="btn">Solicitar retirada</a>
                    </div>
                </div>
                <?php
                }
                ?>
            <div class="wcfm-container pt-4 caja-tabla" id="balance">
                <div class="wcfm-content">
                    <table class="display tabla-saldos" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Valor</th>
                                <?php
                                if ($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) { ?>
                                <th>Vendedor</th>
                                <th>Acción</th>
                                <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody id="tabla-saldos">
                            <?php
                                if ($balances->num_rows()==0) { ?>
                            <tr>
                                <td colspan="10" style="text-align:center!important;">
                                    No hay solicitudes registradas.
                                </td>
                            </tr>
                            <?php
                                }else{
                                    foreach ($balances->result_array() as $key => $value) {
                                        if (($_SESSION['tipo_accesos']!=1 && $_SESSION['tipo_accesos']!=0) && $value['balance_pagos_estatus']!="pendiente" && $value['balance_pagos_estatus']!="rechazado") { ?>
                                        <tr>
                                            <td style="width:210px;">
                                                <?=$value['balance_pagos_fecha']?>
                                            </td>
                                            <td>
                                                <?=$value['balance_pagos_concepto']?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($value['balance_pagos_tipo']=="credito") {
                                                    echo "<span class='saldo pos'>$".number_format($value['balance_pagos_valor'], 0, ',', '.')."</span>";
                                                }else{
                                                    echo "<span class='saldo neg'>-$".number_format($value['balance_pagos_valor'], 0, ',', '.')."</span>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        } elseif($value["balance_pagos_estatus"]=="aprobado" && ($_SESSION['tipo_accesos']!=1 && $_SESSION['tipo_accesos']!=0)){ ?>
                                            <tr>
                                                <td style="width:210px;">
                                                    <?=$value['balance_pagos_fecha']?>
                                                </td>
                                                <td>
                                                    <?=$value['balance_pagos_concepto']?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($value['balance_pagos_tipo']=="credito") {
                                                        echo "<span class='saldo pos'>$".number_format($value['balance_pagos_valor'], 0, ',', '.')."</span>";
                                                    }else{
                                                        echo "<span class='saldo neg'>-$".number_format($value['balance_pagos_valor'], 0, ',', '.')."</span>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }elseif (($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) && $value['balance_pagos_tipo']=="debito" ) { ?>
                                            <tr>
                                                <td style="width:210px;">
                                                    <?=$value['balance_pagos_fecha']?>
                                                </td>
                                                <td>
                                                    <?=$value['balance_pagos_concepto']?>
                                                </td>
                                                <td>
                                                    <?php
                                                        echo "<span class='saldo pos'>$".number_format($value['balance_pagos_valor'], 0, ',', '.')."</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?=$value['name']." ".$value['lastname']?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($value['balance_pagos_estatus']=="pendiente") { ?>
                                                    <button class="btn btn-success text-white btn-sm" onclick="aprobarRetiro('<?=$value['name']." ".$value['lastname']?>','<?=number_format($value['balance_pagos_valor'], 0, ',', '.')?>',<?=$value['balance_pagos_id']?>)" type="button">Aprobar</button>
                                                    <?php
                                                    }elseif ($value['balance_pagos_estatus']=="aprobado" || $value['balance_pagos_estatus']=="rechazado") { ?>
                                                    <button class="btn btn-warning text-white btn-sm" type="button">Ver</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                            <?php
                                    } 
                            ?>
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <a href="#" class="btn next">Siguiente</a>
                    </div>
                </div>
            </div>
            <div class="wcfm-container pt-4 caja-tabla" id="retirada" style="display:none;">
                <div class="col-12 control-solicitar p-40">
                    <label for="">Cuánto deseas retirar:</label>
                    <input type="text" class="input-default" name="valor-retiro" id="valor-retiro" max="0" value="">
                    <button type="button" id="button-1" onclick="solicitarRetiro();" class="btn normal-default" name="button">Solicitar</button>
                    <button type="button" class="btn normal-default" style="display:none;" id="button-2" name="button">Solicitando...</button>
                    <button type="button" class="btn normal-default" style="display:none;" id="button-3" name="button">Enviado <img draggable="false" role="img" class="emoji" alt="✔" src="https://s.w.org/images/core/emoji/13.1.0/svg/2714.svg"></button>
                </div>
                <table class="display tabla-saldos" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($balances->num_rows()==0) { ?>
                        <tr>
                            <td colspan="10" style="text-align:center!important;">
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                        <?php
                        }else{
                            $count = 0;
                            foreach ($balances->result_array() as $key => $value) {
                                if ($value['balance_pagos_tipo']=="debito") {
                                    $count++;
                                ?>
                        <tr>
                            <td style="width:210px;">
                                <?=$value['balance_pagos_fecha']?>
                            </td>
                            <td>
                                $<?=number_format($value['balance_pagos_valor'], 0, ',', '.')?>
                            </td>
                            <td>
                                <?php
                                if ($value['balance_pagos_estatus']=="aprobado") {
                                    echo "<span class='status pos'>Lista</span>";
                                }elseif ($value['balance_pagos_estatus']=="rechazado") {
                                    echo "<span class='status rej'>Rechazado</span>";
                                }else{
                                    echo "<span class='status pen'>Pendiente</span>";
                                }
                                ?>
                            </td>
                            
                            <td>
                                <?=$value['balance_pagos_nota']?>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                            if ($count==0) { ?>
                        <tr>
                            <td colspan="10" style="text-align:center!important;">
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                            <?php
                            } 
                        }
                        ?>
                    </tbody>
                </table>
                <div class="text-right">
                    <a href="#" class="btn next">Siguiente</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ModalRetiro" tabindex="-1" role="dialog" aria-labelledby="ModalRetiroLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        
            <div class="col-12 caja-tabla p-5">
                <div class="form-group">
                    <p>Pagar a: <span id="pagar_a_p"></span></p>
                    <p>Valor Solicitado: <span id="valor_solicitado_p"></span></p>
                    <input type="hidden" id="balance_id" value="">
                </div>
                <div class="form-group">
                    <label for="nro_guia">Nota:</label>
                    <textarea class="form-control" name="balance_pagos_nota" id="balance_pagos_nota" rows="5"></textarea>
                </div>
                <div class="col-12">
                    <p class="text-left">
                        <button data-dismiss="modal" class="btn btn-secondary" type="button">
                            Cancelar
                        </button>
                        <button onclick="confirmarRetiro();" class="btn btn-default" type="button">
                            Enviar
                        </button>
                    </p>
                </div>
            </div>
        
    </div>
</div>