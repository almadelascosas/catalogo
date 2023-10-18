<?php
if ($productos->num_rows() == 0) { ?>
<div class="col-12 text-center">
    <p>No hay productos para mostrar</p>
</div>
<?php
}
foreach ($productos->result_array() as $key => $value) { ?>
<div class="col-md-3 col-6 px-2">
    <div class="products mb-4">
        <a href="<?=base_url()?>tienda/single/<?=$value['productos_id'].'/'.$value['productos_titulo']?>">
            <div class="image">
                <?php
                if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) { ?>
                <img src="<?=base_url().$value['medios_url']?>" alt="<?=$value['medios_titulo']?>" srcset="<?=base_url().$value['medios_url']?>">
                <?php
                }else{ ?>
                    <img src="<?=base_url()?>assets/img/Not-Image.png" alt="Not image" srcset="<?=base_url()?>assets/img/Not-Image.png">
                <?php
                }
                ?>
            </div>
            <div class="info">
                <p class="my-2"><?=$value['productos_titulo']?></p>
                <p class="price my-2">$ <?=number_format($value['productos_precio'], 2, ',', '.')?></p>
            </div>
        </a>
    </div>
</div>
<?php
}
?>