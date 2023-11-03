<header class="cabecera">
    <nav class="menu d-none d-sm-none d-md-block">
        <div class="wcfm_menu_logo"> 
            <a href="<?=base_url('panel')?>" target="_blank">
                <img src="assets/img/monograma-alma.png" alt="Store Logo"></a>			  
            </a>			
		</div>
        <div class="wcfm_menu_items">
        	<div style="height:80px"></div>
        	<a href="<?=base_url('panel')?>" class="wcfm_menu_item">
        		<svg height="2.5em" viewBox="0 0 576 512"><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
        	</a>
        	<a href="<?=base_url('catalogo')?>" class="wcfm_menu_item">
        		<svg height="2.7em" viewBox="0 0 384 512"><path d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM112 192H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
        	</a>
        	<a href="<?=base_url('productos')?>" class="wcfm_menu_item">

        		<svg height="2.5em" viewBox="0 0 640 512"><style>svg{fill:#494949}</style><path d="M211.8 0c7.8 0 14.3 5.7 16.7 13.2C240.8 51.9 277.1 80 320 80s79.2-28.1 91.5-66.8C413.9 5.7 420.4 0 428.2 0h12.6c22.5 0 44.2 7.9 61.5 22.3L628.5 127.4c6.6 5.5 10.7 13.5 11.4 22.1s-2.1 17.1-7.8 23.6l-56 64c-11.4 13.1-31.2 14.6-44.6 3.5L480 197.7V448c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64V197.7l-51.5 42.9c-13.3 11.1-33.1 9.6-44.6-3.5l-56-64c-5.7-6.5-8.5-15-7.8-23.6s4.8-16.6 11.4-22.1L137.7 22.3C155 7.9 176.7 0 199.2 0h12.6z"/></svg>
        	</a>
        	<a href="#" class="wcfm_menu_item">
        		<i class="fas fa-shopping-cart fa-3x" aria-hidden="true"></i>
        	</a>
        	<a href="#" class="wcfm_menu_item">
        		<i class="fas fa-user-circle fa-3x" aria-hidden="true"></i>
        	</a>
			
			<a class="wcfm_menu_item mt-2" href="<?=base_url('auth/logout')?>">
				<i class="fas fa-sign-out fa-3x" aria-hidden="true"></i>
			</a>
		</div>

    </nav>
    <nav class="menu_mobile d-block d-sm-block d-md-none">
    	<div class="row">
    		<div class="col-2 wcfm_menu_logo_mobil">
    			<a href="<?=base_url('panel')?>" target="_blank">
	                <img src="assets/img/monograma-alma.png" alt="Store Logo"></a>			  
	            </a>
    		</div>
    		<div class="col-2 div-menu_mobil">
    			<a href="#" class="wcfm_menu_item_mobil">
	        		<i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
	        	</a>
    		</div>    		
    		<div class="col-2 div-menu_mobil">
    			<a href="<?=base_url('catalogo')?>" class="wcfm_menu_item_mobil">
	        		<i class="fas fa-clipboard fa-2x" aria-hidden="true"></i>
	        	</a>
    		</div>
    		<div class="col-2 div-menu_mobil">
    			<a href="<?=base_url('productos')?>" class="wcfm_menu_i_mobiltem">
	        		<svg height="2em" viewBox="0 0 640 512"><style>svg{fill:#494949}</style><path d="M211.8 0c7.8 0 14.3 5.7 16.7 13.2C240.8 51.9 277.1 80 320 80s79.2-28.1 91.5-66.8C413.9 5.7 420.4 0 428.2 0h12.6c22.5 0 44.2 7.9 61.5 22.3L628.5 127.4c6.6 5.5 10.7 13.5 11.4 22.1s-2.1 17.1-7.8 23.6l-56 64c-11.4 13.1-31.2 14.6-44.6 3.5L480 197.7V448c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64V197.7l-51.5 42.9c-13.3 11.1-33.1 9.6-44.6-3.5l-56-64c-5.7-6.5-8.5-15-7.8-23.6s4.8-16.6 11.4-22.1L137.7 22.3C155 7.9 176.7 0 199.2 0h12.6z"/></svg>
	        	</a>
    		</div>
    		<div class="col-2 div-menu_mobil">
    			<a href="#" class="wcfm_menu_item_mobil">
	        		<i class="fas fa-shopping-cart fa-2x" aria-hidden="true"></i>
	        	</a>
    		</div>
    		

    	</div>


    </nav>
</header>
