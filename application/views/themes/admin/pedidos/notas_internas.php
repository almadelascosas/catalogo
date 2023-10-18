<div class="chat-notas-pedidos w-100">
    <div class="cabecera col-12">
        <h3>Mensajes
            <a class="d-none" id="btn-reelegir_vendedores" onclick="reelegir_vendedores();" href="#cuerpo-mostrar-vendedores"> <span style="color:transparent;">____</span> Cambiar de vendedor</a>
        </h3>
    </div>
    
    <?php
    if ($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) { 
        ?>
        <div id="cuerpo-mostrar-vendedores" class="cuerpo-mostrar-vendedores text-center col-12">
            <div class="row">
                <div class="col-12">
                    <p>Selecciona el vendedor con quien deseas chatear.</p>
                </div>
                <?php
                for ($i=0; $i < count($notas_internas['notas_vendedores']); $i++) {
                    ?>
                <div class="col-md-4 col-12 my-4">
                    <a class="btn-mostrar-vendedor" onclick="mostrar_cuerpo_vendedor(<?=$notas_internas['notas_vendedores'][$i]['id']?>)" href="#cuerpo-vendedor-<?=$notas_internas['notas_vendedores'][$i]['id']?>">
                        <?=$notas_internas['notas_vendedores'][$i]['name']?>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        <?php
        for ($i=0; $i < count($notas_internas['notas_vendedores']); $i++) {
        ?>
        <div class="cuerpo d-none col-12" id="cuerpo-vendedor-<?=$notas_internas['notas_vendedores'][$i]['id']?>">
            <?php
            foreach ($notas_internas['notas'] as $key => $value) {
                if ($value['notas_pedidos_usuarios_id']==$_SESSION['usuarios_id']) {
                ?>
                <div class="mensaje-enviado <?php if ($value['notas_pedidos_tipo']==1) { echo "notificacion"; } ?> offset-4 col-8 mb-4">
                    <p class="autor-fecha">
                        <strong><?=$_SESSION['name']?></strong>
                        <span class="fecha"><?=$value['notas_pedidos_fecha_emitido']?></span>
                    </p>
                    <p class="mensaje">
                        <?=$value['notas_pedidos_mensaje']?>
                    </p>
                </div>
                <?php
                }elseif ($value['notas_pedidos_tipo']==1) { ?>
                    <div class="mensaje-recibido notificacion col-8 mb-4">
                        <p class="autor-fecha">
                            <strong><?=$value['name']?></strong>
                            <span class="fecha"><?=$value['notas_pedidos_fecha_emitido']?></span>
                        </p>
                        <p class="mensaje">
                        <?=$value['notas_pedidos_mensaje']?>
                        </p>
                    </div>
                <?php
                } elseif ($notas_internas['notas_vendedores'][$i]['id']==$value['notas_pedidos_usuarios_id']) {  
                    ?>
                <div class="mensaje-recibido <?php if ($value['notas_pedidos_tipo']==1) { echo "notificacion"; } ?> col-8 mb-4">
                    <p class="autor-fecha">
                        <strong><?=$value['name']?></strong>
                        <span class="fecha"><?=$value['notas_pedidos_fecha_emitido']?></span>
                    </p>
                    <p class="mensaje">
                    <?=$value['notas_pedidos_mensaje']?>
                    </p>
                </div>
                    <?php
                }
            } 
            ?>
        </div>
        <?php           
        }
        ?>
    <?php
    }else{
        ?>
    <div class="cuerpo d-block col-12">
        <?php
        foreach ($notas_internas['notas'] as $key => $value) {
            if ($value['notas_pedidos_usuarios_id']==$_SESSION['usuarios_id']) {
            ?>
            <div class="mensaje-enviado <?php if ($value['notas_pedidos_tipo']==1) { echo "notificacion"; } ?> offset-4 col-8 mb-4">
                <p class="autor-fecha">
                    <strong><?=$_SESSION['name']?></strong>
                    <span class="fecha"><?=$value['notas_pedidos_fecha_emitido']?></span>
                </p>
                <p class="mensaje">
                    <?=$value['notas_pedidos_mensaje']?>
                </p>
            </div>
            <?php
            }else{
                ?>
            <div class="mensaje-recibido <?php if ($value['notas_pedidos_tipo']==1) { echo "notificacion"; } ?> col-8 mb-4">
                <p class="autor-fecha">
                    <strong><?=$value['name']?></strong>
                    <span class="fecha"><?=$value['notas_pedidos_fecha_emitido']?></span>
                </p>
                <p class="mensaje">
                <?=$value['notas_pedidos_mensaje']?>
                </p>
            </div>
                <?php
            }
        }
        ?>
    </div>
    <?php
    }
    ?>

    <div class="pie-chat col-12 <?php if ($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) { echo "d-none"; } ?>" >
        <input type="hidden" name="notas_pedidos_pedido_id" value="<?=$notas_internas['notas_pedidos_id']?>">
        <input type="hidden" name="notas_pedidos_usuario_dirigido" value="0">
        <div class="row">
            <div class="<?php
            if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) {
                echo " col-6 ";
            } else {
                echo " col-9 ";
            }
            ?>">
                <textarea class="form-control mb-0 mt-4" name="notas_pedidos_mensaje" id="notas_pedidos_mensaje" rows="1"></textarea>
            </div>
            <?php
            if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
            <div class="col-3">
                <label class="w-100">
                    Tipo de mensaje
                    <select class="form-control mb-0" name="notas_pedidos_tipo" id="notas_pedidos_tipo">
                        <option value="0">Personal</option>              
                        <option value="1">Notificación</option>              
                    </select>
                </label>
            </div>
            <?php
            }
            ?>
            <div class="col-3">
                <button class="btn btn-basic btn-primary mt-4" id="btn-enviar-mensaje" onclick="enviarMensaje();" type="button">
                    Enviar nota
                </button>
            </div>
        </div>
    </div>
    
</div>