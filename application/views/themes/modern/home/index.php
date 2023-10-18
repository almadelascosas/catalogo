<script type="text/javascript">
    fbq('track','ViewContent', {content_name:"Página de inicio"});
</script>
<main class="cuerpo col-12 float-left">
    <div class="row">
        <div class="col-12 text-center banner px-0">
            <?php 
            $image='';
            if (isset($banner_home['banner_imagen']) && $banner_home['banner_imagen']!=""){
                $image = (isMobile()) ? delBackslashIni($banner_home['banner_imagen_mobile_url']) : delBackslashIni($banner_home['banner_imagen_url']);
            }
            $alt=$banner_home['banner_imagen_titulo'];
            $link=$banner_home['banner_enlace'];
            $imageLoading=(isMobile())?'assets/img/loading/yellow_1.gif':'assets/img/loading/loading-banner-desktop.gif';
            //print $image.'.yepagu';
            if(!file_exists($image) || $image===''){
                $image=(isMobile())?'assets/img/Banner-padre-mobile.png':'assets/img/Banner-desktop.png';
                $alt='Banner';
                $link='javascript:void(0)';
            }
            ?>
            <a href="<?=$link?>">
                <img style="width:100%; max-height: 600px;" class="lazy <?=(!isMobile())?'w-100':'';?>" data-src="<?=$image.'?'.rand()?>" src="<?=$imageLoading?>" alt="<?=$alt?>">
            </a>        
        </div>
        <div class="col-12 mb-4">
            <div class="col-md-10 offset-md-1 col-12 px-0">
                <div class="banner-second px-4 py-3">
                    <div class="owl-carousel carousel-banner">
                        <?php
                        foreach ($banner_second->result_array() as $key => $value) {
                            $image=delBackslashIni($value['medios_url']);
                            if(!file_exists($image)) $image='assets/img/Not-Image.png';
                        ?>
                        <div id="banner-second-<?=$value['banner_home_noticias_id']?>">
                            <div class="col-12 col-md-10 offset-md-1 px-3">
                                <div class="row">
                                    <div class="col-12 px-md-0 px-3">
                                        <div class="icono-fav-banner">
                                            <img class="lazy" data-src="<?=$image.'?'.rand()?>" src="assets/img/loading/yellow_3.gif" alt="<?=$value['medios_titulo']?>" >
                                        </div>
                                        <p class="m-0">
                                            <?=$value['banner_home_noticias_texto']?>
                                        </p>
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
        <div class="col-12 section-categorias py-4">
            <div class="px-0 col-md-10 offset-md-1 col-12 px-0">
                <div class="row">
                    <div class="col-12 pb-2 top-section">
                        <div class="row">
                            <div class="d-table px-md-2 col-8">
                                <h2 class="m-0">Categorías únicas</h2>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?=base_url('tienda')?>">Ver todas ></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-3 px-md-2">
                        <p class="mb-2">Si es un producto especial está acá.</p>
                    </div>
                    <div class="col-12 px-4 text-center">
                        <div class="row">
                            <?php
                            $cantPermitida=(isMobile())?6:8;
                            foreach ($categorias->result_array() as $key => $value): 
                                if($key<$cantPermitida){
                            ?>
                            <div class="col-4 col-md-1_5 categorias px-2">
                                <div class="image" style="height:100%">
                                    <a href="<?=base_url('tienda/categorias/'.$value['categorias_id'].'/'.$value['categorias_nombre'])?>">
                                        <?php
                                        if($value['categorias_imagen']!=0) $image=delBackslashIni($value['medios_url']);
                                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                        ?>
           
                                        <img class="lazy" style="width: 100%; height: auto" data-src="<?=$image.'?'.rand()?>" src="assets/img/loading/yellow_3.gif" alt="<?=$value['medios_url']?>">
                                        

                                    </a>
                                </div>
                                <p><?=$value['categorias_nombre']?></p>
                            </div>
                            <?php 
                                }
                            endforeach; 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($latestViews!=array()) { ?>
        <div class="col-12 section-ultimos-vistos py-md-4 py-2 px-4">
            <div class="col-md-10 offset-md-1 col-12 px-0">
                <div class="row">
                    <div class="col-12 top-section">
                        <div class="row">
                            <div class="pl-2 d-table mb-3 col-8">
                                <h2>Últimos vistos</h2>
                            </div>
                            <div class="pr-2 col-4 text-right">
                                <a href="<?=base_url('tienda')?>">Ver todos ></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-3 px-2">
                        <p>Nuestra selección de productos que te pueden gustar</p>
                    </div>
                    <div class="col-12 pb-3 px-2">
                        <div class="owl-carousel carousel-productos">
                            <?php
                            $cont=0;
                            foreach ($latestViews->result_array() as $key => $value) { ?>
                            <div>
                                <div class="products mb-4">
                                    <a href="<?=base_url('tienda/single/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>">
                                        <div class="image">
                                            <?php
                                            if($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) $image = delBackslashIni($value['medios_enlace_miniatura']); 
                                            $classImage='';
                                            if(file_exists($image)){
                                                list($width, $height, $type, $attr) = getimagesize($image);
                                                $classImage = ($width>$height) ? '' : 'imagenVertical';
                                                if($height<360 && $classImage==='') $classImage='imagenHorizontal';
                                            }
                                            if(!file_exists($image)) $image = 'assets/img/Not-Image.png';
                                            ?>
                                            <img class="d-none lazy <?=$classImage?>" data-src="<?=$image.'?'.rand()?>" src="assets/img/loading/yellow_1.gif" alt="<?=$value['medios_titulo']?>">
                                        </div>
                                        <div class="info">
                                            <p class="mt-2 mb-0 bold"><?=$value['productos_titulo']?></p>
                                            <p class="my-0 p-vendedor"><?=$value['name']?></p>
                                            <p class="price mb-2 mt-0">$ <?=number_format($value['productos_precio'], 0, ',', '.')?></p>
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
        </div>
        <?php
        }

        if ($latestViews!=array()) { 
        ?>        
        <div class="col-12 section-vendedores-unicos d-none py-md-4 py-2 px-4">
            <div class="col-md-10 offset-md-1 col-12 px-0">
                <div class="row">
                    <div class="col-12 top-section">
                        <div class="row">
                            <div class="pl-2 d-table mb-3 col-8">
                                <h2>Vendedores únicos</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 area-vendedores-unicos mb-4">
                        <div class="menu-vendedores-unicos">
                            <nav>
                                <ul>
                                    
                                    <?php
                                    $cont=0;
                                    if ($categorias_vendor!=array()) {
                                        foreach ($categorias_vendor['data'] as $key => $value) {
                                            $cont++; 
                                            ?>
                                        <li onclick="vendorCat(<?=$value['categorias_id']?>);" class="menu-vendedores-unicos-li <?php if($cont==1){ echo 'active'; } ?>">
                                            <a href="#categoria-por-vendedor-<?=$value['categorias_id']?>" class="tag-categorias-por-vendedor">
                                                <?=$value['categorias_nombre']?>
                                            </a> 
                                        </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                    
                                </ul>
                            </nav>
                        </div>

                        <div id="vendedores-por-categoria" class="col-12 px-0">
                            <?php
                            if ($categorias_vendor!=array()) {
                                foreach ($categorias_vendor['data'] as $key => $value):
                                    if ($key === 0) { 
                            ?>
                                    <div id="categoria-por-vendedor-<?=$value['categorias_id']?>" class="row contenedor-vendedores d-flex">
                                        <?php foreach ($vendedores_first['data'] as $key2 => $value2): ?>                                 
                                        <div class="col-6 col-md-2_5 px-2">
                                            <div class="card-vendedor" style="background-image:url(<?=$value2['imagen_perfil']?>);">
                                                <a href="<?=base_url('tienda/vendor/'.$value2['usuarios_id'].'/'.limpiarUri($value2['nombre_vendedor'].'-'.$value2['apellido_vendedor']))?>">
                                                    <div class="parrafo w-100">
                                                        <p class="w-100"><?=$value2['nombre_vendedor']?></p>
                                                    </div>    
                                                </a>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                            <?php   
                                    }
                                endforeach;
                            }
                            ?>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="col-12 section-recomended py-md-4 py-2 px-4">
            <div class="col-md-10 offset-md-1 col-12 px-0">
                <div class="row">
                    <div class="col-12 pb-2 top-section">
                        <div class="row">
                            <div class="pl-2 d-table mb-3 col-8">
                                <h2>Recomendados para ti</h2>
                            </div>
                            <div class="pr-2 col-4 text-right">
                                <a href="<?=base_url()?>tienda">Ver todos ></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-3 px-2">
                        <div class="owl-carousel carousel-productos">
                        <?php
                        $cont=0;
                        foreach ($productos_recomendados->result_array() as $key => $value) { ?>
                            <div>
                                <div class="products mb-4">
                                    <a href="<?=base_url('tienda/single/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>">
                                        <div class="image">
                                            <?php 
                                            if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) $image=delBackslashIni($value['medios_enlace_miniatura']);
                                            $classImage='';
                                            if(file_exists($image)){
                                                list($width, $height, $type, $attr) = getimagesize($image);
                                                $classImage = ($width>$height) ? '' : 'imagenVertical';
                                                if($height<360 && $classImage==='') $classImage='imagenHorizontal';
                                            }
                                            if(!file_exists($image)) $image='assets/img/Not-Image.png';
                                            ?>
                                            <img class="d-none lazy <?=$classImage?>" data-src="<?=$image.'?'.rand()?>" src="assets/img/loading/yellow_1.gif" alt="<?=$value['medios_titulo']?>">
                                        </div>
                                        <div class="info">
                                            <p class="mt-2 mb-0 bold"><?=$value['productos_titulo']?></p>
                                            <p class="my-0 p-vendedor"><?=$value['name']?></p>
                                            <p class="price mb-2 mt-0">$ <?=number_format($value['productos_precio'], 0, ',', '.')?></p>
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
        </div>
        
        <div class="col-12 section-newsletter py-4 pb-5">
            <div class="col-md-10 offset-md-1 col-12">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-12">
                        <form action="" method="POST" class="col-12 newsletter">
                            <div class="col-12 text-center position-relative">
                                <img class="newsletter-img lazy" data-src="assets/img/icono-newsletter.png" src="assets/img/loading/yellow_1.gif" alt="newsletter">
                            </div>
                            <h2>Suscríbete al newsletter</h2>
                            <p>Recibe contenido exclusivo de Alma como lanzamientos, noticias e información de la mejor comunidad de productos con Alma.</p>
                            <div class="entrada">
                                <input type="text">
                                <button type="submit" name="btnNewsletter" id="btnNewsletter" alt="Boton inscribirse a newsletter">Suscribirme</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 section-nuevos py-md-4 py-2 px-4 mb-5">
            <div class="col-md-10 offset-md-1 col-12 px-0">
                <div class="row">
                    <div class="col-12 pb-2 top-section">
                        <div class="row">
                            <div class="pl-2 d-table col-8">
                                <h2>Nuevos productos</h2>
                            </div>
                            <div class="pr-2 col-4 text-right">
                                <a href="<?=base_url('tienda')?>">Ver todos ></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-3 px-2">
                        <p class="my-2">Productos nuevos todas las semanas para ti</p>
                    </div>
                    <div class="col-12">
                        <div class="owl-carousel carousel-productos">
                        <?php
                        $cont=0;
                        foreach ($productos->result_array() as $key => $value) { ?>
                            <div>
                                <div class="products mb-4">
                                    <a href="<?=base_url('tienda/single/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>">
                                        <div class="image">
                                            <?php
                                            if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) $image=delBackslashIni($value['medios_enlace_miniatura']);
                                            if(!file_exists($image)) $image='assets/img/Not-Image.png'; 
                                            ?>
                                            <img class="d-none lazy" data-src="<?=$image.'?'.rand()?>" src="assets/img/loading/yellow_1.gif" alt="<?=$value['medios_titulo']?>">
                                        </div>
                                        <div class="info">
                                            <p class="mt-2 mb-0 bold"><?=$value['productos_titulo']?></p>
                                            <p class="my-0 p-vendedor"><?=$value['name']?></p>
                                            <p class="price mb-2 mt-0">$ <?=number_format($value['productos_precio'], 0, ',', '.')?></p>
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
        </div>

        <?php
        $this->load->view("themes/modern/confia-alma");  
        ?>

    </div>
</main>
