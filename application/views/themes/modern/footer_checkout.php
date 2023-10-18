
<div class="col-12 footer float-left py-4 pt-5 mt-5 bg-white">
    <div class="row">
        <div class="col-12 text-center">
            <div class="logo-footer mx-auto">
                <img style="max-width:100px;" class="mx-auto" src="<?=base_url()?>assets/img/LOGO_ALMA.png" alt="Logo">
            </div>
        </div>
    </div>
</div>
<div class="col-12 copyright pb-4 px-md-5 float-left bg-white mb-0">
    <div class="row">
        <div class="col-12 text-center">
            <p class="m-0">
                Copyright © 2022 - Todos los derechos reservados<br>
                Powered by Alma de las Cosas
            </p>
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