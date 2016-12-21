<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 扩展视图文件 皮肤
*/

class MY_Loader extends CI_Loader{
	
	//开启皮肤
	public function home_themes_mobile_on() {
		$this->_ci_view_paths = array(VIEWPATH.'m'.DIRECTORY_SEPARATOR=>true);
	}

	public function admin_themes_on() {
		$this->_ci_view_paths = array(VIEWPATH.'admin'.DIRECTORY_SEPARATOR=>true);
	}
        
  public function top_themes_on() {
//		$this->_ci_view_paths = array(VIEWPATH.'admin'.DIRECTORY_SEPARATOR=>true);
	}

}
