<?php $menu = obtenerMenu(); ?>
<header class="w-100 float-left px-2 py-md-2 py-0">
    <div class="col-12 px-md-5 pr-2 pl-0">
        <div class="row">
            <div class="col-md-1 col-4 colunm-1-header">
                <div class="h-100 w-100 d-table">
                    <div class="d-table-cell align-middle">
                        <div class="logo">
                            <a href="/">
                                <img src="assets/img/logo_cabecera.png" alt="Logo">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 d-md-block d-none colunm-2-header">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="d-table h-100 w-100">
                            <div class="d-table-cell align-middle">
                                <?php
                                $funcion = "modalUbicacion('Elige donde recibir pedidos','Selecciona el lugar donde deseas recibir tus compras. completando esto podrás ver productos para entrega el mismo día.')";
                                ?>
                                <a class="btn-ubicacion btn btn-block mt-md-2" href="#modalUbicacion" onclick="<?=$funcion?>">
                                    <?php
                                    if (!isset($_SESSION['municipio_session']) || $_SESSION['municipio_session']==0) { ?>
                                    <span id="ubicacion-desktop">
                                        <span class="icon-location"></span>
                                        <span class="deks-ubi">Ingresa tu </span>ubicación
                                    </span>
                                    <?php
                                    }else{ ?>
                                    <span class="one-line-ellipsis" id="ubicacion-desktop">
                                        <span class="icon-location"></span><?=$_SESSION['municipio_nombre']?>, <?=substr($_SESSION['departamento_nombre'], 0, 3)?>
                                    </span>
                                    <?php
                                    }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <form class="w-100 buscador mt-md-2" action="<?=base_url('tienda')?>" method="get">
                            <input type="text" name="search" id="search" placeholder="Busca lo que quieras" value="<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>">
                            <button type="submit" id="btnSearchHeader" name="btnSearchHeader" alt="Boton busqueda general"><span class="icon-search"></span></button>
                        </form>
                    </div>
                    <div class="col-12">
                        <nav class="menu mt-md-2">
                            <div class="col-12 d-md-none d-block bg-gray">
                                <h4>Categorías</h4>
                                <span class="close-menu d-block float-right pr-4 d-md-none" aria-hidden="true">&times;</span>
                            </div>
                            <ul>
                                <?php
                                foreach ($menu['menu']->result_array() as $key => $value) {
                                    if ($value['menu_padre_id']==0) {
                                    ?>
                                <li>
                                    <a href="<?=base_url()."tienda/categorias/".$value['menu_categorias_id']."/".$value['categorias_nombre']?>">
                                        <?=$value['menu_texto']?>
                                    </a>
                                    <ul class="submenu">
                                    <?php
                                    $cont = 0;
                                    foreach ($menu['subcategorias']->result_array() as $key2 => $value2) {
                                        if ($value2['categorias_padre']==$value['categorias_id']) {
                                            $cont++;
                                        ?>
                                        <li><a href="<?=base_url()."tienda/categorias/".$value2['categorias_id']."/".$value2['categorias_nombre']?>"><?=$value2['categorias_nombre']?></a></li>
                                        <?php
                                        }
                                    }
                                    ?>
                                    </ul>
                                    <?php
                                    if ($cont > 0) { ?>
                                    <span class="flecha_menu"></span>
                                    <?php
                                    }
                                    ?>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                                <li>
                                    <a href="<?=base_url('regalos-corporativos')?>" target="_blank">Empresas</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-8 colunm-3-header">
                <div class="ml-2 iconos-top main-toggle-padre d-md-none d-block" onclick='menu("abrir");'>
                    <div class="main-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="iconos-top">
                    <ul class="list-inline">
                        <li class="user-profile">
                            <a href="<?=(isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']!==NULL)?base_url('mi-cuenta/orders'):base_url('auth/login')?>">
                                <span class="icon-user"></span>
                            </a>
                        </li>
                        <li class="cart-li">
                            <a href="#" onclick="abrirCart()">
                                <span class="icon-cart"></span>
                            </a>
                        </li>
                        <li class="search-li d-none">
                            <a href="#">
                                <span class="icon-search"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                
            </div>
            <div class="col-12 d-md-none div-buscador my-2">
                <form class="w-100 buscador" action="<?=base_url('tienda')?>" method="get">
                    <input type="text" name="search" id="search" placeholder="Busca lo que quieras" value="<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>">
                    <button type="submit"><span class="icon-search"></span></button>
                </form>
            </div>
        </div>

    </div>
</header>
<div class="banner-ubicacion-mobile col-12" onclick="<?=$funcion?>">
    <?php
    if (!isset($_SESSION['municipio_session']) || $_SESSION['municipio_session']==0) { ?>
    <p id="ubicacion-mobile" class="mb-0 py-2"><span class="icon-location"></span> Ingresa tu ubicación</p>
    <?php
    }else{ ?>
    <p id="ubicacion-mobile" class="mb-0 py-2"><span class="icon-location"></span> Enviar a <?=$_SESSION['departamento_nombre']?>, <?=$_SESSION['municipio_nombre']?></p>
    <?php
    }
    ?>
</div>

<nav class="menu menu-mobile">
    <div class="col-12 d-md-none float-left d-block bg-gray mb-5">
        <h4 class="mb-0 float-left py-4">Categorías</h4>
        <span class="close-menu d-block float-right mt-1 d-md-none" aria-hidden="true">&times;</span>
    </div>
    <ul class="mt-5">
        <?php
        foreach ($menu['menu']->result_array() as $key => $value) {
            if (intval($value['menu_padre_id'])===0) {
                $image = delBackslashIni($value['medios_url']);
                if(!file_exists($image)) $image='assets/img/icono-pred-catergoria.png';
        ?>
        <li>
            <a href="<?=base_url("tienda/categorias/".$value['menu_categorias_id']."/".$value['categorias_nombre'])?>">
                <span class="icono-menu" style="background-image:url(<?=$image.'?'.rand()?>);"></span>
                <?=$value['menu_texto']?>
            </a>
            <ul class="submenu collapse pb-3" id="collapsMenu-<?=$value['menu_id']?>">
                <?php
                $cont = 0;
                foreach ($menu['subcategorias']->result_array() as $key2 => $value2) {
                    if ($value2['categorias_padre']==$value['categorias_id']) {
                        $cont++;
                        $image = delBackslashIni($value2['medios_url']);
                        if(!file_exists($image)) $image='assets/img/icono-pred-catergoria.png';
                ?>
                <li>
                    <a class="py-2" href="<?=base_url("tienda/categorias/".$value2['categorias_id']."/".$value2['categorias_nombre'])?>">
                    <span class="icono-menu" style="background-image:url(<?=$image.'?'.rand()?>);"></span>
                    <?=$value2['categorias_nombre']?>
                    </a>
                </li>
                <?php
                    }
                }
                ?>
            </ul>
            <?php
            if ($cont > 0) { ?>
            <span class="colapsable" data-toggle="collapse" data-target="#collapsMenu-<?=$value['menu_id']?>" aria-expanded="false" aria-controls="collapsMenu-<?=$value['menu_id']?>"></span>
            <?php
            }
            ?>
        </li>
        <?php
            }
        }

        $image='assets/img/icono-pred-catergoria.png';
        ?>
        <li>
            <a href="<?=base_url('regalos-corporativos')?>">
                <span class="icono-menu" style="background-image:url(<?=$image.'?'.rand()?>);"></span>
                Para empresas
            </a>
        </li>
    </ul>
</nav>

<div class="cart col-12 px-0 float-left" id="cart">
    <div class="float-left d-block bg-gray col-12 top-cart">
        <h4 class="mb-0 float-left py-4">Carrito de compras</h4>
        <span class="close-menu d-block float-right mt-1" onclick="cerrarCart();">&times;</span>
    </div>
    <div class="float-left col-12 d-md-block d-none"></div>
    <div class="content-cart float-left" id="content-cart">
        <?php
        $this->load->view("themes/modern/tienda/cart");
        ?>
    </div>
</div>