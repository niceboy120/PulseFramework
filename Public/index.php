<?php
/**
 * @filesource Public/index.php
 * @namespace none
 * @author Daniel Fagnan
 * @package  Pulse Framework
 * @copyright  2012 (c) All Rights Reserved.
 * @version  v0.1
 * @category  Boostrap
 */

//////////////////////////////////
//Let's define a few constants; //
//////////////////////////////////

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/** We need to get the relative path according to the file system. */
defined('BASEPATH') or define('BASEPATH', realpath(dirname(realpath(__FILE__))) . '/../');
/** Let's define our main directory where all the classes, etc.. will be located. **/
defined('PKGPATH') or define('PKGPATH', BASEPATH . 'Packages/');
/** Now let's define our apps directory where the application specific code will be in. **/
defined('APPPATH') or define('APPPATH', PKGPATH . 'App/');
/** Now let's define our core systems package where Pulse is located **/
defined('COREPATH') or define('COREPATH', PKGPATH . 'Core/');

///////////////////////////////////////////////////////
// Let's run / start our Autoloader. We need to manually load the file.
///////////////////////////////////////////////////////

/** Let's make sure that the function doesn't exist first. **/
if( ! function_exists('run_autoloader'))
{
	/**
	 * Start the basic definition for the autoloader class.
	 * We need to define all the paths for locating and loading, and a bunch of other stuff.
	 * @return nothing :)
	 */
	function run_autoloader()
	{
		// Load our autoloader class. (Use require because it's faster).		
		require(COREPATH . 'Classes/Autoloader.php');

		//Add the namespace to the system;
		\Pulse\Core\Autoloader::add_namespace('\\Pulse\\Core', COREPATH . 'Classes/');

		//Now let's define all the paths for the system.
		\Pulse\Core\Autoloader::add_classes(array(
				'Pulse\\Core\\Pulse' => COREPATH . 'Classes/Pulse.php',
				'Pulse\\Core\\Config' => COREPATH . 'Classes/Config.php',
				'Pulse\\Core\\Driver' => COREPATH . 'Classes/Driver.php',
				'Pulse\\Core\\Event' => COREPATH . 'Classes/Event.php',
				'Pulse\\Core\\Database' => COREPATH . 'Classes/Database.php',
				'Pulse\\Core\\Inout' => COREPATH . 'Classes/Input.php',
				'Pulse\\Core\\Package' => COREPATH . 'Classes/Package.php',
				'Pulse\\Core\\Session' => COREPATH . 'Classes/Session.php',
			));

		\Pulse\Core\Autoloader::register();

		! class_exists('Autoloader') and class_alias('Pulse\\Core\\Autoloader', 'Autoloader');
		! class_exists('Pulse') and class_alias('\\Pulse\\Core\\Pulse', 'Pulse');
		! class_exists('Event') and class_alias('\\Pulse\\Core\\Event', 'Event');
		! class_exists('Config') and class_alias('\\Pulse\\Core\\Config', 'Config');
		! class_exists('Input') and class_alias('\\Pulse\\Core\\Input', 'Input');
	}

}

//Run the autoloader;
run_autoloader();

//////////////////////////////////////
// Now let's load the pulse class //
//////////////////////////////////////
\Pulse::init();


/////////////////////////////////////////////////////
// Let's shut the whole system down with an event; //
/////////////////////////////////////////////////////
\Event::load('shutdown');


/***********************/

////////////////////////
// The base functions //
////////////////////////
if( ! function_exists('logger'))
{
	function logger($level, $msg, $method = null)
	{
		if ($level > \Config::get('log_threshold'))
		{
			return false;
		}

		! class_exists('Fuel\\Core\\Log') and import('log');
		! class_exists('Log') and class_alias('Fuel\\Core\\Log', 'Log');

		return \Log::write($level, $msg, $method);
	}

}