<?php

class Asset_controller extends CI_Controller {

	function Asset_controller()
	{
		parent::__construct();
	}
	
	function index(){
		$this->load->config("assets");
		$asset_folder = $this->config->item("assets_path");
		$type = $this->uri->segment(2);
		$level = $this->uri->segment(3);
		$files_path = "$asset_folder/$level/$type/";
		$ext = ".$type";
		$file = $this->uri->segment(4);
		if($type == 'js'){
			Header("Content-type: text/javascript");
		}elseif($type == 'css'){
			Header ("Content-type: text/css");
		}
		$this->firephp->log($files_path.$file.$ext);
		require_once($files_path.$file.$ext);
	}
}
?>
