<?php
$menu_dash = obtenerMenuDash();
?>
<header class="cabecera">
    <nav class="menu">
        <div class="wcfm_menu_logo"> 
			<h4>
			  <a class="wcfm_store_logo_icon" href="#" target="_blank">
                <img src="<?=base_url('assets/img/LOGO_ALMA.png')?>" alt="Store Logo"></a>			  
                <a href="#" target="_blank">
                    Mi tienda
                </a>			
            </h4>
		</div>
        <div class="wcfm_menu_items">
			<?php
			foreach ($menu_dash->result_array() as $key => $value) {
				$uri_segment = strtolower($value['menu_dash_texto']);
				$permisos = explode(",", $value['menu_dash_permiso']);
				$findme = $_SERVER["HTTP_HOST"]."/".$this->uri->segment(1);
				$mystring = $value['menu_dash_enlace'];
				if (in_array($_SESSION['tipo_accesos'],$permisos) || $_SESSION['tipo_accesos']==0) {
				?>
			<a class="wcfm_menu_item mt-2 <?php if(strpos($mystring, $findme)!==false){ echo "active"; } ?>" href="<?=base_url().$value['menu_dash_enlace']?>">
				<span class="wcfmfa fa-chalkboard"></span>
				<span class="text"><?=$value['menu_dash_texto']?></span>
			</a>
			<?php
				}
			}
			?>
			<?php
			if ($_SESSION["tipo_accesos"]==0) { ?>
			<a class="wcfm_menu_item mt-2 <?php if($this->uri->segment(1)=="permisos"){ echo "active"; } ?>" href="<?=base_url()?>permisos">
				<span class="wcfmfa fa-chalkboard"></span>
				<span class="text">Permisos</span>
			</a>
			<?php
			}
			?>
			<a class="wcfm_menu_item mt-2" href="<?=base_url('auth/logout')?>">
				<span class="wcfmfa fa-chalkboard"></span>
				<span class="text">Salir</span>
			</a>
		</div>

    </nav>
</header>