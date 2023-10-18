<?php
if($this->uri->segment(2)=="categorias"){ ?>
    <script type="text/javascript">
        fbq('track','ViewContent', {content_ids:['<?=$categoria_padre['categorias_id']?>'],content_type:'page',content_name:"Tienda Categoría - <?=$categoria_padre['categorias_nombre']?>"});
    </script>
<?php
}elseif($this->uri->segment(2)=="vendor"){
?>
    <script type="text/javascript">
        fbq('track','ViewContent', {content_ids:['<?=$vendor['usuarios_id']?>'],content_type:'page',content_name:"Tienda Vendedor - <?=$vendor['name']?>"});
    </script>
<?php
}else{
?>
<script type="text/javascript">
    fbq('track','ViewContent',content_type:"page", {content_name:"Tienda"});
</script>
<?php
}

    if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL ) {
        $page = $_REQUEST['page'];
        $pageNext = $page+1;
        $pagePrev = $page-1;
    }else{
        $page = 1;
        $pageNext = 2;
        $pagePrev = 0;
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

    if (array_key_exists('page',$_REQUEST)) {
        $urlActualNextPage = $sinreq."?".$varequest;
        $urlActualPrevPage = $sinreq."?".$varequestPrev;
    }else{
        if ($varequest!="") {
            $urlActualNextPage = $sinreq."?".$varequest."&page=2";
            $urlActualPrevPage = $sinreq."?".$varequestPrev;
        }else{
            $urlActualNextPage = $sinreq."?page=2";
            $urlActualPrevPage = $sinreq;
        }
    }
?>
<main class="col-12 cuerpo float-left">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <?php
                if($this->uri->segment(2)=="categorias"){
                    if ($categoria_padre['categorias_banner_desktop_enlace']=="") {
                        $categoria_padre['categorias_banner_desktop_enlace'] = "assets/img/header-bg.jpg";
                    } 
                    if ($categoria_padre['categorias_banner_mobile_enlace']=="") {
                        $categoria_padre['categorias_banner_mobile_enlace'] = "assets/img/header-bg.jpg";
                    } 
                ?>
                <style>
                @media (max-width:767px){
                    .bg_banner_vendor{
                        background-image:url(<?=$categoria_padre['categorias_banner_mobile_enlace']?>)!important;
                    }
                }
                @media (min-width:768px){
                    .bg_banner_vendor{
                        background-image:url(<?=$categoria_padre['categorias_banner_desktop_enlace']?>)!important;
                    }
                }
                </style>
                <?php 
                }
                ?>
                <style>
                    .bg_tienda{ background-image:url(assets/img/header-bg.jpg); text-align:center; }
                </style>
                <div class="col-12 py-4 bg-gray <?php if($this->uri->segment(2)=="vendor" || $this->uri->segment(2)=="categorias"){ echo "bg_banner_vendor"; }else{ echo " bg_tienda "; } ?>"
                <?php if($this->uri->segment(2)=="vendor"){ $image =  str_replace(")","%29",$vendor['imagen_banner']); $image =  str_replace("(","%28",$image);  echo 'style="background-image:url('.base_url().$image.');"'; } ?>>
                    <div class="col-md-10 col-12 offset-md-1">
                        <?php
                        if($this->uri->segment(2)=="vendor"){
                            if ($vendor['imagen_perfil']=="") {
                                $vendor['imagen_perfil'] = "assets/img/Not-Image.png";
                            }
                        ?>
                        <h2 class="mb-0 py-4">
                            <div class="img-profile-vendor" style="background-image:url(<?=base_url().$vendor['imagen_perfil']?>);"></div>
                            <span><?=$vendor['name']?></span>
                        </h2>
                        <?php
                        }elseif($this->uri->segment(2)=="categorias"){ ?>
                        
                        <h2 class="mb-0 py-4"><?php
                        if ($categoria_padre['categorias_titulo_h1']!="") {
                            echo $categoria_padre['categorias_titulo_h1'];
                        }else{
                            echo $categoria_padre['categorias_nombre'];
                        }
                        ?></h2>
                        <?php
                        }else{ ?>
                        <h2 class="mb-0 py-4">Tienda</h2>
                        <?php    
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10 offset-md-1 my-md-5 col-12">
            <div class="col-12 pb-md-5 pt-md-3 px-0">
                <div class="row">
                    <div class="col-12 d-md-none d-block form-filtros px-md-3 p-0 border">
                        <div class="row">
                            <div class="col-12 filtros-categorias-first d-none py-1 bg-white">
                                <label>
                                    <input type="checkbox" name="filterscategorias[]" value="Regalos" id="">
                                    <span>
                                        Regalos
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="filterscategorias[]" value="Hogar" id="">
                                    <span>
                                        Hogar
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="filterscategorias[]" value="Bienestar" id="">
                                    <span>
                                        Bienestar
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="filterscategorias[]" value="Colección" id="">
                                    <span>
                                        Colección
                                    </span>
                                </label>
                                <label>
                                    <input type="checkbox" name="filterscategorias[]" value="Regalos" id="">
                                    <span>
                                        Regalos
                                    </span>
                                </label>
                            </div>
                            <div class="col-6 py-2 filter-orderby text-center border-right">
                                <select id="orderby-m" onchange="ordernarPor();" class="orderby-m">
                                    <option value="">Ordenar Por</option>
                                    <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="defecto"){ echo "selected"; } ?> value="defecto">Por defecto</option>
                                    <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="sale"){ echo "selected"; } ?> <?=(!isset($_REQUEST['orderby']))?'selected':''?> value="sale">Por popularidad</option>
                                    <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="date"){ echo "selected"; } ?> value="date">Por Los Últimos</option>
                                    <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="price_low"){ echo "selected"; } ?> value="price_low">Por Precio: bajo a alto</option>
                                    <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="price_high"){ echo "selected"; } ?> value="price_high">Por Precio: alto a bajo</option>
                                </select>
                            </div>
                            <div class="col-6 py-2 filter-orderby d-table text-center" onclick="abrirfiltros();">
                                <p class="align-middle d-table-cell">
                                    Filtros <span class="icon-arrow-down"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 filtros-in-mb">
                        <div class="d-md-none col-12 top-close">
                            <h3>Filtros</h3>
                            <span class="close-menu d-md-none d-block" onclick="cerrarfiltros();" aria-hidden="true">×</span>
                        </div>
                        <div class="row pt-md-0 overflow-filtros-m pt-3">
                            <input type="hidden" name="mindefault" id="mindefault" value="<?=$preciosFiltro['minimo']?>">
                            <input type="hidden" name="maxdefault" id="maxdefault" value="<?=$preciosFiltro['maximo']?>">
                            <form class="col-12 form-filtros border mb-4 pr-md-5 pl-xl-4 bg-transparent" id="form-filtros" action="" method="get">
                                <div class="row">
                                    <div class="text-center py-3 d-md-block d-none">
                                        <h3>Filtros</h3>
                                    </div>
                                    <div class="col-12 botones-filtros text-center">
                                        <div class="row">
                                            <div class="col-md-12 col-7 col-xl-7 px-1 mb-md-4">
                                                <button class="d-block mx-auto form-control btn-info" type="submit">
                                                    Aplicar filtros
                                                </button>
                                            </div>
                                            <div class="col-md-12 col-5 px-1 col-xl-5 mb-md-4">
                                                <a class="d-block form-control btn-limpiar w-100" href="<?=base_url($sinreq)?>">Limpiar</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if ($categorias->num_rows() > 0 && ($this->uri->segment(2)=="categorias" || $this->uri->segment(2)==NULL)) { ?>
                                    <div class="col-12 px-0 border-top py-3">
                                        <?php
                                        if ($this->uri->segment(2)=="categorias") { ?>
                                        <h6 class="mb-4">Subcategorías</h6>
                                        <?php
                                        }else{ ?>
                                        <h6 class="mb-4">Categorías</h6>
                                        <?php
                                        }
                                        ?>
                                        <ul class="no-style subcategorias_tienda" >
                                        <?php
                                        $catComida=0;
                                        if ($this->uri->segment(2)=="categorias") {
                                            $conteo = 0;
                                            foreach ($categorias->result_array() as $key => $value) {
                                                $image = image($value['medios_url']);
                                                $conteo++; 
                                                ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" <?php if (isset($_REQUEST['productos_categorias']) && in_array($value['categorias_id'], $_REQUEST['productos_categorias'])){ echo "checked"; } ?> value="<?=$value['categorias_id']?>" name="productos_categorias[]" id="categorias_padre-<?=$conteo?>">
                                                    <span class="image" style="background-image:url(<?=$image?>);"></span>
                                                    <span class="text">
                                                        <?=$value['categorias_nombre']?>
                                                    </span>
                                                </label>
                                            </li>
                                            <?php
                                            }
                                        }elseif($this->uri->segment(2)==NULL){
                                            $cate = array();
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
                                                $image = image($value['padre']['medios_enlace_miniatura']);
                                                ?>
                                                <li>
                                                    <a href="tienda/categorias/<?=$value['padre']['categorias_id']."/".$value['padre']['categorias_nombre']?>">
                                                        <label>
                                                            <span class="image" style="background-image:url(<?=$image?>);"></span>
                                                            <span class="text">
                                                                <?=$value['padre']['categorias_nombre']?>
                                                            </span>
                                                        </label>
                                                    </a>
                                                </li>
                                                <?php
                                            }

                                        }
                                        ?>
                                        </ul>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="col-12 d-none px-0 border-top py-3">
                                        <h6 class="mb-3">Condiciones especiales</h6>
                                        <div class="w-100 condiciones-especiales">
                                            <label class="w-100 my-2">
                                                <span class="icon-home3 pr-2"></span> Envío gratis
                                                <input type="checkbox" class="check-slide mt-1 float-right" name="envio_gratis">
                                            </label>
                                            <label class="w-100 my-2">
                                                <span class="icon-user pr-2"></span> Entrega el mismo día
                                                <input type="checkbox" class="check-slide mt-1 float-right" name="entrega_mismo_dia">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0 border-top py-3 d-md-block d-none">
                                        <h6 class="mb-3">Ordenar Por:</h6>
                                        <select name="orderby" id="orderby" class="form-control">
                                            <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="defecto"){ echo "selected"; } ?> value="defecto">Por defecto</option>
                                            <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="sale"){ echo "selected"; } ?> <?=(!isset($_REQUEST['orderby']))?'selected':''?> value="sale">Por popularidad</option>
                                            <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="date"){ echo "selected"; } ?> value="date">Por Los Últimos</option>
                                            <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="price_low"){ echo "selected"; } ?> value="price_low">Por Precio: bajo a alto</option>
                                            <option <?php if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=="price_high"){ echo "selected"; } ?> value="price_high">Por Precio: alto a bajo</option>
                                        </select>
                                    </div>
                                    <div class="col-12 px-0 border-top py-3">
                                        <h6 class="mb-3">Precio</h6>
                                        <div class="filtro-precio">
                                            <p class="mb-3">Rango de precios: <span class="color-gray-light"><span id="min-p"><?=number_format($preciosFiltro['minimo'], 0, ',', '.')?></span> - <span id="max-p"><?=number_format($preciosFiltro['maximo'], 0, ',', '.')?></span></span></p>
                                            <?php if ((isset($_REQUEST['minprice']) && isset($_REQUEST['maxprice'])) && ($_REQUEST['maxprice']!=NULL && $_REQUEST['minprice']!=NULL) ) { ?>
                                                <input type="hidden" name="minprice" id="minprice" value="<?=$_REQUEST['minprice']?>">
                                                <input type="hidden" name="maxprice" id="maxprice" value="<?=$_REQUEST['maxprice']?>">
                                            <?php
                                            }else{ ?>
                                                <input type="hidden" name="minprice" id="minprice" value="<?=$preciosFiltro['minimo']?>">
                                                <input type="hidden" name="maxprice" id="maxprice" value="<?=$preciosFiltro['maximo']?>">
                                            <?php
                                            } 
                                            ?>
                                            <div class="col-12">
                                                <div id="uislider"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 px-0 border-top py-3 d-none">
                                        <h6 class="mb-3">Etiquetas</h6>
                                        <div class="col-12 px-0 filtros-categorias-first py-1 bg-white">
                                            <label>
                                                <input type="checkbox" name="filterscategorias[]" value="Regalos" id="">
                                                <span>
                                                    Regalos
                                                </span>
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filterscategorias[]" value="Hogar" id="">
                                                <span>
                                                    Hogar
                                                </span>
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filterscategorias[]" value="Bienestar" id="">
                                                <span>
                                                    Bienestar
                                                </span>
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filterscategorias[]" value="Colección" id="">
                                                <span>
                                                    Colección
                                                </span>
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filterscategorias[]" value="Regalos" id="">
                                                <span>
                                                    Regalos
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    

                                    <div class="col-12 botones-filtros text-center border-top py-3 mt-4 d-md-block d-none">
                                        <div class="row">
                                            <div class="col-md-12 col-7 col-xl-7 px-1 mb-md-4">
                                                <button class="d-block mx-auto form-control btn-info" type="submit">
                                                    Aplicar filtros
                                                </button>
                                            </div>
                                            <div class="col-md-12 col-5 px-1 col-xl-5 mb-md-4">
                                                <a class="d-block form-control btn-limpiar w-100" href="<?=base_url($sinreq)?>">Limpiar</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 section-productos pb-5 mt-md-0 mt-4">
                        <div class="row">
                            <div class="col-12 px-4">
                                <div class="row" id="loop-products">
                                    <?php
                                    if (count($productos) === 0) { ?>
                                    <div class="col-12 text-center">
                                        <p>No hay productos para mostrar</p>
                                    </div>
                                    <?php
                                    }

                                    foreach ($productos as $key => $value) {
                                        $tipo='';
                                        if(isset($value['sorting'])) $tipo='('.$value['sorting'].')';

                                        $evento_ubi = "";
                                        if (!isset($_SESSION['municipio_session'])) {
                                            if ($value['productos_envio_local']==1 && $value['productos_envio_nacional']==0) {
                                                $evento_ubi = 'modalUbicacion(\'Verificación de cobertura\',\'Este producto tiene restricción de envío a algunas zonas, cuéntanos a dónde deseas enviarlo para verificar cobertura\');';
                                            }
                                        } 
                                        $image = delBackslashIni($value['medios_enlace_miniatura']);
                                        $classImage='';
                                        if(!file_exists($image)) $image = delBackslashIni($value['medios_url']);
                                        if(file_exists($image)){
                                            list($width, $height, $type, $attr) = getimagesize($image);
                                            $classImage = ($width>$height) ? '' : 'imagenVertical';
                                            if($height<360 && $classImage==='') $classImage='imagenHorizontal';
                                        }
                                        if(!file_exists($image)) $image = 'assets/img/Not-Image.png';
                                        ?>
                                    <div class="col-md-4 col-6 px-md-0 px-2">
                                        <div class="products mb-4">
                                            <a onclick="<?php if($value['productos_envio_local']==1 && $value['productos_envio_nacional']==0){ echo $evento_ubi; } ?>" href="<?=base_url('tienda/single/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>">
                                                <div class="image position-relative">
                                                    <img class="d-none lazy <?=$classImage?>" data-src="<?=$image.'?'.rand()?>" src="assets/img/loading/yellow_1.gif" alt="<?=$value['medios_titulo']?>">
                                                    <?php
                                                    if ($value['productos_precio_oferta']!="" && $value['productos_precio_oferta'] > 0 && $value['productos_precio_oferta'] < $value['productos_precio']) {
                                                        $oferta = 0;
                                                        $oferta = 100/$value['productos_precio'];
                                                        $oferta = $oferta*$value['productos_precio_oferta'];
                                                        $oferta = 100-$oferta;
                                                        ?>
                                                        <span class="tag-precio-oferta">
                                                            <?=number_format($oferta, 0, ',', '.')?>% off
                                                        </span>
                                                        <?php
                                                    }
                                                    if ($value['productos_envio_nacional']==1 && $value['productos_valor_envio_nacional']==0){ ?>
                                                    <span class="tag-envio-gratis">
                                                        Envío GRATIS
                                                    </span>
                                                    <?php
                                                    }else{
                                                        if (isset($_SESSION['municipio_session']) && $value['productos_envio_local']==1 && $value['productos_valor_envio_local']==0 ) {
                                                            $ubicaciones = explode("/",$value['productos_ubicaciones_envio']);
                                                            $yesubi = 0;
                                                            for ($i=0; $i < count($ubicaciones); $i++) {
                                                                $ubi = explode(",",$ubicaciones[$i]);
                                                                if ($_SESSION['departamento_session']==$ubi[0] && $_SESSION['municipio_session']==$ubi[1]) { 
                                                                    $yesubi = 1;
                                                                }
                                                            }
                                                            if ($yesubi==1) { 
                                                            ?>
                                                        <span class="tag-envio-gratis">
                                                            Envío GRATIS
                                                        </span>
                                                        <?php   
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="info">
                                                    <p class="mt-2 mb-0 bold"><?=$value['productos_titulo']?></p>
                                                    <?php
                                                    if (isset($_SESSION['departamento_session']) && $_SESSION['departamento_session']!=NULL) {
                                                        $env = 0;
                                                        $ubiPro = explode("/",$value['productos_ubicaciones_envio']);
                                                        for ($i=0; $i < count($ubiPro); $i++) {
                                                            $ubiPro_der = explode(",",$ubiPro[$i]);
                                                            if (isset($ubiPro_der[0]) && isset($ubiPro_der[1]) && $_SESSION['departamento_session']==$ubiPro_der[0] && $_SESSION['municipio_session']==$ubiPro_der[1]) {
                                                                $env = 1;
                                                            }
                                                        }
                                                        $entrega_local = array( 
                                                            "", 
                                                            "Mismo día", 
                                                            "Recíbelo mañana", 
                                                            "1 - 2 días hábiles ", 
                                                            "2 - 3 días hábiles ",
                                                            "3 - 4 días hábiles ",
                                                            "4 - 5 días hábiles ",
                                                            "5 - 7 días hábiles "
                                                        );
                                                        $entrega_nacional = array(
                                                            "", 
                                                            "1 - 2 días hábiles ", 
                                                            "2 - 3 días hábiles ",
                                                            "3 - 4 días hábiles ",
                                                            "4 - 5 días hábiles ",
                                                            "5 - 7 días hábiles "
                                                        );
                                                        if ($env == 1) {
                                                            if ($value['productos_entrega_local']!=0 && $value['productos_entrega_local']!="") {
                                                                if ($value['productos_entrega_local']==1) {
                                                                ?>
                                                                <p class="my-0 p-vendedor"><span class="icon-mismo-dia color-verde-tienda pr-2"></span><?=$entrega_local[$value['productos_entrega_local']]?></p>
                                                                <?php    
                                                                } else{
                                                                ?>
                                                                <p class="my-0 p-vendedor"><span class="icon-envios pr-2"></span><?=$entrega_local[$value['productos_entrega_local']]?></p>
                                                                <?php
                                                                }
                                                            }
                                                        }else{ ?>
                                                             <p class="my-0 p-vendedor"><span class="icon-envios pr-2"></span><?=$entrega_nacional[$value['productos_entrega_nacional']]?></p>
                                                            <?php
                                                        }
                                                    }else{ ?>
                                                    <p class="my-0 p-vendedor"><?=$value['name']?></p>
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($value['productos_precio_oferta']=="" || $value['productos_precio_oferta']<=0) { ?>
                                                    <p class="price mb-2 mt-0">$ <?=number_format($value['productos_precio'], 0, ',', '.')?></p>
                                                    <?php
                                                    }else{ ?>
                                                    <p class="price mb-2 mt-0">$ <?=number_format($value['productos_precio_oferta'], 0, ',', '.')?> - <span class="tachado">$ <?=number_format($value['productos_precio'], 0, ',', '.')?></span> </p>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 text-center py-4">
                                <div class="paginado">
                                    <ul>
                                        <?php if(isset($page) && $page>1){ ?>
                                        <li>            
                                            <a href="<?=(intval($page)>1)?$urlActualPrevPage:'#'?>">&lt; Anterior</a> 
                                        </li> 
                                        <?php } ?>

                                        <li><span id="ind-page" class="page"><?=$page?></span></li>
                                        
                                        <?php if(count($productos)===24){ ?>
                                        <li>
                                            <a href="<?=$urlActualNextPage?>">Siguiente &gt;</a>
                                        </li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            <input type="hidden" name="page" id="page" value="<?=$page?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</main>
