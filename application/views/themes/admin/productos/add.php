<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text"><?=($producto['productos_id']=="" || $producto['productos_id']==0 || $producto['productos_id']==NULL)?'Añadir':'Actualizar' ?> Producto</span>
            
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                    <i class="wcfmfa icon-bell"></i>
                    <span class="unread_notification_count message_count">0</span>
                    <div class="notification-ring"></div>
                </a>
            </div>
        </div>
        <form id="form-producto" class="col-12 my-5 contact-form" action="<?=base_url('productos/guardar')?>" method="post">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="col-12">
                    <div class="row">
                        <input type="hidden" name="productos_fecha_creacion" id="productos_fecha_creacion" value="<?=$producto["productos_fecha_creacion"]?>">
                        <input type="hidden" name="productos_id" id="productos_id" value="<?=$producto['productos_id']?>">
                        <div class="col-12 col-md-9">
                            <?php if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                            <div class="form-group">
                                <label>
                                    Estado del producto:
                                    <select class="form-control" name="productos_estatus" id="productos_estatus">
                                        <option <?php if($producto['productos_estatus']==0){ echo "selected" ; } ?> value="0">Borrador</option>
                                        <option <?php if($producto['productos_estatus']==1){ echo "selected" ; } ?> value="1">Público</option>
                                    </select>
                                </label>
                            </div>
                            <?php
                            }
                            ?>
                            <div class="form-group">
                                <select class="form-control" name="productos_tipo_producto" id="productos_tipo_producto">
                                    <option <?php if($producto['productos_tipo_producto']==1){ echo "selected" ; } ?> value="1">Producto Simple</option>
                                    <option <?php if($producto['productos_tipo_producto']==2){ echo "selected" ; } ?> value="2">Producto Variable</option>
                                    <option <?php if($producto['productos_tipo_producto']==3){ echo "selected" ; } ?> value="3">Producto Agrupado</option>
                                    <option <?php if($producto['productos_tipo_producto']==4){ echo "selected" ; } ?> value="4">Producto Externo / Afiliado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input <?php if(strrpos("Virtual",$producto['productos_tipo_presentacion'])!==false){ echo "checked" ; } ?> type="checkbox" name="productos_tipo_presentacion[]" id="productos_tipo_presentacion-1" value="Virtual">
                                    Virtual
                                </label>
                                <label>
                                    <input <?php if(strrpos("Descargable",$producto['productos_tipo_presentacion'])!==false){ echo "checked" ; } ?> type="checkbox" name="productos_tipo_presentacion[]" id="productos_tipo_presentacion-2" value="Descargable">
                                    Descargable
                                </label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="productos_titulo" name="productos_titulo" value="<?=$producto['productos_titulo']?>" placeholder="Título del producto">
                            </div>
                            <div class="form-group col-12 p-0">
                                <div class="row precios">
                                    <div class="col-md-6 col-12">
                                        <label for="productos_precio">Precio ($)<sup class="text-danger">*</sup></label>
                                        <input type="number" min="0" required class="form-control" name="productos_precio" id="productos_precio" value="<?=$producto['productos_precio']?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="productos_precio_oferta">Precio de la oferta ($)<sup class="text-danger">*</sup></label>
                                        <input type="number" min="0" class="form-control" name="productos_precio_oferta" id="productos_precio_oferta" value="<?=$producto['productos_precio_oferta']?>">
                                    </div>
                                    <div class="col-12 text-right">
                                        <a data-toggle="collapse" href="#col-prog" role="button" aria-expanded="false" aria-controls="col-prog">programar</a>
                                    </div>
                                    <div class="col-12 collapse" id="col-prog">
                                        <div class="row">
                                            <div class="col-md-6 col-12 ">
                                                <label for="productos_programacion-1">Desde</label>
                                                <input type="date" class="form-control" name="productos_programacion[]" id="productos_programacion-1" value="">
                                            </div>
                                            <div class="col-md-6 col-12 ">
                                                <label for="productos_programacion">Hasta</label>
                                                <input type="date" class="form-control" name="productos_programacion[]" id="productos_programacion-2" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="productos_descripcion_corta">Descripción Corta:</label>
                                <textarea name="productos_descripcion_corta" id="productos_descripcion_corta" class="form-control" rows="6"><?=$producto['productos_descripcion_corta']?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="productos_descripcion_larga">Descripción:</label>
                                <textarea name="productos_descripcion_larga" id="productos_descripcion_larga" class="form-control" rows="6"><?=$producto['productos_descripcion_larga']?></textarea>
                            </div>
                            <script>
                                var cargaMuni = <?=($producto['productos_departamento']!=0 || $producto['productos_departamento']=="")?0:1;?>
                            </script>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="row">
                                <div class="col-12 text-center galeria-prod">
                                    <div class="row">
                                        <div class="col-12 card-gallery border rounded mb-2">
                                            <?php
                                            $image='assets/uploads/Placeholder.png';
                                            if ($producto['productos_imagen_destacada']!="" && $producto['productos_imagen_destacada']!=0 && $imagen_destacada!="") {
                                                $image = delBackslashIni($imagen_destacada['medios_url']);
                                                if(!file_exists($image)) $image='assets/uploads/Placeholder.png';
                                            }
                                            ?>
                                            <img class="principal" onclick="galeria(this);" src="<?=$image.'?'.rand()?>" alt="Placeholder">
                                            <input type="hidden" id="productos_imagen_destacada" name="productos_imagen_destacada" value="<?=$producto['productos_imagen_destacada']?>">
                                        </div>
                                        <div class="d-none" id="ejemplomini">
                                            <div class="col-6 card-gallery border rounded mb-2">
                                                <img onclick="galeriaMini(this);" src="assets/uploads/Placeholder.png" alt="Placeholder">
                                                <div class="input-img">
                                                </div>
                                                <div class="w-100 controls-gallery">
                                                    <span onclick="$(this).parent().parent().remove()">x</span>
                                                    <span onclick="crearElemento('#ejemplomini','#appendMini');">+</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="row" id="appendMini">
                                                <?php
                                                if(isset($imagenes)){
                                                    $cant=count($imagenes->result_array());
                                                    $ultimo=$cant-1;

                                                    foreach ($imagenes->result_array() as $key => $value) {
                                                        $imagenOriginal=$value["medios_url"];
                                                        $imagen = delBackslashIni($value["medios_enlace_miniatura"]);
                                                        if(!file_exists($imagen)) $imagen = delBackslashIni($value["medios_url"]);
                                                        if(!file_exists($imagen)) $imagen='assets/img/Not-Image.png';
                                                        ?>
                                                        <div class="col-6 card-gallery border rounded mb-2">
                                                            <img onclick="galeriaMini(this);" class="lazy" data-src="<?=$imagen.'?'.rand()?>" src="assets/img/loading/yellow_3.gif" alt="Imagen ID: <?=$value['medios_id']?>">
                                                            <div class="input-img">
                                                                <input type="hidden" name="productos_imagenes[]" value="<?=$value['medios_id']?>">
                                                            </div>
                                                            <div class="w-100 controls-gallery">
                                                                <span onclick="$(this).parent().parent().remove()">x</span>
                                                                <?php if(intval($ultimo)===intval($key)){ ?>
                                                                <span onclick="crearElemento('#ejemplomini','#appendMini');">+</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                }
                                                ?>

                                                <div class="col-6 card-gallery border rounded mb-2">
                                                    <img onclick="galeriaMini(this);" src="assets/uploads/Placeholder.png" alt="agregar nueva imagen">
                                                    <div class="input-img"></div>
                                                    <div class="w-100 controls-gallery">
                                                        <span onclick="$(this).parent().parent().remove()">x</span>
                                                        <span onclick="crearElemento('#ejemplomini','#appendMini');">+</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card rounded w-100 mb-2 video-prod">
                                    <p class="m-0 p-2">Vídeo:</p>
                                    <input onchange="subir_video=1;" type="file" accept="video/*" name="productos_video_upload" id="productos_video_upload" data-default-file="<?php echo base_url().$producto['productos_video'] ?>" data-max-file-size="" class="dropify" value="<?php echo $producto['productos_video'] ?>">
                                    <input type="hidden" name="productos_video" id="productos_video" value="<?=$producto['productos_video'];?>">
                                </div>
                                <div class="card rounded w-100 mb-2">
                                    <div class="card-header">
                                        <h6 class="m-0">Categorías <sup class="text-danger">*</sup></h6>
                                    </div>
                                    <div class="card-body">
                                        <?php

                                        $categorias_seleccionadas = explode("/,/",$producto['productos_categorias']);
                                        $cate = array();
                                        $categorias_array = $categorias->result_array();

                                        foreach ($categorias->result_array() as $key => $value) {
                                            $hijos = array();
                                            foreach ($categorias->result_array() as $key2 => $value2) {
                                                if ($value2['categorias_padre']==$value['categorias_id']) {
                                                    array_push($hijos, $value2);
                                                }
                                            }
                                            if ($value['categorias_padre']==0 || $value['categorias_padre']==NULL || $value['categorias_padre']=="") {
                                                array_push($cate, array("padre" => $value, "hijos" => $hijos));
                                            }
                                        }
                                        $conteo=0;
                                        foreach($cate as $value){
                                            $conteo++;
                                            ?>
                                        <div class="form-group">
                                            <?php if(count($value['hijos'])>0){ ?>
                                            <a data-toggle="collapse" href="#cate-<?=$value['padre']['categorias_id']?>" role="button" aria-expanded="false" aria-controls="cate-<?=$value['padre']['categorias_id']?>" class="link-categoria"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></a>
                                            <?php }else{ print '<a class="divvacio">&nbsp;</a>'; } ?>
                                            <label for="">
                                                <input type="checkbox" <?php if (in_array($value['padre']['categorias_id'], $categorias_seleccionadas)){ echo "checked" ; } ?> value="<?=$value['padre']['categorias_id']?>" data-conteo="<?=$conteo?>" name="productos_categorias[]" id="categorias_padre-<?=$conteo?>" <?=(isset($_SESSION['tipo_accesos']) && intval($_SESSION['tipo_accesos'])===8)?'class="checkpadre"':''?> > <?=$value['padre']['categorias_nombre']?>
                                            </label>
                                            <div class="col-12 collapse div-subcategoria" id="cate-<?=$value['padre']['categorias_id']?>">
                                                <?php
                                                    if(count($value['hijos'])>0) print '<b>Subcategorias:</b><br>';
                                                    $conteo2=0;
                                                    foreach ($value['hijos'] as $key) {
                                                        $conteo2++;
                                                ?>
                                                <br>
                                                <label for="">
                                                    <input type="checkbox" <?php if (in_array($key['categorias_id'], $categorias_seleccionadas)){ echo "checked" ; } ?> value="<?=$key['categorias_id']?>" name="productos_categorias[]" id="categorias_hijo-<?=$conteo?><?=$conteo2?>" data-catpadre="<?=$conteo?>" <?=(isset($_SESSION['tipo_accesos']) && intval($_SESSION['tipo_accesos'])===8)?'class="checkhijo"':''?>> <?=$key['categorias_nombre']?>
                                                </label>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="productos_visibilidad">Visibilidad catálogo</label>
                                    <select class="form-control" name="productos_visibilidad" id="productos_visibilidad">
                                        <option <?php if($producto['productos_visibilidad']==1){ echo "selected" ; } ?> value="1">En la tienda y en los resultados de busqueda</option>
                                        <option <?php if($producto['productos_visibilidad']==2){ echo "selected" ; } ?> value="2">Solo en la tienda</option>
                                        <option <?php if($producto['productos_visibilidad']==3){ echo "selected" ; } ?> value="3">Solo en los resultados de busqueda</option>
                                        <option <?php if($producto['productos_visibilidad']==4){ echo "selected" ; } ?> value="4">Oculto</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wcfm-container simple variable external grouped booking mt-4">
                <div class="col-12">
                    <div class="row">
                        <div class="nav flex-column nav-pills col-md-3 col-12" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-inventario-tab" data-toggle="pill" href="#inventario-tab" role="tab" aria-controls="inventario-tab" aria-selected="true">Inventario</a>
                            <a class="nav-link" id="v-seo-tab" data-toggle="pill" href="#seo-tab" role="tab" aria-controls="seo-tab" aria-selected="false">SEO</a>
                            <a class="nav-link" id="v-envio-tab" data-toggle="pill" href="#envio-tab" role="tab" aria-controls="envio-tab" aria-selected="false">Envío</a>
                            <a class="nav-link" id="v-atributos-tab" data-toggle="pill" href="#atributos-tab" role="tab" aria-controls="atributos-tab" aria-selected="false">Atributos</a>
                            <a class="nav-link" id="v-vinculado-tab" data-toggle="pill" href="#vinculado-tab" role="tab" aria-controls="vinculado-tab" aria-selected="false">Vinculado</a>
                            <a class="nav-link" id="v-vendido-tab" data-toggle="pill" href="#vendido-tab" role="tab" aria-controls="vendido-tab" aria-selected="false">Vendido por</a>
                            <a class="nav-link" id="v-add-on-tab" data-toggle="pill" href="#add-on-tab" role="tab" aria-controls="add-on-tab" aria-selected="false">Add-On</a>
                        </div>
                        <div class="tab-content col-md-9 col-12" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="inventario-tab" role="tabpanel" aria-labelledby="v-inventario-tab">
                                <div class="col-12 card p-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_gestion_inv">¿Gestionar inventario?</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input <?php if($producto['productos_gestion_inv']){ echo "checked" ; }?> class="" type="checkbox" value="1" name="productos_gestion_inv" id="productos_gestion_inv" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_stock">Cantidad en inventario<sup class="text-danger">*</sup></label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input required class="form-control" type="number" name="productos_stock" id="productos_stock" value="<?=$producto['productos_stock']?>" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_estado_inv">Estado del inventario<sup class="text-danger">*</sup></label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <select class="form-control" name="productos_estado_inv" id="productos_estado_inv">
                                                    <option <?php if($producto['productos_estado_inv']==1){ echo "selected" ; } ?> value="1">Hay existencia</option>
                                                    <option <?php if($producto['productos_estado_inv']==2){ echo "selected" ; } ?> value="2">Agotado</option>
                                                    <option <?php if($producto['productos_estado_inv']==3){ echo "selected" ; } ?> value="3">En reserva</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_venta_individual">Vendido individualmente</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input <?php if($producto['productos_venta_individual']){ echo "checked" ; }?> class="" type="checkbox" name="productos_venta_individual" id="productos_venta_individual" value="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="seo-tab" role="tabpanel" aria-labelledby="v-seo-tab">
                                <div class="col-12 card p-4">
                                    <div class="col-12">
                                        <p class="mb-0"><span>
                                                <?=base_url()?></span></p>
                                        <h5 class="mb-0"><a class="color-link-google" href="#" id="tltseo">
                                                <?=$producto['productos_titulo_seo']?></a></h5>
                                        <p class="" id="metadesc">
                                            <?=$producto['productos_meta_descripcion']?>
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="productos_titulo_seo">Titulo de SEO</label>
                                        <input class="form-control" onchange="cambiotxt(this,'#tltseo');" type="text" id="productos_titulo_seo" name="productos_titulo_seo" value="<?=$producto['productos_titulo_seo']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="productos_meta_descripcion">Metadescripcion</label>
                                        <textarea class="form-control" onchange="cambiotxt(this,'#metadesc');" id="productos_meta_descripcion" name="productos_meta_descripcion"><?=$producto['productos_meta_descripcion']?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="envio-tab" role="tabpanel" aria-labelledby="v-envio-tab">
                                <div class="col-12 card p-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_peso">Peso (kg)<sup class="text-danger">*</sup></label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input required class="form-control" value="<?=$producto['productos_peso']?>" type="number" name="productos_peso" id="productos_peso">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="">Dimensiones (cm)</label>
                                            </div>
                                            <?php
                                            if ($producto['productos_dimensiones']=="") {
                                                $producto['productos_dimensiones'] = "/,//,//,//,/";
                                            }
                                            $dimensiones = explode("/,/",$producto['productos_dimensiones']);
                                            ?>
                                            <div class="col-md-7 col-12">
                                                <div class="row">
                                                    <div class="col-4 pr-2">
                                                        <input class="form-control" type="number" name="productos_dimensiones[]" id="productos_dimensiones-largo" value="<?php if(isset($dimensiones[0])){ echo $dimensiones[1]; } ?>" placeholder="Largo">
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <input class="form-control" type="number" name="productos_dimensiones[]" id="productos_dimensiones-ancho" value="<?php if(isset($dimensiones[0])){ echo $dimensiones[2]; } ?>" placeholder="Ancho">
                                                    </div>
                                                    <div class="col-4 pl-2">
                                                        <input class="form-control" type="number" name="productos_dimensiones[]" id="productos_dimensiones-alto" value="<?php if(isset($dimensiones[0])){ echo $dimensiones[3]; } ?>" placeholder="Alto">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_envio_local">Envíos Locales</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input type="hidden" value="1" name="productos_envio_local">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <?php
                                            $departamentos = getDepartamentos();
                                            if($producto['productos_ubicaciones_envio']===""){ ?>
                                            <div class="col-12" id="ubicaciones-producto">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>Ubicación 1:</label>
                                                    </div>
                                                    <div class="col-10">
                                                        <div class="row">
                                                            <div class="col-sm-6 pr-md-2 col-12">
                                                                <select onchange="cargarMuniProd(0);" id="productos_departamentos-0" name="productos_departamentos[]" class="form-control">
                                                                    <option value="">Seleccione...</option>
                                                                    <?php
                                                                    foreach ($departamentos->result_array() as $key => $value) { ?>
                                                                    <option value="<?=$value['id_departamento']?>">
                                                                        <?=$value['departamento']?>
                                                                    </option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6 px-md-2 col-12">
                                                                <select id="productos_municipios-0" name="productos_municipios[]" class="form-control">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 pr-md-2">
                                                        <button onclick="aggUbiProducto();" type="button" class="btn bg-primary text-white">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                var cargarMuniProd = 1;
                                                var contDep = 1;
                                            </script>

                                            <?php }else{ ?>

                                            <div class="col-12" id="ubicaciones-producto">
                                                <?php
                                                $cont=0;
                                                $cargasMuni = '';
                                                $cargasMuniValor = "";
                                                $ubicaciones_arr = explode("/", $producto['productos_ubicaciones_envio']);
                                                $cant = count($ubicaciones_arr);
                                                $ultimo = $cant-1;

                                                foreach ($ubicaciones_arr as $key2 => $value2) {
                                                    $dep_mun = explode(",", $value2);
                                                    $cont=$key2;
                                                    $mostrar = $cont + 1;
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>Ubicación <?=$mostrar?>:</label>
                                                    </div>
                                                    <div class="col-10">
                                                        <div class="row">
                                                            <div class="col-sm-6 pr-md-2 col-12">
                                                                <select onchange="cargarMuniProd(<?=$key2?>);" id="productos_departamentos-<?=$key2?>" name="productos_departamentos[]" class="form-control">
                                                                    <option value="">Seleccione...</option>
                                                                    <?php 
                                                                    $idDep=0;
                                                                    foreach ($departamentos->result_array() as $key => $value) { 
                                                                        $selected="";
                                                                        if($dep_mun[0]===$value['id_departamento']){
                                                                            $selected="selected";
                                                                            $idDep=intval($value['id_departamento']);
                                                                        }
                                                                    ?>
                                                                    <option <?=$selected?> value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6 px-md-2 col-12">
                                                                <select id="productos_municipios-<?=$key2?>" name="productos_municipios[]" class="form-control">
                                                                    <?php
                                                                    if(intval($idDep)>0){
                                                                        $muniXdep=$mdMuni->getAllByIdDpto($idDep);
                                                                        foreach($muniXdep as $muni):
                                                                            $selected = (intval($dep_mun[1]) === intval($muni['id_municipio'])) ? "selected" : "";
                                                                            print '<option value="'.$muni['id_municipio'].'" '.$selected.'>'.$muni['municipio'].'</option>';
                                                                        endforeach;
                                                                    }                                                                    
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 pr-md-2 text-center">
                                                        <button onclick="$(this).parent().parent().remove();contDep--;" type="button" class="btn bg-danger text-white">×</button>
                                                        <?php if ($ultimo===$key2) { ?>
                                                        <button onclick="aggUbiProducto();" type="button" class="btn bg-primary text-white">+</button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    if ($cont===1) {
                                                        $cargasMuni = $cont;
                                                        $cargasMuniValor = $dep_mun[1];
                                                    }else{
                                                        $cargasMuni .= ",".$cont;
                                                        $cargasMuniValor .= ",".$dep_mun[1];
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <script>
                                                //var cargaMuni = [<?php //(isset($cargasMuni))?$cargaMuni:''?>];
                                                //var cargarMuniProdsMuni = [<?php //(isset($cargasMuniValor))?$cargasMuniValor:'';?>];
                                                var contDep = <?=(isset($cont))?$cont:'0';?>
                                            </script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label>Tiempo de entrega</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <select name="productos_entrega_local" id="productos_entrega_local" class="form-control">
                                                    <option <?php if($producto['productos_entrega_local']==1){ echo "selected" ; } ?> value="1">Mismo día</option>
                                                    <option <?php if($producto['productos_entrega_local']==2){ echo "selected" ; } ?> value="2">Recíbelo mañana</option>
                                                    <option <?php if($producto['productos_entrega_local']==3){ echo "selected" ; } ?> value="3">1 - 2 días hábiles </option>
                                                    <option <?php if($producto['productos_entrega_local']==4){ echo "selected" ; } ?> value="4">2 - 3 días hábiles </option>
                                                    <option <?php if($producto['productos_entrega_local']==5){ echo "selected" ; } ?> value="5">3 - 4 días hábiles </option>
                                                    <option <?php if($producto['productos_entrega_local']==6){ echo "selected" ; } ?> value="6">4 - 5 días hábiles </option>
                                                    <option <?php if($producto['productos_entrega_local']==7){ echo "selected" ; } ?> value="7">5 - 7 días hábiles</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label>Hora limite de recepción:</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input type="time" name="productos_limite_recepcion" id="productos_limite_recepcion" value="<?=$producto['productos_limite_recepcion']?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label>Valor del envío:</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input type="number" class="form-control" name="productos_valor_envio_local" value="<?=$producto['productos_valor_envio_local']?>" id="productos_valor_envio_local">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="productos_envio_nacional">Envíos nacionales</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input <?php if($producto['productos_envio_nacional']==1){ echo "checked" ; } ?> type="checkbox" name="productos_envio_nacional" id="productos_envio_nacional">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label>Tiempo de entrega</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <select name="productos_entrega_nacional" id="productos_entrega_nacional" class="form-control">
                                                    <option <?php if($producto['productos_entrega_nacional']==1){ echo "selected" ; } ?> value="1">1 - 2 días hábiles</option>
                                                    <option <?php if($producto['productos_entrega_nacional']==2){ echo "selected" ; } ?> value="2">2 - 3 días hábiles</option>
                                                    <option <?php if($producto['productos_entrega_nacional']==3){ echo "selected" ; } ?> value="3">3 - 4 días hábiles</option>
                                                    <option <?php if($producto['productos_entrega_nacional']==4){ echo "selected" ; } ?> value="4">4 - 5 días hábiles</option>
                                                    <option <?php if($producto['productos_entrega_nacional']==5){ echo "selected" ; } ?> value="5">5 - 7 días hábiles</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label>Valor del envío:</label>
                                            </div>
                                            <div class="col-md-7 col-12">
                                                <input type="number" class="form-control" name="productos_valor_envio_nacional" id="productos_valor_envio_nacional" value="<?=$producto['productos_valor_envio_nacional']?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="atributos-tab" role="tabpanel" aria-labelledby="v-atributos-tab">
                                <p>------</p>
                            </div>
                            <div class="tab-pane fade" id="vinculado-tab" role="tabpanel" aria-labelledby="v-vinculado-tab">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="w-100 buscador-productos">
                                            Productos Vinculados:
                                            <input id="productos-vinculado" type="text" class="form-control productos-vinculado">
                                            <button type="button" onclick="buscarProducto();" class="btn btn-default"><span class="icon-search"></span></button>
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-12 my-2 card p-2" id="productos-search">
                                        </div>
                                        <div class="col-sm-2 col-12 my-2 p-2 d-table">
                                            <div class="text-center d-table-cell align-middle">
                                                <button onclick="addProductRel();" type="button" class="btn btn-primary">
                                                    <span class="icon-arrow-right2"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 col-12 my-2 card p-2" id="productos-selected">
                                            <?php
                                        if ($productos_relacionados!="") {
                                            foreach ($productos_relacionados->result_array() as $key => $value) { ?>
                                            <div class="card mb-2">
                                                <input type="hidden" name="productos_relacionados[]" value="<?=$value['productos_id']?>">
                                                <p class="m-0">
                                                    <?=$value['productos_titulo']?>
                                                    <span onclick="$(this).parent().parent().remove();buscarProducto();" class="p-2 float-right icon-cross"></span>
                                                </p>
                                            </div>
                                            <?php
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="vendido-tab" role="tabpanel" aria-labelledby="v-vendido-tab">
                                <?php
                                if ($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) { ?>
                                <select class="form-control" name="productos_vendedor" id="productos_vendedor">
                                    <option <?php if ($producto['productos_vendedor']==1) { echo "selected" ; } ?> value="1">Alma de las cosas</option>
                                    <?php
                                    foreach ($usuarios->result_array() as $key => $value) { ?>
                                    <option <?php if ($value['usuarios_id']==$producto['productos_vendedor']) { echo "selected" ; } ?> value="
                                        <?=$value['usuarios_id']?>">
                                        <?=$value['name']." ".$value['lastname']?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <?php
                                }else{
                                    if ($producto["productos_vendedor"]==0 || $producto["productos_vendedor"]=="") { 
                                        if ($_SESSION['tipo_accesos']==7) { 
                                        ?>
                                <input type="hidden" name="productos_vendedor" id="productos_vendedor" value="1">
                                <?php
                                        }else{ 
                                        ?>
                                <input type="hidden" name="productos_vendedor" id="productos_vendedor" value="<?=$_SESSION['usuarios_id']?>">
                                <?php
                                        }
                                    }else{
                                        ?>
                                <input type="hidden" name="productos_vendedor" id="productos_vendedor" value="<?=$producto[" productos_vendedor"]?>">
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="tab-pane fade" id="add-on-tab" role="tabpanel" aria-labelledby="v-add-on-tab">
                                <div class="col-12">
                                    <p>Fila Add-on</p>
                                </div>
                                <?php
                                
                                if ($addons_producto !== NULL) { 
                                ?>
                                <script> var filas = <?=count($addons_producto)?>; </script>
                                <div class="col-12 filas" id="filas-add-on">
                                    <?php
                                    $count=0;
                                    foreach ($addons_producto as $key => $value) {
                                        $count++; 
                                    ?>
                                    <div class="w-100 fila border mb-2">
                                        <input type="hidden" value="<?=$value['addons_id']?>" name="addons_id[]">
                                        <div class="card col-12 top-fila" data-toggle="collapse" data-target="#collapse-fila-<?=$count?>" aria-expanded="false" aria-controls="collapseExample">
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-0 ml-4 py-2">Fila</p>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <button type="button" onclick="event.preventDefault();$(this).parent().parent().parent().parent().remove();" class="btn border mr-2 btn-classic">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapse-fila-<?=$count?>">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Tipo
                                                        <select name="addons_tipo[]" class="form-control">
                                                            <option <?php if($value['addons_tipo']=="multiple" ){ echo "selected" ; } ?> value="multiple">Única selección</option>
                                                            <option <?php if($value['addons_tipo']=="checkboxes" ){ echo "selected" ; } ?> value="checkboxes">Selección Múltiple</option>
                                                            <option <?php if($value['addons_tipo']=="short_text" ){ echo "selected" ; } ?> value="short_text">Texto corto</option>
                                                            <option <?php if($value['addons_tipo']=="long_text" ){ echo "selected" ; } ?> value="long_text">Texto Largo</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Mostrar como:
                                                        <select name="addons_display[]" class="form-control">
                                                            <option <?php if($value['addons_display']=="dropdowns" ){ echo "selected" ; } ?> value="dropdowns">Selector</option>
                                                            <option <?php if($value['addons_display']=="radio_btn" ){ echo "selected" ; } ?> value="radio_btn">Radio Buttons</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Titulo:
                                                        <input value="<?=$value['addons_titulo']?>" type="text" class="form-control" name="addons_titulo[]">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Formato del titulo:
                                                        <select name="addons_tipo_titulo[]" class="form-control">
                                                            <option <?php if($value['addons_tipo_titulo']=="label" ){ echo "selected" ; } ?> value="label">Label</option>
                                                            <option <?php if($value['addons_tipo_titulo']=="heading" ){ echo "selected" ; } ?> value="heading">Heading</option>
                                                            <option <?php if($value['addons_tipo_titulo']=="hide" ){ echo "selected" ; } ?> value="hide">Hide</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <input <?php if($value['addons_agg_desc']==1){ echo "checked" ; } ?> value="1" type="checkbox" name="addons_agg_desc[]">
                                                    Agregar descripción
                                                    <textarea class="form-control" name="addons_descripcion[]" rows="4"><?=$value['addons_descripcion']?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        <input <?php if($value['addons_requerido']==1){ echo "checked" ; } ?> value="1" type="checkbox" name="addons_requerido[]">
                                                        Fila requerida
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 opciones">
                                                <?php                                                
                                                if ($value['addons_opciones']!="") {
                                                    $opciones = explode("],[", $value['addons_opciones']);
                                                    $opciones_ar = array();
                                                    for ($i=0; $i < count($opciones)-1; $i++) { 
                                                        ?>
                                                <div class="col-12 opcion px-0 mb-2">
                                                    <div class="col-12 bg-secondary mb-2 py-2 text-white top-option">
                                                        <span>Opción</span>
                                                    </div>
                                                    <?php
                                                        $opciones_der = explode("/,/",$opciones[$i]);
                                                        $text_fila = $opciones_der[0];
                                                        $tipo_fila = $opciones_der[1];
                                                        $precio_fila = $opciones_der[2];
                                                        ?>
                                                    <div class="col-12 fila-option">
                                                        <div class="row">
                                                            <div class="col-sm-5 col-12">
                                                                <input value="<?=$text_fila?>" type="text" name="option_text_fila[]" class="option-text form-control" placeholder="Ingresa una opción">
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <select name="option_tipo_fila[]" class="form-control option_tipo">
                                                                    <option <?php if($tipo_fila=="1" ){ echo "selected" ; } ?> value="1">Flat Fee</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <input value="<?=$precio_fila?>" type="number" name="option_precio_fila[]" class="form-control precio_opcion">
                                                            </div>
                                                            <div class="col-sm-1 co-12 text-right">
                                                                <button type="button" onclick="$(this).parent().parent().parent().parent().remove();" class="btn btn-default text-white">X</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    } 
                                                    ?>
                                                <?php
                                                }else{ ?>
                                                <div class="col-12 opcion px-0 mb-2">
                                                    <div class="col-12 bg-secondary mb-2 py-2 text-white top-option">
                                                        <span>Opción</span>
                                                    </div>
                                                    <div class="col-12 fila-option">
                                                        <div class="row">
                                                            <div class="col-sm-5 col-12">
                                                                <input type="text" name="option_text_fila[]" class="option-text form-control" placeholder="Ingresa una opción">
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <select name="option_tipo_fila[]" class="form-control option_tipo">
                                                                    <option value="1">Flat Fee</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <input type="number" name="option_precio_fila[]" class="form-control precio_opcion">
                                                            </div>
                                                            <div class="col-sm-1 co-12 text-right">
                                                                <button type="button" onclick="$(this).parent().parent().parent().parent().remove();" class="btn btn-default text-white">X</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <input type="hidden" class="addons_opciones" name="addons_opciones[]" value="">
                                            <div class="col-12 text-right mb-3 mr-2">
                                                <button type="button" onclick="aggOption(this);" class="btn btn-default">Agregar opcion</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                }else{ ?>
                                <script>
                                    var filas = 1;
                                </script>
                                <div class="col-12 filas" id="filas-add-on">
                                    <div class="w-100 fila border mb-2">
                                        <input type="hidden" name="addons_id[]">
                                        <div class="card col-12 top-fila" data-toggle="collapse" data-target="#collapse-fila-1" aria-expanded="false" aria-controls="collapseExample">
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-0 ml-4 py-2">Fila</p>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <button type="button" onclick="event.preventDefault();$(this).parent().parent().parent().parent().remove();" class="btn border mr-2 btn-classic">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapse-fila-1">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Type
                                                        <select name="addons_tipo[]" class="form-control">
                                                            <option value="multiple">Multiple Choice</option>
                                                            <option value="checkboxes">Checkboxes</option>
                                                            <option value="short_text">Texto corto</option>
                                                            <option value="long_text">Texto Largo</option>
                                                            <option value="dropdowns">Dropdowns</option>
                                                            <option value="radio_btn">Radio Buttons</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Titulo:
                                                        <input type="text" class="form-control" name="addons_titulo[]">
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        Formato del titulo:
                                                        <select name="addons_tipo_titulo[]" class="form-control">
                                                            <option value="label">Label</option>
                                                            <option value="heading">Heading</option>
                                                            <option value="hide">Hide</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <input value="1" type="checkbox" name="addons_agg_desc[]">
                                                    Agregar descripción
                                                    <textarea class="form-control" name="addons_descripcion[]" rows="4"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="w-100">
                                                        <input value="1" type="checkbox" name="addons_requerido[]">
                                                        Fila requerida
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 opciones">
                                                <div class="col-12 opcion px-0 mb-2">
                                                    <div class="col-12 bg-secondary mb-2 py-2 text-white top-option">
                                                        <span>Opción</span>
                                                    </div>
                                                    <div class="col-12 fila-option">
                                                        <div class="row">
                                                            <div class="col-sm-5 col-12">
                                                                <input type="text" name="option_text_fila[]" class="option-text form-control" placeholder="Ingresa una opción">
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <select name="option_tipo_fila[]" class="form-control option_tipo">
                                                                    <option value="1">Flat Fee</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <input type="number" name="option_precio_fila[]" class="form-control precio_opcion">
                                                            </div>
                                                            <div class="col-sm-1 co-12 text-right">
                                                                <button type="button" onclick="$(this).parent().parent().parent().parent().remove();" class="btn btn-default text-white">X</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" class="addons_opciones" name="addons_opciones[]" value="">
                                            <div class="col-12 text-right mb-3 mr-2">
                                                <button type="button" onclick="aggOption(this);" class="btn btn-default">Agregar opcion</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <button onclick="agregarFila('#filas-add-on');" type="button" class="btn border btn-classic">Agregar Fila</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4 text-right">
                    <?php
                    if ($producto['productos_id']=="" || $producto['productos_id']==0 || $producto['productos_id']==NULL) { ?>
                    <button onclick="valFormProducto();" type="submit" class="btn btn-default py-1 btn-sm">Publicar</button>
                    <?php
                    }else { ?>
                    <button onclick="valFormProducto();" type="submit" class="btn btn-default btn-sm">Actualizar</button>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="ModalGallery" tabindex="-1" role="dialog" aria-labelledby="ModalGalleryLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="content-gallery">
        </div>
    </div>
</div>
<div class="div-progress-bar d-none">
    <div class="d-table w-100 h-100">
        <div class="d-table-cell align-middle">
            <div class="col-md-6 col-10 offset-1 offset-md-3">
                <p class="text-center text-white" id="p-nombre"></p>
                <div class="progress">
                    <div class="progress-bar" id="progressBar1"></div>
                </div>
            </div>
        </div>
    </div>
</div>