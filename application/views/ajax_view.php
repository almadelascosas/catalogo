<?php
	//View
	if(isset($vista)){
		$this->load->view('themes/'.$this->config->item('app_theme_front').'/'.$view);
	}else{
		$this->load->view('themes/'.$this->config->item('app_theme').'/'.$view);
	}
?>
