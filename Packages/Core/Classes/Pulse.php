<?php

namespace Pulse\Core;

class Pulse{
	public static $is_cli = false;
	/**
	 * This is the main setup method. Used to start the whole system up.
	 * @return boolean
	 */
	public function init()
	{

		\Config::setBasePath(BASEPATH);
		\Config::load('global', PKGPATH . 'App/Config/Application.php');

		//Let's see what our base url is;
		$base_url = \Config::get('base_url');

		if(empty($base_url))
		{
			//Then form the current path;
			$base_url = self::create_url();
		}
		
	}

	public static function create_url()
	{
		$base_url = '';
		if(\Input::server('http_host'))
		{
			$base_url .= \Input::protocol().'://'.\Input::server('http_host');
		}
		if (\Input::server('script_name'))
		{
			$base_url .= str_replace('\\', '/', dirname(\Input::server('script_name')));

			// Add a slash if it is missing
			$base_url = rtrim($base_url, '/').'/';
		}
		return $base_url;
	}

}