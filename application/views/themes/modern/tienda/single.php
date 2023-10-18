<?php
use Doctrine\Common\Annotations\PhpParser;

$categorias_list = "";
$cont = 0;
$catComida=0;
foreach ($categorias->result_array() as $key => $value) {
    if(intval($value['categorias_id'])===155) $catComida++;
    $cont++;
    if ($cont == 1) {
        $categorias_list = $value['categorias_nombre'];
    } else{
        $categorias_list .= ",".$value['categorias_nombre'];
    }
}
if ($producto['productos_precio_oferta']=="" || $producto['productos_precio_oferta']<=0 || $producto['productos_precio_oferta'] > $producto['productos_precio']) {
    $precioProducto = $producto['productos_precio'];
}else{
    $precioProducto = $producto['productos_precio_oferta'];
}
?>
<script type="text/javascript">
    fbq('track', 'ViewContent', {content_ids:'<?=$producto['productos_id']?>',content_type:'product',content_name:'<?=$producto['productos_titulo']?>',currency:'COP',content_category:'<?=$categorias_list?>',google_product_category:'<?=$categorias_list?>'});
</script>
<main class="col-12 cuerpo float-left" onclick="void(0);">
    <div class="row" onclick="void(0);">
        <div class="col-md-10 offset-md-1 mb-5 card-single-product col-12 px-0" onclick="void(0);">
            <div class="col-12 pb-md-5 pt-md-3" onclick="void(0);">
                <div class="row" onclick="void(0);">
                    <div class="col-12 pt-1 py-md-2 pt-md-4 mb-md-4 breadcumbs">
                        <a class="btn btn-back" href="javascript:history.back();"><span class="icon-arrow-left2"></span></a>
                        <p class="controls-vistas">
                            
                            <?php if ($this->uri->segment(2)=="single" && $producto['productos_video']!="") { ?>
                            <a class="btn text-white bg-green-1 box-shadow-clasic" href="<?=base_url('tienda/reel/'.$producto['productos_id'].'/'.limpiarUri($producto['productos_titulo']))?>">
                                Vista clip
                            </a>
                            <?php } ?>
                        </p>

                    </div>
                </div>
                <div class="row" onclick="void(0);">
                    <div class="col-12 col-md-7 product-images">
                        <div class="row">
                            <div class="col-md-3 d-md-block d-none">
                                <div class="w-100 scroll-mini">
                                    <?php                                   
                                    if ($producto['productos_imagen_destacada']!=="" && $producto['productos_imagen_destacada']!==0){
                                        //print $producto['medios_enlace_miniatura'];
                                        $image=delBackslashIni($producto['medios_enlace_miniatura']);
                                        if(!file_exists($image)) $image=delBackslashIni($producto['medios_url']);
                                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                    ?>
                                    <div onclick="$('#dotp-0').click();" class="miniatura mb-4">
                                        <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="">
                                    </div>
                                    
                                    <?php
                                    }

                                    $cn2=1;
                                    foreach ($imagenes->result_array() as $key => $value) {
                                        $image=delBackslashIni($value['medios_enlace_miniatura']);
                                        if(!file_exists($image)) $image=delBackslashIni($value['medios_url']);
                                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                        ?>
                                    <div onclick="$('#dotp-<?=$cn2?>').click();" class="miniatura mb-4">
                                        <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="">
                                    </div>
                                    <?php
                                        $cn2++;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-9 pl-md-0 pr-md-4">
                                <div class="owl-carousel" id="carrusel-img-principal">
                                    <?php
                                    if ($producto['productos_imagen_destacada']!=="" && $producto['productos_imagen_destacada']!==0) {
                                        $image = delBackslashIni($producto['medios_url']); 
                                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                    ?>
                                    <div class="image-principal" id="image-principal">
                                        <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="">
                                    </div>
                                    <?php
                                    }

                                    foreach ($imagenes->result_array() as $key => $value) {
                                        $image = delBackslashIni($value['medios_url']); 
                                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                    ?>
                                    <div class="image-principal">
                                        <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="">
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <ul id="carousel-custom-dots" class="owl-dots d-none">
                            <?php 
                            if ($producto['productos_imagen_destacada']!=="" && $producto['productos_imagen_destacada']!==0) { ?>
                            <li id="dotp-0" class="owl-dot-2">0</li>
                            <?php 
                            }

                            $cn=1; 
                            foreach ($imagenes->result_array() as $key => $value) {    
                            ?>
                                <li id="dotp-<?=$cn?>" class="owl-dot-2"><?=$cn?></li>
                            <?php
                                $cn++;
                            }
                            ?>
                        </ul>
                        <div class="col-12 px-0">
                            <?php if (isMobile()) { ?>
                            <div class="owl-carousel" id="carrusel-miniproduct">
                                <?php                                
                                if ($producto['productos_imagen_destacada']!="" && $producto['productos_imagen_destacada']!=0) {
                                    $image = delBackslashIni($producto['medios_url']); 
                                    if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                ?>
                                <div onclick="$('#dotp-0').click();" class="miniatura">
                                    <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="">
                                </div>
                                <?php 
                                }  

                                $cn2=1;
                                foreach ($imagenes->result_array() as $key => $value) {
                                    $image = delBackslashIni($value['medios_url']); 
                                    if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                    
                                ?>
                                    <div onclick="$('#dotp-<?=$cn2?>').click();" class="miniatura" style="width:100%;">
                                        <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="">
                                    </div>
                                <?php
                                    $cn2++;     
                                }
                                ?>
                            </div>
                        <?php } ?>
                        </div>
                        <?php if (!isMobile()) { ?>
                        <div class="col-12 aditional-info d-md-block d-none py-5">
                            <div class="col-12 text-center mb-4">
                                <p class="selectores-single">
                                    <span onclick="selectInfo(this,'#descripcion-pr')" class="tag-descripcion bold mr-3">Descripción</span>
                                    <span onclick="selectInfo(this,'#atributos-pr')" class="bold">Información adicional</span>
                                </p>
                            </div>
                            <div class="seleccionados-pr" id="descripcion-pr"><?=$producto['productos_descripcion_larga']?></div>
                            <div class="seleccionados-pr d-none" id="atributos-pr">
                                Peso: <?=$producto['productos_peso']?> Kg.<br>
                                Dimesiones: <?php
                                $dimensiones = explode("/,/",$producto['productos_dimensiones']);
                                for ($i=0; $i < count($dimensiones); $i++) {
                                    if ($i==1) {
                                        echo $dimensiones[$i]."cm";
                                    }elseif ($i>1 && $i<4) {
                                        echo "x".$dimensiones[$i]."cm";
                                    }
                                }
                                ?>.<br>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <?php if (isMobile()) { ?>
                    <div class="col-12 product-info">
                        <h5 class="mb-0 py-3"><?=$producto['productos_titulo']?></h5>
                        <p><?=$producto['productos_descripcion_corta']?></p>
                        <?php
                        if ($producto['productos_precio_oferta']=="" || $producto['productos_precio_oferta'] <= 0) {
                            ?>
                            <h4 class="precio-info mb-0 py-3" id="h4-valor">$ <?=number_format($precioProducto, 0, ',', '.')?></h4>
                            <?php
                        }else{
                            ?>
                            <h4 class="precio-info mb-0 py-3" id="h4-valor">$ <?=number_format($precioProducto, 0, ',', '.')?> - <span class="tachado">$ <?=number_format($producto['productos_precio'], 0, ',', '.')?></span></h4>
                            <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-12 d-none" id="div-precio-extra">
                                <div class="row">
                                    <div class="col-6 precio-info mb-0 py-3">
                                        Subtotal:
                                    </div>
                                    <div class="col-6 precio-info mb-0 py-3">
                                        <span id="subtotal-producto"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-5 col-12" onclick="void(0);">
                        <div class="row" onclick="void(0);">
                            <?php
                            if ((isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']==9) || ($producto['productos_estado_inv']==1 && $producto['productos_estatus']==1)) {
                                if (!isMobile()) { ?>
                                <div class="col-12 product-info">
                                    <h2 class="mb-0 py-3"><?=$producto['productos_titulo']?></h2>
                                    <p><?=$producto['productos_descripcion_corta']?></p>
                                    <?php
                                    if ($producto['productos_precio_oferta']=="" || $producto['productos_precio_oferta'] <= 0) {
                                        ?>
                                        <h4 class="precio-info mb-0 py-3" id="h4-valor">$ <?=number_format($precioProducto, 0, ',', '.')?></h4>
                                        <?php
                                    }else{
                                        ?>
                                        <h4 class="precio-info mb-0 py-3" id="h4-valor">$ <?=number_format($precioProducto, 0, ',', '.')?> - <span class="tachado">$ <?=number_format($producto['productos_precio'], 0, ',', '.')?></span></h4>
                                        <?php
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-12 d-none" id="div-precio-extra">
                                            <div class="row">
                                                <div class="col-6 precio-info mb-0 py-3">
                                                    Subtotal:
                                                </div>
                                                <div class="col-6 precio-info mb-0 py-3">
                                                    <span id="subtotal-producto"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-12 px-0" id="caja-addons">
                                    <?php
                                    $conteo = -1;
                                    foreach ($addons_producto as $key => $value) {
                                        $conteo++; 
                                        if ($value['addons_titulo']!="") { ?>
                                        <div class="col-12 bg-light rounded p-4 mb-4 addons">
                                        <input type="hidden" name="addons_id[]" value="<?=$value['addons_id']?>">
                                        <input type="hidden" name="addons_tipo[]" value="<?=$value['addons_tipo']?>">
                                        <input type="hidden" name="addons_display[]" value="<?=$value['addons_display']?>">
                                        <input type="hidden" name="addons_titulo[]" value="<?=$value['addons_titulo']?>">
                                        <?php
                                        if ($value['addons_tipo']=="checkboxes") {
                                            if ($value['addons_opciones']!="/,/1/,/],[") {
                                                echo "<label class='w-100'><p class='bold mb-0'>".$value['addons_titulo']."</p></label>";
                                            }
                                        }else{
                                            echo "<label class='w-100'><p class='bold mb-0'>".$value['addons_titulo']."</p></label>";
                                        }
                                        if ($value['addons_tipo']=="short_text") { ?>
                                        <input onblur="aumentarCargo(this);" class="form-control addons-texto" <?php if ($value['addons_requerido']==1) {
                                            echo "required";
                                        } ?> type="text" name="addons_respuesta_pedido[]">
                                        <?php
                                        }elseif ($value['addons_tipo']=="long_text") { ?>
                                        <textarea onblur="aumentarCargo(this);" class="form-control addons-texto" <?php if ($value['addons_requerido']==1) {
                                            echo "required";
                                        } ?> name="addons_respuesta_pedido[]" rows="5"></textarea>
                                        <?php
                                        }elseif ($value['addons_tipo']=="multiple") {
                                            if ($value['addons_display']=="dropdowns") {
                                                $opciones = explode('],[',$value['addons_opciones']); 
                                                
                                                ?>
                                                <label class="w-100">
                                                    <select class="form-control select-addons" <?php if ($value['addons_requerido']==1) {
                                                                    echo "required";
                                                                } ?> onchange="aumentarCargo(this);" name="addons_respuesta_pedido[]" id="">
                                                        <option value="">Seleccionar...</option>
                                                        <?php
                                                        for ($i=0; $i < count($opciones)-1; $i++) { 
                                                            $dr_options = explode('/,/',$opciones[$i]);
                                                            if (isset($dr_options[0]) && $dr_options[0]!="") {
                                                            ?>
                                                                <option value="<?=$dr_options[0]?>/,/<?=$dr_options[2]?>">
                                                                <?php
                                                                echo $dr_options[0];
                                                                if ($dr_options[2]!="" && $dr_options[2]!=0) { ?>
                                                                - $ <?=number_format($dr_options[2], 0, ',', '.')?>
                                                                </option>
                                                            <?php
                                                            }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </label>
                                            <?php
                                            }else{
                                                $opciones = explode('],[',$value['addons_opciones']);
                                                ?>
                                                <label>
                                                    <input onchange="aumentarCargo(this);" required type="radio" name="addons_respuesta_pedido[]" value="">
                                                    Ninguno
                                                    <input type="hidden" name="addons_respuesta_cargo[]" value="0">
                                                    <input type="hidden" name="addons_respuesta_tipo_cargo[]" value="0">
                                                </label>
                                                <?php
                                                for ($i=0; $i < count($opciones)-1; $i++) { 
                                                    $dr_options = explode('/,/',$opciones[$i]);
                                                        if (isset($dr_options[0]) && $dr_options[0]!="") {
                                                    ?>
                                                    <label class="w-100">
                                                        <input onchange="aumentarCargo(this);" <?php if ($value['addons_requerido']==1) {
                                                            echo "required";
                                                        } ?> type="radio" name="addons_respuesta_pedido[]" value="<?=$dr_options[0]?>">
                                                        <?php
                                                        echo $dr_options[0];
                                                        if ($dr_options[2]!="" && $dr_options[2]!=0) { ?>
                                                        - $ <?=number_format($dr_options[2], 0, ',', '.')?>
                                                        <input type="hidden" name="addons_respuesta_cargo[]" value="<?=$dr_options[2]?>">
                                                        <input type="hidden" name="addons_respuesta_tipo_cargo[]" value="<?=$dr_options[1]?>">
                                                    </label>
                                                    <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }elseif ($value['addons_tipo']=="checkboxes") {
                                            $opciones = explode('],[',$value['addons_opciones']);
                                            for ($i=0; $i < count($opciones)-1; $i++) { 
                                                $dr_options = explode('/,/',$opciones[$i]);
                                                if (isset($dr_options[0]) && $dr_options[0]!="") {
                                                    ?>
                                                    <label class="w-100">
                                                        <input onchange="aumentarCargo(this);" type="checkbox" name="addons_respuesta_pedido[]" value="<?=$dr_options[0]?>">
                                                        <?php
                                                        echo $dr_options[0];
                                                        if ($dr_options[2]!="" && $dr_options[2]!=0) {
                                                            
                                                            ?>
                                                                - $ <?=number_format($dr_options[2], 0, ',', '.')?>
                                                            <input type="hidden" name="addons_respuesta_cargo[]" value="<?=$dr_options[2]?>">
                                                            <input type="hidden" name="addons_respuesta_tipo_cargo[]" value="<?=$dr_options[1]?>">
                                                        <?php
                                                        }
                                                        ?>
                                                    </label>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="col-12 my-3 mensaje-ubi">
                                    <p>
                                        Recibe este producto en <?php
                                        $productos_entrega_local = array(
                                            '',
                                            'Mismo día',
                                            'Recíbelo mañana',
                                            '1 - 2 días hábiles',
                                            '2 - 3 días hábiles',
                                            '3 - 4 días hábiles',
                                            '4 - 5 días hábiles',
                                            '5 - 7 días hábiles',
                                        );
                                        $productos_entrega_nacional = array(
                                            '',
                                            '1 - 2 días hábiles',
                                            '2 - 3 días hábiles',
                                            '3 - 4 días hábiles',
                                            '4 - 5 días hábiles',
                                            '5 - 7 días hábiles',
                                        );
                                        echo "<strong>".$productos_entrega_local[$producto['productos_entrega_local']]."</strong>";
                                        ?> 
                                        si estás en <strong><?=$producto['ubicaciones_texto']?></strong>
                                        <?php
                                        if ($producto['productos_envio_nacional']==1) { ?>
                                        o en <strong><?=$productos_entrega_nacional[$producto['productos_entrega_nacional']]?></strong> a otras ciudades
                                        <?php
                                        }
                                        ?>
                                    </p>
                                </div>

                                <?php if($catComida>0){ ?>
                                <div class="col-12 py-3">

                                    <button type="button" class="btn btn-outline-dark btnlineal" data-toggle="modal" data-target=".bd-example-modal-lg">Programar entrega (opcional) <i class="fa fa-calendar" aria-hidden="true"></i></button>
                                    <input type="hidden" name="fechaprogramada" id="fechaprogramada" value="">
                                    
                                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                          
                                            <div class="modal-header">
                                                <h5 class="modal-title">Fecha de entrega</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">                                                

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="elegant-calencar d-md-flex">
                                                            <div class="wrap-header d-flex align-items-center">
                                                                 <p id="reset">reset</p>
                                                                <div id="header" class="p-0">
                                                                    <div class="pre-button d-flex align-items-center justify-content-center"><i class="fa fa-chevron-left"></i></div>
                                                                    <div class="head-info">
                                                                        <div class="head-day"></div>
                                                                        <div class="head-month"></div>
                                                                    </div>
                                                                    <div class="next-button d-flex align-items-center justify-content-center"><i class="fa fa-chevron-right"></i></div>
                                                                </div>
                                                            </div>
                                                            <div class="calendar-wrap">
                                                                <table id="calendar">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Dom</th>
                                                                            <th>Lun</th>
                                                                            <th>Mar</th>
                                                                            <th>Mie</th>
                                                                            <th>Jue</th>
                                                                            <th>Vie</th>
                                                                            <th>Sab</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                          <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                            </div>

                                        </div>
                                      </div>
                                    </div>

                                </div>
                                <?php } ?>


                                
                                
                                <div class="col-12 py-3" onclick="void(0);">
                                    <div class="row" onclick="void(0);">
                                        <div class="col-4 pr-0">
                                            <div class="control-quanty d-table">
                                                <div class="d-table-cell align-middle">
                                                    <span onclick="restar('#quanty');" class="menos">-</span>
                                                    <input type="number" name="quanty" id="quanty" max="" min="1" value="1">
                                                    <span onclick="sumar('#quanty');" class="mas">+</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Precios --->
                                        <input type="hidden" id="productos_precio" value="<?=$precioProducto?>" name="productos_precio">
                                        <!-- Envios -->
                                        <input type="hidden" id="productos_envio_local" value="<?=$producto['productos_envio_local']?>" name="productos_envio_local">
                                        <input type="hidden" id="productos_valor_envio_local" value="<?=$producto['productos_valor_envio_local']?>" name="productos_valor_envio_local">
                                        <input type="hidden" id="productos_ubicaciones_envio" value="<?=$producto['productos_ubicaciones_envio']?>" name="productos_ubicaciones_envio">
                                        <input type="hidden" id="productos_envio_nacional" value="<?=$producto['productos_envio_nacional']?>" name="productos_envio_nacional">
                                        <input type="hidden" id="productos_valor_envio_nacional" value="<?=$producto['productos_valor_envio_nacional']?>" name="productos_valor_envio_nacional">
                                        <!--  -->
                                        <input type="hidden" id="productos_vendedor" value="<?=$producto['productos_vendedor']?>" name="productos_vendedor">
                                        <input type="hidden" id="productos_envio_nacional" value="<?=$producto['productos_envio_nacional']?>" name="productos_envio_nacional">
                                        <input type="hidden" id="precio-producto" value="<?=$precioProducto?>">
                                        <input type="hidden" id="addons-producto" value="">
                                        <!-- fbq('track', 'AddToCart', {content_ids:'<?=$producto['productos_id']?>',contents:[{'id': '<?=$producto['productos_id']?>', 'quantity': $('#quanty').val()}],content_type:'product',content_name:'<?=$producto['productos_titulo']?>',currency:'COP',value:'<?=$precioProducto?>',content_category:'<?=$categorias_list?>',google_product_category:'<?=$categorias_list?>'});" -->
                                        <div class="col-8" onclick="void(0);">
                                            <a id="btn-addcart" class="btn btn-addcart w-100 pt-3" 
                                            <?php if (isMobile()) { ?>
                                                ontouchend="addCartNew(<?=$producto['productos_id']?>,'#quanty','#productos_precio','#addons-producto'); fbq('track', 'AddToCart', {content_ids:'<?=$producto['productos_id']?>',contents:[{'id': '<?=$producto['productos_id']?>', 'quantity': $('#quanty').val()}],content_type:'product',content_name:'<?=$producto['productos_titulo']?>',currency:'COP',value:'<?=$precioProducto?>',content_category:'<?=$categorias_list?>',google_product_category:'<?=$categorias_list?>'});"
                                            <?php }else{ ?>
                                                onclick="addCartNew(<?=$producto['productos_id']?>,'#quanty','#productos_precio','#addons-producto'); fbq('track', 'AddToCart', {content_ids:'<?=$producto['productos_id']?>',contents:[{'id': '<?=$producto['productos_id']?>', 'quantity': $('#quanty').val()}],content_type:'product',content_name:'<?=$producto['productos_titulo']?>',currency:'COP',value:'<?=$precioProducto?>',content_category:'<?=$categorias_list?>',google_product_category:'<?=$categorias_list?>'});"
                                            <?php } ?>
                                            >COMPRAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <p class="my-3">Vendido por: <a class="bold" href="#"><?=$producto['vendedor_nombre']?></a></p>
                                    <?php
                                    $ubicaciones = explode("/",$producto['productos_ubicaciones_envio']);
                                    if ($producto['productos_envio_local']==1 && count($ubicaciones) > 0 && isset($producto['ubicaciones_texto'])) {
                                    ?>
                                    <p class="my-3">Ubicación: <span class="bold"><?=$producto['ubicaciones_texto']?></span></p>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($producto['productos_envio_local']==1) { ?>
                                    <p class="my-3">
                                        Valor de envío local:
                                        <?php
                                        if ($producto['productos_valor_envio_local'] > 0) {
                                            ?>
                                            <span class="bold color-verde-tienda">$ <?=number_format($producto['productos_valor_envio_local'], 0, ',', '.')?></span>
                                            <?php
                                        }else{
                                            ?>
                                            <span class="bold color-verde-tienda">Gratis</span>
                                            <?php
                                        }
                                        ?> 
                                    </p>
                                    <?php
                                    }
                                    if ($producto['productos_envio_nacional']==1) { ?>
                                    <p class="my-3">
                                        Valor de envío nacional:
                                        <?php
                                        if ($producto['productos_valor_envio_nacional'] > 0) {
                                            ?>
                                            <span class="bold color-verde-tienda">$ <?=number_format($producto['productos_valor_envio_nacional'], 0, ',', '.')?></span>
                                            <?php
                                        }else{
                                            ?>
                                            <span class="bold color-verde-tienda">Gratis</span>
                                            <?php
                                        }
                                        ?> 
                                    </p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <p class="mb-4 bold">Métodos de pago</p>
                                    <ul class="image-method-pay">
                                        <?php
                                        foreach ($metodos as $key => $value) { ?>
                                        <li><img src="<?=$value['metodo_imagen']?>" alt="Metodos de Pago"></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            <?php
                            }else{ ?>
                            <div class="col-12">
                                <p>Este producto estará disponible pronto, estás interesado?:</p>
                                <a class="btn btn-addcart w-100 py-3" style="font-size:15px;" href="https://api.whatsapp.com/send?phone=++573502045177&text=Hola Alma de las Cosas
    Quisiera recibir información sobre" rel="noopener noreferrer">Contactanos</a>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12 aditional-info py-3 d-md-none d-block">
                        <div class="col-12 text-center mb-4">
                            <div class="selectores-single">
                                <div class="col-12 py-2">
                                    <span onclick="selectInfo(this,'#descripcion-pr-mobile')" class="tag-descripcion bold mr-3">Descripción</span>
                                </div>
                                <div class="col-12 py-2">
                                    <span onclick="selectInfo(this,'#atributos-pr-mobile')" class="bold">Información adicional</span>
                                </div>
                            </div>
                        </div>
                        <div class="seleccionados-pr" id="descripcion-pr-mobile"><?=$producto['productos_descripcion_larga']?></div>
                        <div class="seleccionados-pr d-none" id="atributos-pr-mobile">
                            Peso: <?=$producto['productos_peso']?> Kg.<br>
                            Dimesiones: <?php
                            $dimensiones = explode("/,/",$producto['productos_dimensiones']);
                            for ($i=0; $i < count($dimensiones); $i++) {
                                if ($i==1) {
                                    echo $dimensiones[$i]."cm";
                                }elseif ($i>1 && $i<4) {
                                    echo "x".$dimensiones[$i]."cm";
                                }
                            }
                            ?>.<br>
                        </div>
                    </div>
                    <?php
                    if ($productos_relacionados->num_rows() > 0) { ?>

                    <div class="col-12 related-products py-3 px-4">
                        <div class="row">
                            <div class="col-12 px-2">
                                <h2 class="my-4">Productos relacionados</h2>
                            </div>
                            <div class="col-12">
                                <div class="owl-carousel carousel-productos">
                                <?php
                                $cont=0;
                                foreach ($productos_relacionados->result_array() as $key => $value) { ?>
                                    <div>
                                        <div class="products mb-5">
                                            <a href="<?=base_url('tienda/single/'.$value['productos_id'].'/'.$value['productos_slug'])?>">
                                                <div class="image">
                                                    <?php
                                                    if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) { ?>
                                                    <img class="d-none" loading="lazy" src="<?=$value['medios_enlace_miniatura']?>" <?php if (isset($value['medios_txt_alternativo']) && $value['medios_txt_alternativo']!="") {
                                                        echo 'alt="'.$value['medios_txt_alternativo'].'"';
                                                    }else{
                                                        echo 'alt="'.$value['medios_titulo'].'"';
                                                    } ?> srcset="<?=base_url().$value['medios_enlace_miniatura']?>">
                                                    <?php
                                                    }else{ ?>
                                                        <img class="d-none" loading="lazy" src="<?=base_url()?>assets/img/Not-Image.png" alt="Not image" srcset="<?=base_url()?>assets/img/Not-Image.png">
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="info">
                                                    <p class="mt-2 mb-0"><?=$value['productos_titulo']?></p>
                                                    <p class="my-0 p-vendedor"><?=$value['name']." ".$value['lastname']?></p>
                                                    <p class="price mb-2 mt-0">$ <?=number_format($precioProducto, 0, ',', '.')?></p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php
                                } 
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    } 
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
</main>
<div itemscope itemtype="http://schema.org/Product">
  <meta itemprop="brand" content="<?=$categorias_list?>">
  <meta itemprop="name" content="<?=$producto['productos_titulo']?>">
  <meta itemprop="description" content="<?=$producto['productos_descripcion_corta']?>">
  <meta itemprop="productID" content="<?=$producto['productos_id']?>">
  <meta itemprop="url" content="https://<?=$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]?>">
  <meta itemprop="image" content="<?=image($producto['medios_url'])?>">
  <div itemprop="value" itemscope itemtype="http://schema.org/PropertyValue">
    <span itemprop="propertyID" content="<?=$producto['productos_id']?>"></span>
    <meta itemprop="value" content="<?=number_format($precioProducto, 2, '.', '')?>"></meta>
  </div>
  <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <link itemprop="availability" href="http://schema.org/InStock">
    <link itemprop="itemCondition" href="http://schema.org/NewCondition">
    <meta itemprop="price" content="<?=number_format($precioProducto, 2, '.', '')?>">
    <meta itemprop="priceCurrency" content="COP">
  </div>
</div>