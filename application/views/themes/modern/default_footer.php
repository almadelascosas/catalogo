
<div class="col-12 footer float-left py-4 px-md-5">
    <div class="d-none d-sm-none d-md-block">
        <div class="row" style="padding: 15px 0 35px;">
            <div class="col-md-3"></div>
            <div class="col-md-3 text-left">
                <h3 style="color:#5F5F5F">Descarga la App</h3>
                <span style="color:#7E7E7E; line-height: 1.2em;">Ahora puedes tener un universo lleno de productos unicos en tu celular. Disponible patra iOS y Android</span>
            </div>
            <div class="col-md-3 text-center">
                <div class="row">
                    <div class="col-12">
                        <a href="https://apps.apple.com/co/app/alma-de-las-cosas/id6444680132" target="_blank"><img src="assets/img/descargar_appstore.png?<?=rand()?>" alt="descargar de AppStore"></a>
                    </div>
                    <div class="col-12" style="height:25px;"></div>
                    <div class="col-12">
                        <a href="https://play.google.com/store/apps/details?id=com.almadelascosas.alma_de_las_cosas_app" target="_blank"><img src="assets/img/descargar_playstore.png?<?=rand()?>" alt="descargar de PlayStore"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12" style="padding: 10px 0;"></div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12" style="height:40px;">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-12 text-center px-md-3 px-5">
            <div class="logo-footer mx-auto">
                <img class="mx-auto" src="assets/img/logo_alma_footer.png" alt="Logo">
            </div>
        </div>
        <div class="col-md-4 col-12">
            <nav class="menu-footer">
                <ul>
                    <li><a href="<?=base_url('regalos-corporativos')?>">Para empresas</a></li>
                    <?php $linkUser=(isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']!=NULL)?'mi-cuenta':'auth/login' ?>
                    <li><a href="<?=base_url($linkUser)?>">Mi cuenta</a></li>
                    <li><a href="<?=base_url('politica-de-privacidad')?>">Política de Privacidad</a></li>
                    <li><a href="<?=base_url()?>">Preguntas frecuentes</a></li>
                    <li><a href="<?=base_url()?>">Contáctanos</a></li>
                    <li><a href="<?=base_url('rastrea-tu-pedido')?>">Rastrea tu pedido</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-md-4 d-md-block d-none">
            <form action="" method="POST" class="col-md-9 col-12 px-0 newsletter border-footer-mobile">
                <h2 class="text-light text-md-left text-center">Suscríbete al newsletter</h2>
                <div class="entrada">
                    <input type="text">
                    <button type="submit">Suscribirme</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-12 copyright pb-3 px-md-5 float-left">
    <div class="row">
        <div class="col-12 d-block d-md-none text-center py-4">
            <ul class="list-inline">
                <li><a class="text-light px-3" href="https://www.facebook.com/almadelascosas"><span class="icon-facebook"></span></a></li>
                <li><a class="text-light px-3" href="#"><span class="icon-twitter"></span></a></li>
                <li><a class="text-light px-3" href="https://instagram.com/almadelascosas?igshid=YmMyMTA2M2Y="><span class="icon-instagram"></span></a></li>
            </ul>
        </div>
        <div class="col-md-6 col-12 d-table text-md-left text-center">
            <p class="m-0 d-table-cell align-middle">
                Copyright © 2022 - Todos los derechos reservados<br>
                Powered by Alma de las Cosas
            </p>
        </div>
        <div class="col-6 d-md-table d-none text-right">
            <ul class="list-inline d-table-cell align-middle">
                <li><a class="text-light" href="https://www.facebook.com/almadelascosas"><span class="icon-facebook"></span></a></li>
                <li><a class="text-light" href="#"><span class="icon-twitter"></span></a></li>
                <li><a class="text-light" href="https://instagram.com/almadelascosas?igshid=YmMyMTA2M2Y="><span class="icon-instagram"></span></a></li>
            </ul>
        </div>
    </div>
</div>

<?php echo whatsappButton(); ?>

<!-- Modal Ubicación -->
<div class="modal fade bd-example-modal-sm" id="modalUbicacion" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog bd-example-modal-sm modal-dialog-centered" role="document">
    <div class="modal-content modal-content-ubicacion">
        <div class="col-10 mod-header-ubicacion">
            <h5 class="m-0">Ubicación</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="col-12 mod-body-ubicacion p-4">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-md-3 col-12 text-center mb-md-0 mb-4">
                            <img class="image-icon-ubicacion" src="<?=base_url('assets/img/icono-ubicacion.png')?>" alt="Image">
                        </div>
                        <div class="col-md-9 col-12">
                            <h6 id="titulo_ubi">Elige donde recibir pedidos</h6>
                            <p id="parrafo_ubi">Selecciona el lugar donde deseas recibir tus compras. completando esto podrás ver productos para entrega el mismo día.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 py-2 pr-md-2">
                    <label class="w-100">
                        Selecciona tu departamento
                        <select onchange="cargarMunicipios();" class="form-control" name="departamento_session" id="departamento_session">
                            <option value="">Seleccione...</option>
                            <?php
                            $departamentos = getDepartamentos_new();
                            foreach ($departamentos as $key => $value) { ?>
                            <option <?php if(isset($_SESSION['departamento_session']) && $value['id_departamento']==$_SESSION['departamento_session']){ echo "selected"; } ?> value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <script>
                <?php 
                $muniSession = 0;
                $dptoSession = 0;
                if(isset($_SESSION['municipio_session'])){
                    $dptoSession = intval($_SESSION['departamento_session']);
                    $muniSession = intval($_SESSION['municipio_session']);
                }
                $dptoUsuario = $this->session->userdata('login_user');
                if($muniSession === 0) {
                    if(isset($dptoUsuario['usuarios_municipio'])){
                        $dptoSession = intval($dptoUsuario['usuarios_departamento']);
                        $muniSession = intval($dptoUsuario['usuarios_municipio']);
                    }
                }
                ?>
                var cargaMun = <?=($muniSession!==0)?1:0;?>;
                </script>
                <div class="col-md-6 col-12 py-2 pl-md-2">
                    <label class="w-100">
                        Selecciona tu Ciudad
                        <select class="form-control" name="municipio_session" id="municipio_session">
                            <option value="">Seleccione...</option>
                            <?php                            
                            if($muniSession!==0) {
                                $municipios = getMunicipios($dptoSession);
                                foreach ($municipios as $key => $value) { 
                            ?>
                            <option <?php if(intval($value['id_municipio'])===intval($muniSession)){ echo "selected"; } ?> value="<?=$value['id_municipio']?>"><?=$value['municipio']?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </label>
                </div>
                <div class="col-12 text-center py-4">
                    <?php
                    if (isset($_SESSION['cart']) && $_SESSION['cart']!=NULL && $_SESSION['cart']!=array()) {
                        $localidad = 0;
                        for ($i=0; $i < count($_SESSION['cart']); $i++) { 
                            if ($_SESSION["cart"][$i]['productos_envio_nacional']==0) {
                                $localidad=1;
                            }
                        }
                    }else{
                        $localidad = 0;
                    }

                    if (!isset($_SESSION['municipio_session']) || $localidad==0) { ?>
                    <button onclick="ubicacionNueva();" class="btn d-none btn-mod-ubicacion btn_ubi">Listo</button>
                    <?php
                    }else{ ?>
                    <button onclick="modalCambioUbi();" class="btn d-none btn-mod-ubicacion btn_ubi">Listo</button>
                    <?php
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
  </div>
</div>


<div class="w-100 pop-cambio-ubi">
    <div class="content">
        <div class="top">
            <h4>Cambio de ubicación</h4>
            <button type="button" class="close" onclick="closeCambioUbi();">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="cuerpo">
            <p>El producto que tienes en el carrito proviene de una ubicación diferente a la que acabas de seleccionar</p>
            <ul class="list-items" id="prodReqUbi">
                <li>
                    <span class="image"></span>
                    <p class="text">
                        <span>Set de picnic</span><br>
                        <strong>$78.000</strong>
                    </p>
                </li>
            </ul>
            <p class="text-center">
                ¿Deseas vaciar el carrito de compras?
                <button onclick="vacioUbi();" class="btn btn-vaciar text-white">Sí, vaciar</button>
            </p>
        </div>
    </div>
</div>


<?php
if ($this->uri->segment(2)=="categorias" && !isset($_SESSION['municipio_session']) && (isset($categoria_padre) && $categoria_padre['categorias_ubicacion_requerido']==1)) { 

?>
<div class="capa-modal-ub-req">

</div>
<div class="modal-ubicacion-obligatoria">
    <div class="header">
        <h2>
            <a class="icono" href="/"><span class=" icon-arrow-left2"></span></a> Comida y Bebida
        </h2>
    </div>
    <div class="main-ub col-12">
        <div class="row">
            <div class="logos-ub col-12 col-md-6">
                <ul>
                    <li><img class="lazy" src="assets/img/logo.png" alt="Logo"></li>
                    <?php $image = image($categoria_padre['categorias_imagen_enlace']); ?>
                    <li><img class="lazy" src="<?=$image?>" alt="Logo"></li>
                </ul>
            </div>
            <div class="col-12 col-md-6 pt-md-5">
                <h4>Disfruta nuestra oferta</h4>
                <p>Para ofrecer productos que sean aplicables a tu zona por favor selecciona tu ciudad.</p>
            </div>
            <div class="col-12 "><!-- pt-5 -->
                    <!-- 
                    <div class="col-md-6 col-12 py-2 pr-md-2">
                        <label class="w-100">
                            Selecciona tu departamento
                            <select onchange="cargarMunicipios($(this).val(),'#municipio_session_req');" class="form-control" name="departamento_session" id="departamento_session_req">
                                <option value="">Seleccione...</option>
                                <?php
                                /*
                                $departamentos = getDepartamentos();
                                foreach ($departamentos->result_array() as $key => $value) {
                                    print '<option '.(isset($_SESSION['departamento_session']) && $value['id_departamento']==$_SESSION['departamento_session'])?"selected":"";.' value="'.$value['id_departamento'].'">'.$value['departamento'].'</option>';
                                }
                                */
                                ?>
                            </select>
                        </label>
                    </div>
                     -->

                    <script>
                    var cargaMun = <?=(!isset($_SESSION['municipio_session']) || $_SESSION['municipio_session']==0)?1:0?>
                    </script>

                    <div class="col-12 py-2 pl-md-2">
                        <label class="w-100">
                            Selecciona tu Ciudad
                            <select class="form-control" name="municipio_session" id="municipio_session_req">
                                <option value="">Seleccione...</option>
                                <?php
                                $muniSession=(isset($_SESSION['municipio_session']))?intval($_SESSION['municipio_session']):0;
                                $locations = explode('/', $categoria_padre['categorias_ubicacion_ciudad']);
                                $arrayMuni=[];
                                foreach ($locations as $key => $localidad) {
                                    $local = explode(',', $localidad);
                                    $arrayMuni[]=$local[1];
                                }

                                $municipios = getMunicipioByIdArray($arrayMuni);
                                foreach ($municipios as $key => $value) { 
                                ?>        
                                <option <?=(intval($value['id_municipio'])===$muniSession)?"selected":""?> value="<?=$value['id_municipio']?>"><?=$value['municipio']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-12 text-center">
                        <button onclick="ubicacionNueva('#departamento_session_req','#municipio_session_req');" class="btn btn-mod-ubicacion btn_ubi">Listo</button>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>


