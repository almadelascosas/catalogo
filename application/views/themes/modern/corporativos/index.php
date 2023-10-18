<script type="text/javascript">
fbq('track', 'ViewContent', { content_name: "Página de Corporativos" });
</script>
<main class="cuerpo col-12 float-left">
    <div class="row">
        <section class="col-12 p-0 banner-principal">
            <?php
            $image = image($banner_corporativos['banner_imagen_url']);
            $image_mobile = image($banner_corporativos['banner_imagen_mobile_url']);
            ?>
            <?php if($banner_corporativos['banner_enlace']!=='' && $banner_corporativos['banner_enlace']!==null){ ?> <a href="<?=$banner_corporativos['banner_enlace']?>" target="<?=$banner_corporativos['banner_target']?>"> <?php } ?>
            <img class="w-100 d-none d-md-block" src="<?=$image?>" alt="Desktop">
            <img class="w-100 d-md-none" src="<?=$image_mobile?>" alt="Mobile">
            <?php if($banner_corporativos['banner_enlace']!=='' && $banner_corporativos['banner_enlace']!==null){ ?> </a> <?php } ?>
        </section>
        <section class="col-12 col-md-10 offset-md-1 sc-soluciones">
            <div class="row">
                <div class="col-12 text-md-left text-center mb-md-0 mb-3">
                    <h2>Nuestras soluciones</h2>
                    <p class="mb-4">A la medida de lo que tu empresa necesita</p>
                </div>
                <div class="col-md-4 col-12 mb-md-0 mb-3">
                    <a href="javascript:void(0)" class="btnTranfer" data-toggle="modal" data-toggle="modal" data-target="#modalReference" data-href="https://kanbai.co/catalogo/regalos">
                        <div class="card item-regalos">
                            <div class="icono-reg">
                                <img src="assets/img/corporativos/Regalos_Empresariales.png" alt="Regalos Empresariales">
                            </div>
                            <div class="text-reg">
                                <h3>Regalos Corporativos</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-12 mb-md-0 mb-3">
                    <a href="javascript:void(0)" class="btnTranfer" data-toggle="modal" data-toggle="modal" data-target="#modalReference" data-href="https://kanbai.co/catalogo/regalos">
                        <div class="card item-regalos">
                            <div class="icono-reg">
                                <img src="assets/img/corporativos/Regalos_Eventos.png" alt="Regalos Eventos">
                            </div>
                            <div class="text-reg">
                                <h3>Regalos para Eventos</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-12 mb-md-0 mb-3">
                    <a href="javascript:void(0)" class="btnTranfer" data-toggle="modal" data-target="#modalReference" data-href="https://kanbai.co/catalogo/merchandising">
                        <div class="card item-regalos">
                            <div class="icono-reg">
                                <img src="assets/img/corporativos/Merchandising.png" alt="Merchandising">
                            </div>
                            <div class="text-reg">
                                <h3>Merchandising corporativo</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- INICIO MODAL -->
            <div class="modal fade" id="modalReference" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="border-radius: 2.3rem;">
                        <div class="row">
                            <div class="col-md-12"><br>&nbsp;<br></div>
                        </div>
                        <div class="row">
                            <div class="offset-md-3 col-md-2 offset-2 col-3 text-center">
                                <img class="d-none d-sm-none d-md-block" src="assets/img/logo_cabecera.png">
                                <img class="d-block d-sm-block d-md-none" style="position: relative; left: -13px; max-width: 92%; margin:-6px auto;" src="assets/img/logo_cabecera.png">
                            </div>
                            <div class="col-md-2 col-1 text-center mb-md-0 mb-3">
                                <i class="fa fa-arrow-circle-right fa-2x" aria-hidden="true"></i>
                            </div>
                            <div class="col-md-2 col-4 text-center">
                                <img class="d-none d-sm-none d-md-block" style="height:34px; width: auto; max-width: 200% !important;" src="assets/img/logo-kanbai-color.png">
                                <img class="d-block d-sm-block d-md-none" style="position:  relative;left: 16px; height:36px; width: auto; max-width: 116% !important;" src="assets/img/logo-kanbai-color.png">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-12 " style="height: 25px;"><br>&nbsp;<br></div>
                        </div>
                        <div class="row">
                            <div class="offset-md-1 col-md-10 col-12 text-center" >
                                <h2 class="titulo_corporativo">Te estamos transfiriendo a Kanbai</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-1 col-md-10 offset-1 col-10 txttrans">
                                La plataforma de <b>Alma de las Cosas</b> creada 100% para empresas. Acá encontrarás precios con descuentos, posibilidad de personalización, atención profesional y mucho más.
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-4 col-md-4 offset-4 col-3 contador text-center">  

                                <img src="assets/img/loading/yellow_3.gif">
                                <div id="countdown" style="display:none"></div>
                         
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIN MODAL -->
        </section>
        <section class="col-12 col-md-10 offset-md-1 sc-services-detail">
            <div class="row">
                <div class="col-12 d-md-block d-none">
                    <h2>¿Porqué nosotros?</h2>
                </div>
                <div class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card-detail">
                        <div class="icono">
                            <div class="d-table-cell align-middle">
                                <div class="image">
                                    <img src="assets/img/corporativos/Cumplimiento.png" alt="Cumplimiento">
                                </div>
                            </div>
                        </div>
                        <div class="text">
                            <div class="d-table-cell align-middle">
                                <h5 class="m-0">Cumplimento</h5>
                                <p class="m-0">Entregamos en tu oficina o en la casa de cada persona</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card-detail">
                        <div class="icono">
                            <div class="d-table-cell align-middle">
                                <div class="image">
                                    <img src="assets/img/corporativos/Amor_Detalle.png" alt="Amor por cada detalle">
                                </div>
                            </div>
                        </div>
                        <div class="text">
                            <div class="table-cell align-middle">
                                <h5 class="m-0">Amor por cada detalle</h5>
                                <p class="m-0">Cada detalle es importante, por eso cuidamos la experiencia</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card-detail">
                        <div class="icono">
                            <div class="d-table-cell align-middle">
                                <div class="image">
                                    <img src="assets/img/corporativos/Servicios_Pre_Pos.png" alt="Servicios pre y pos">
                                </div>
                            </div>
                        </div>
                        <div class="text">
                            <div class="table-cell align-middle">
                                <h5 class="m-0">Servicios pre y pos</h5>
                                <p class="m-0">Personal preparado para ayudarte en todo el proceso</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card-detail">
                        <div class="icono">
                            <div class="d-table-cell align-middle">
                                <div class="image">
                                    <img src="assets/img/corporativos/Felicidad_Ofi.png" alt="Más felicidad en la ofi">
                                </div>
                            </div>
                        </div>
                        <div class="text">
                            <div class="table-cell align-middle">
                                <h5 class="m-0">Más felicidad en la ofi</h5>
                                <p class="m-0">Potencia el vínculo de los miembros con la empresa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="col-12 col-md-10 offset-md-1 sc-clientes">
            <div class="row">
                <div class="col-12">
                    <h2>Clientes de diferentes sectores</h2>
                    <p>Empresas de todos los tamaños confían en nosotros</p>
                </div>
                <div class="mt-4 owl-carousel carousel-clientes d-md-block d-none" id="carrusel-clientes">
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Logo_skandia.png" alt="Logo_skandia">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Liberty_Seguros.png" alt="Liberty_Seguros">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Colmed.png" alt="Colmed">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/sophos.png" alt="sophos">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Devimar.png" alt="Devimar">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Percos.png" alt="Percos">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/globant.png" alt="globant">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/laika.png" alt="laika">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/epayco.png" alt="epayco">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/LOGO_GIVELO.png" alt="LOGO_GIVELO">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Audifarma.png" alt="Audifarma">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Boehringer.png" alt="Boehringer">
                    </div>
                </div>
                <div class="owl-carousel carousel-clientes d-md-none" id="carrusel-clientes-mobile">
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Logo_skandia.png" alt="Logo_skandia">
                        <img class="icono-cliente" src="assets/img/corporativos/Liberty_Seguros.png" alt="Liberty_Seguros">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Colmed.png" alt="Colmed">
                        <img class="icono-cliente" src="assets/img/corporativos/sophos.png" alt="sophos">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Devimar.png" alt="Devimar">
                        <img class="icono-cliente" src="assets/img/corporativos/Percos.png" alt="Percos">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/globant.png" alt="globant">
                        <img class="icono-cliente" src="assets/img/corporativos/epayco.png" alt="epayco">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/LOGO_GIVELO.png" alt="LOGO_GIVELO">
                        <img class="icono-cliente" src="assets/img/corporativos/Audifarma.png" alt="Audifarma">
                    </div>
                    <div class="item">
                        <img class="icono-cliente" src="assets/img/corporativos/Boehringer.png" alt="Boehringer">
                    </div>
                </div>
            </div>
        </section>
        <section class="col-12 col-md-10 offset-md-1 sc-contacto">
            <div class="row">
                <form class="col-md-10 offset-md-1 col-12 contact-form" action="<?=base_url('corporativos/mailform')?>" method="post">
                    <div class="row">
                        <div class="col-md-2 col-12 position-relative">
                            <img class="paper-plane" src="assets/img/corporativos/paper-plane.png" alt="paper plane">
                        </div>
                        <div class="col-md-10 col-12 p-md-5 pt-5">
                            <h4 class="text-md-left text-center">Pongámonos en contacto</h4>
                            <p class="mb-4 text-md-left text-center">Completa este formulario y uno de nuestros asesores se pondrá en contacto contigo</p>
                            <div class="form-group">
                                <label for="nombre_completo">Nombre Completo</label>
                                <input class="form-control" type="text" name="nombre_completo" id="nombre_completo">
                            </div>
                            <div class="form-group">
                                <label for="correo_electronico">Correo Electrónico</label>
                                <input class="form-control" type="email" name="correo_electronico" id="correo_electronico">
                            </div>
                            <div class="form-group">
                                <label for="numero_celular">Número de Celular</label>
                                <input class="form-control" type="tel" name="numero_celular" id="numero_celular">
                            </div>
                            <div class="form-group">
                                <label for="mensaje">Explícanos brevemente que necesidad tienes</label>
                                <textarea class="form-control" name="mensaje" id="mensaje" rows="4"></textarea>
                            </div>
                            <div class="form-group text-center py-4 mb-0 pt-5">
                                <button class="btn btn-contact" type="submit">Enviar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>
<div class="d-md-none d-block boton-ws text-center w-100">
    <a class="btn btn-whatsapp-header" href="https://api.whatsapp.com/send?phone=+573502045177&text=Hola Alma de las Cosas! Me interesa la línea corporativa para mi empresa">
        Contactarme con un asesor/a <span class="icon-whatsapp"></span>
    </a>
</div>

