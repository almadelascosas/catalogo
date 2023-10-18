
<div id="categoria-por-vendedor-<?=$categorias_id?>" class="row contenedor-vendedores d-none">
    <?php
        foreach ($vendedores['data'] as $key2 => $value2) { ?> 
    
    <div class="col-6 col-md-2_5 px-2">
        <div class="card-vendedor" style="background-image:url(<?=$value2['imagen_perfil']?>);">
            <a href="<?=base_url()?>tienda/vendor/<?=$value2['usuarios_id']?>/<?=limpiarUri($value2['nombre_vendedor'].' '.$value2['apellido_vendedor'])?>">
                <div class="parrafo w-100">
                    <p class="w-100"><?=$value2['nombre_vendedor']?></p>
                </div>    
            </a>
        </div>
    </div>

    <?php
    }
    ?>
</div>
