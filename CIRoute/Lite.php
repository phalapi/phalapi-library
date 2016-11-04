<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 16-10-13
 * Time: 上午10:11
 */

const BASEDIR = '../Library/CIRoute/Core';
require_once BASEDIR.'/URI.php';
require_once BASEDIR.'/Router.php';
const UTF8_ENABLED = true;

class CIRoute_Lite {
	public static $route = array('default_controller'=>'default',
					'404_override'=>'',
					'translate_uri_dashes'=>false);
	protected $RT;


	public function  __construct()
	{
		$this->RT = new CI_Router();
	}
	public function dispatch()
	{
		if(isset($this->RT->uri->rsegments{1}))
		 {
			 $url0 = $this->RT->fetch_class() == $this->RT->uri->rsegments{1} ? true:false;
			 $url1 = $this->RT->fetch_method() == $this->RT->uri->rsegments{2} ? true:false;
			 $urlc = 2 < count($this->RT->uri->rsegments) ? (count($this->RT->uri->rsegments)%2!=0 ? false : true) : false ;
			 $getc = 0 < count($_GET)? false : true ;
			 $rsegments = $this->RT->uri->rsegments;
			 if($url0 && $url1 && $urlc && $getc)
			 {
				 unset($rsegments[1]);
				 unset($rsegments[2]);
				 foreach( $rsegments as $key=>$value)
				 {
					 if(0 == $key%2) $_GET[$rsegments[$key-1]] = $value;
				 }
			 }
		 }
		$_GET['service'] = $this->RT->fetch_class().'.'.$this->RT->fetch_method();
		DI()->request = new PhalApi_Request($_GET);
	}

}
function is_cli()
{
	return false;
}
