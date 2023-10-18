<script>
    var idActual = <?=$producto['productos_id']?>;
</script>
<div class="col-12 col-md-6 py-4 breadcumbs">
    <a class="btn btn-back" href="javascript:history.back();"><span class="arrow-back"><</span></a>
    <p class="controls-vistas color-white">
        <a class="color-white" href="<?=base_url()?>tienda/single/<?=$producto['productos_id'].'/'.$producto['productos_titulo']?>">Vista tradicional</a>
        |
        <a class="color-white" href="<?=base_url()?>tienda/reel/<?=$producto['productos_id'].'/'.$producto['productos_titulo']?>">Vista reel</a>
    </p>
</div>
<div id="fullpage">
    <div class="section">
        <div class="col-12 cuerpo reel">
            <div class="row h-100">
                <?php
                if (isMobile()) { ?>
                <div class="col-12 vista-reel">
                    <video preload="auto" class="my-player" id="my-player" webkit-playsinline  playsinline autoplay muted loop>
                        <source src="<?=base_url().$producto['productos_video']?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="play-video" onclick="controlvideo('#my-player','#my-player-icon');">
                        <span id="my-player-icon" class="d-none icon-play3"></span>
                    </div>
                    <div class="loading-video d-none">
                        <img src="<?=base_url()?>assets/img/loading-video.gif" alt="Loading...">
                    </div>
                    <div class="w-100 controles-reel">
                        <div class="row py-4">
                            <div class="col-9 product-info">
                                <div class="d-table w-100 h-100">
                                    <div class="d-table-cell align-bottom">
                                        <h5 class="mb-0 py-3 color-white"><?=$producto['productos_titulo']?> - $ <?=number_format($producto['productos_precio'], 2, ',', '.')?></h5>
                                        <p class="color-white"><?=$producto['productos_descripcion_corta']?> ...<a class="" onclick="modalInfo(<?=$producto['productos_id']?>);" href="#vermas">más</a>
                                    </div>
                                </div>
                            </p>
                            </div>
                            <div class="col-3 acciones-reel pl-0 text-right">
                                <a class="btn rounded-pill btn-primary" href="<?=base_url()?>tienda/addcart/<?=$producto['productos_id']?>"><img src="<?=base_url()?>assets/img/carrito.png" alt="Carrito" srcset="<?=base_url()?>assets/img/carrito.png"></a>
                                <a class="btn rounded-pill bg-white" href="<?=base_url()?>tienda/addfavorito/<?=$producto['productos_id']?>"><img src="<?=base_url()?>assets/img/favorito.png" alt="Favorito" srcset="<?=base_url()?>assets/img/favorito.png"></a>
                                <a class="btn rounded-pill bg-white" href="#compartir"><img src="<?=base_url()?>assets/img/sharing.png" alt="Compartir" srcset="<?=base_url()?>assets/img/sharing.png"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  
                }else{ ?>
                <div class="col-12 vista-reel">
                    <div class="row h-100">
                        <div class="col-7 d-table h-100">
                            <video preload="auto" class="my-player" id="my-player" webkit-playsinline  playsinline autoplay muted loop>
                                <source src="<?=base_url().$producto['productos_video']?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="play-video" onclick="controlvideo('#my-player','#my-player-icon');">
                                <span id="my-player-icon" class="d-none icon-play3"></span>
                            </div>
                            <div class="loading-video d-none">
                                <img src="<?=base_url()?>assets/img/loading-video.gif" alt="Loading...">
                            </div>
                            <div class="w-100 controles-reel">
                                <div class="row py-4">
                                    <!--<div class="col-9 product-info">
                                        <div class="d-table w-100 h-100">
                                            <div class="d-table-cell align-bottom">
                                                <h5 class="mb-0 py-3 color-white"><?=$producto['productos_titulo']?> - $ <?=number_format($producto['productos_precio'], 2, ',', '.')?></h5>
                                                <p class="color-white"><?=$producto['productos_descripcion_corta']?> ...<a class="" onclick="modalInfo(<?=$producto['productos_id']?>);" href="#vermas">más</a>
                                            </div>
                                        </div>
                                    </p>
                                    </div>-->
                                    <div class="col-3 acciones-reel pl-4 text-left">
                                        <!--<a class="btn rounded-pill btn-primary" href="<?=base_url()?>tienda/addcart/<?=$producto['productos_id']?>"><img src="<?=base_url()?>assets/img/carrito.png" alt="Carrito" srcset="<?=base_url()?>assets/img/carrito.png"></a>
                                        <a class="btn rounded-pill bg-white" href="<?=base_url()?>tienda/addfavorito/<?=$producto['productos_id']?>"><img src="<?=base_url()?>assets/img/favorito.png" alt="Favorito" srcset="<?=base_url()?>assets/img/favorito.png"></a>
                                        <a class="btn rounded-pill bg-white" href="#compartir"><img src="<?=base_url()?>assets/img/sharing.png" alt="Compartir" srcset="<?=base_url()?>assets/img/sharing.png"></a>-->
                                        <a class="btn rounded-pill bg-white" href="#compartir"><img src="<?=base_url()?>assets/img/sharing.png" alt="Compartir" srcset="<?=base_url()?>assets/img/sharing.png"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 h-100 d-table bg-light px-0" >
                            <div class="col-12 h-100 normal-scroll scroll-y scroll-pers">
                                <div class="row">
                                    <div class="col-12 product-info">
                                        <h5 class="mb-0 py-3"><?=$producto['productos_titulo']?></h5>
                                        <p><?=$producto['productos_descripcion_corta']?></p>
                                        <div class="row">
                                            <div class="col-6">
                                                <h4 class="precio-info mb-0 py-3">$ <?=number_format($producto['productos_precio'], 2, ',', '.')?></h4>
                                            </div>
                                            <div class="col-6 acciones-reel pl-4 text-right">
                                                <a class="btn rounded-pill bg-white mr-4" href="<?=base_url()?>tienda/addfavorito/<?=$producto['productos_id']?>"><img src="<?=base_url()?>assets/img/favorito.png" alt="Favorito" srcset="<?=base_url()?>assets/img/favorito.png"></a>
                                                <a class="btn rounded-pill bg-white" href="#compartir"><img src="<?=base_url()?>assets/img/sharing.png" alt="Compartir" srcset="<?=base_url()?>assets/img/sharing.png"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 py-3">
                                        <div class="row">
                                            <div class="col-4 pr-0">
                                                <div class="control-quanty d-table">
                                                    <div class="d-table-cell align-middle">
                                                        <span onclick="restar('#quanty-<?=$producto['productos_id']?>');" class="menos">-</span>
                                                        <input type="number" name="quanty" id="quanty-<?=$producto['productos_id']?>" max="" min="1" value="1">
                                                        <span onclick="sumar('#quanty-<?=$producto['productos_id']?>');" class="mas">+</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <button class="btn btn-addcart w-100" onclick="addCart(<?=$producto['productos_id']?>,'#quanty-<?=$producto['productos_id']?>');" type="button"> AÑADIR AL CARRITO <img src="<?=base_url()?>assets/img/carrito.png" alt="Carrito" srcset="<?=base_url()?>assets/img/carrito.png"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <p class="my-2">Vendido por: <a class="f-12 bold" href="#">Serenity Box</a></p>
                                        <p class="my-2">Ubicación: <span class="bold">Bogotá, Colombia</span></p>
                                        <p class="my-2">Valor del envío: <span class="bold">Gratis</span></p>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-4 bold">Métodos de pago</p>
                                        <ul class="image-method-pay">
                                        <li><img src="<?=base_url()?>assets/img/methods/logo_payu.png" alt="Pagos"></li>
                                        <li><img src="<?=base_url()?>assets/img/methods/logo_pse.png" alt="Pagos"></li>
                                        <li><img src="<?=base_url()?>assets/img/methods/logo_mastercard.png" alt="Pagos"></li>
                                        <li><img src="<?=base_url()?>assets/img/methods/logo_visa.png" alt="Pagos"></li>
                                        <li><img src="<?=base_url()?>assets/img/methods/logo_ame.png" alt="Pagos"></li>
                                        <li><img src="<?=base_url()?>assets/img/methods/logo_dinner.png" alt="Pagos"></li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-12 aditional-info py-3">
                                        <div class="col-12 text-center">
                                        <p>
                                            <span class="tag-descripcion bold">Descripción</span>
                                        </p>
                                        </div>
                                        <p class="bold my-3 text-center">Información adicional</p>
                                        <p><?=$producto['productos_descripcion_larga']?></p>
                                    </div>
                                    <div class="col-12 bottom-desc-reel">

                                    </div>
                                </div>
                                
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

<!-- Modal -->
<div class="modal fade" id="ModalInfo" tabindex="-1" role="dialog" aria-labelledby="ModalInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" id="content-modal">

        </div>
    </div>
</div>
