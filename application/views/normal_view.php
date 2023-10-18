<?php
if (!isset($vista)) {
	$this->load->view('themes/'.$this->config->item('app_theme').'/header');
	
	//Header
	if(isset($custom_header)){
		$this->load->view('themes/'.$this->config->item('app_theme').'/'.$custom_header);
	}else{
		$this->load->view('themes/'.$this->config->item('app_theme').'/default_header');
	}

	//View
	$this->load->view('themes/'.$this->config->item('app_theme').'/'.$view);
	
	//Footer
	if(isset($custom_footer)){
		$this->load->view('themes/'.$this->config->item('app_theme').'/'.$custom_footer);
	}else{
		$this->load->view('themes/'.$this->config->item('app_theme').'/default_footer');
	}

	$this->load->view('themes/'.$this->config->item('app_theme').'/footer');
}elseif ($vista=="front") {
	$this->load->view('themes/'.$this->config->item('app_theme_front').'/header');
	
	//Header
	if(isset($custom_header)){
		$this->load->view('themes/'.$this->config->item('app_theme_front').'/'.$custom_header);
	}else{
		$this->load->view('themes/'.$this->config->item('app_theme_front').'/default_header');
	}

	//View
	$this->load->view('themes/'.$this->config->item('app_theme_front').'/'.$view);
	
	//Footer
	if(isset($custom_footer)){
		$this->load->view('themes/'.$this->config->item('app_theme_front').'/'.$custom_footer);
	}else{
		$this->load->view('themes/'.$this->config->item('app_theme_front').'/default_footer');
	}

	$this->load->view('themes/'.$this->config->item('app_theme_front').'/footer');
}
?>
