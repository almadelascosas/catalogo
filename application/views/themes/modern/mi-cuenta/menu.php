<div class="col-md-12 col-11 contenedor-menu" >
    <div class="d-none d-sm-none d-md-block">
        <ul class="menu-perfil">
            <li>
                <center>    
                    <div class="big-picture-user">
                        <img class="lazy" data-src="assets/img/avatar-panel.png" src="assets/img/loading-buffering.gif">
                    </div>
                </center>
            </li>
            <li>
                <center>
                    <h5 class="gris">!Hola, <?=$_SESSION['name'].' '.$_SESSION['lastname']?></h5>
                </center>
            </li>
            <li class="menu-perfil-item">
                <a class="active" href="<?=base_url('mi-cuenta/edit-account')?>"><span><img src="<?=base_url('assets/img/icons/datos.png')?>"> </span> <p>Mis Datos</p></a>
            </li>
            <li class="menu-perfil-item">
                <a href="<?=base_url('mi-cuenta/address')?>"><span><img src="<?=base_url('assets/img/icons/direccion.png')?>"> </span> <p>Direcciones</p></a>
            </li>
            <!--
            <li class="menu-perfil-item">
                <a href="<?=base_url('mi-cuenta')?>"><span><img src="<?=base_url('assets/img/icons/Perfil_Icon.png')?>"> </span> <p>Metodos de Pago</p></a>
            </li>
            -->
            <li class="menu-perfil-item">
                <a href="<?=base_url('mi-cuenta/orders')?>"><span><img src="<?=base_url('assets/img/icons/pedidos.png')?>"> </span> <p>Mis Compras</p></a>
            </li>
            
            
            <li class="menu-perfil-item d-none d-sm-none d-md-block">
                <a href="<?=base_url('auth/logout')?>"><span><img src="<?=base_url('assets/img/icons/exit.png')?>"> </span> <p>Salir</p></a>
            </li>
        </ul>
    </div>
    <div class="d-block d-sm-block d-md-none">
        <div class="row">
            <div class="col-12 miinfo">
                <div class="row ">
                    <div class="col-3">  
                        <div class="big-picture-user">
                            <img src="<?=base_url('assets/img/logo.png')?>">
                        </div>
                    </div>
                    <div class="col-9 nom-menumovil">
                        <b>
                        !Hola,<br>
                        <?=$_SESSION['name'].' '.$_SESSION['lastname']?>
                        </b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"><hr></div>
                </div>
                <div class="row">
                    <div class="col-4 text-center">
                        <span class="menu-movil"><img src="<?=base_url('assets/img/icons/datos.png')?>"></span><br>
                        <a class="link-box-movil" href="<?=base_url('mi-cuenta/edit-account')?>">Mis Datos</a>
                    </div>
                    <div class="col-4 text-center">
                        <span class="menu-movil"><img src="<?=base_url('assets/img/icons/direccion.png')?>"> </span>
                        <br>
                        <a class="link-box-movil" href="<?=base_url('mi-cuenta/address')?>">Direcciones</a>
                    </div>
                    <div class="col-4 text-center">
                        <span class="menu-movil"><img src="<?=base_url('assets/img/icons/pedidos.png')?>"></span>
                        <br>
                        <a class="link-box-movil" href="<?=base_url('mi-cuenta/orders')?>">Mis compras</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="col-12">&nbsp;</div>