<main class="cuerpo col-12 float-left">
    <div class="row">
        <!--<div class="col-12 mb-5">
            <div class="row">
                <div class="col-12 bg-checkout">
                    <div class="col-md-10 col-12 offset-md-1 text-md-center">
                        <h2 class="mb-0">Rastrea tu pedido</h2>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="col-12 py-4 orders">
            <form class="col-12 mb-5 col-md-6 offset-md-3 form-contact-rastreo bg-white" method="post" action="<?=base_url('rastreo')?>">
                <div class="row">
                    <?php
                    if (isset($_SESSION['mensaje_error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <p><?=$_SESSION['mensaje_error']?></p>
                    </div>
                    <?php
                    unset($_SESSION['mensaje_error']);
                    }
                    ?>
                    <div class="col-12 image mb-4 text-center">
                        <img style="max-width:170px;margin:auto;" src="assets/img/Rastreo_pedido.png" alt="Ojito" sizes="120x120" srcset="<?=base_url()?>assets/img/Ojito.png">
                    </div>
                    <div class="col-12">
                        <h2>Rastrea tu pedido</h2>
                        <p>Consulta el estado de tu pedido escribiendo tu correo y el número de orden el cual te llegó con el mail de confirmación.</p>
                    </div>
                    <div class="col-12 form-group">
                        <label for="id">
                            Número del pedido
                        </label>
                        <input class="form-control" type="number" name="id" id="id">
                    </div>
                    <div class="col-12 form-group">
                        <label for="correo">
                            Correo electrónico
                        </label>
                        <input class="form-control" type="email" name="correo" id="correo">
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button class="btn btn-contact bg-success">Rastrear pedido</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>