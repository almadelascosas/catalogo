<div class="scroll-modal">
  <div class="">
    <div class="col-12 py-2">
      <a class="btn btn-back" data-dismiss="modal" aria-label="Close" href="#"><span class="arrow-back"><</span></a>
    </div>
    <div class="col-12 product-info">
      <h5 class="mb-0 py-3"><?=$producto['productos_titulo']?></h5>
      <p><?=$producto['productos_descripcion_corta']?></p>
      <h4 class="precio-info mb-0 py-3">$ <?=number_format($producto['productos_precio'], 2, ',', '.')?></h4>
    </div>
    <form class="col-12 py-3" method="post" action="<?=base_url()?>tienda/addcart/<?=$producto['productos_id']?>">
      <div class="row">
        <div class="col-4 pr-0">
          <div class="control-quanty d-table">
            <div class="d-table-cell align-middle">
              <span class="menos">-</span>
              <input type="number" name="quanty" id="quanty" max="" min="1" value="1">
              <span class="mas">+</span>
            </div>
          </div>
        </div>
        <div class="col-8">
          <button class="btn btn-addcart w-100" onclick="abrirCart();" type="submit">AÑADIR AL CARRITO</button>
        </div>
      </div>
    </form>
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
  </div>
</div>
