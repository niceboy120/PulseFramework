<?php
//Let's Define our current namespace;
namespace Package\Core;

//Our Global Config class;
class Config{
	/**
	 * Holds the configuration array brought by from json;
	 * @var String
	 */
	public static $_config = array();
	/**
	 * The base path relative to the local file system, not http;
	 * @var String
	 */
	public static $_base_path;

	/**
	 * States if this is accessed through the command line.
	 * @var boolean
	 */
	public static $_is_cli = false;

	/**
	 * The version of the framework
	 * @var string
	 */
	private static $_version = '0.1';

	/**
	 * Defines the current environment to decide which set of configurations we want;
	 * @var String
	 */
	public static $_environment = 'development';

	//$_base_path Setters & Getters;

	/**
	 * Sets the base path of the framework/
	 * @param String $path The basepath of the system.
	 */
	public static function setBasePath($path)
	{
		self::$_base_path = $path;
	}

	/**
	 * Returns the system base path set in the index file;
	 * @return String
	 */
	public static function getBasePath()
	{
		return self::$_base_path;
	}


	//$_config Setters & Getters;
	
	/**
	 * Loads a specific config file;
	 * @param  String $path Complete path w/ file name.
	 * @return null
	 */
	public static function load($name = 'global', $path)
	{
		//Check if the file exists;
		if(file_exists($path))
		{	
			$file = include($path);
			//Now store it into the $_config;
			if($name == 'global')
			{
				//Then place it in the global array;
				self::$_config = array_merge($file, self::$_config);
			}
			else
			{
				self::$_config[(string) $name] = $file;
			}
		} 
		else
		{
			throw new \FileNotFoundException();
		}
	}

	/**
	 * Set the configuration file to the public $_config variable;
	 * @param String $path Full path to the config file;
	 */
	public static function setConfigurationFile($path)
	{
		//Check to see if that file exists or not;
		if( file_exists( $path ) )
		{
			//Now let's get all that data;
			$_data = file_get_contents( $path );
			//Turn that into a php associative array; (TRUE = Associative)
			$_data = json_decode($_data, true);
			//Now let's just set the public object;
			self::$_config = $_data;
		} 
		else 
		{
			//Let's throw a new ConfigFileNotFoundException exception;
			throw new ConfigFileNotFoundException();
		}
	}


	/**
	 * Returns the full configuration array;
	 * @return Array
	 */
	public static function getConfigurationFile()
	{
		if( !empty ( self::$_config ) )
		{
			return self::$_config;
		}
	}


	/**
	 * Returns the specified array / value;
	 * @param  String/Array $key Either the first key or an array;
	 * @param  String $sub Second key
	 * @return String/Array
	 */
	public static function get($key, $sub = null)
	{
		//Check if {$key} is an array then use it;
		if( is_array( $key ) )
		{
			//Then check the array;
			$search = array_search($key, self::$_config);
			if( $search !== false )
			{
				return $search;
			}
			else 
			{
				throw new KeyInConfigNotFoundException();
			}
		}
		else 
		{
			//Check if the sub key is set. If it is then return it's value;
			if( $sub !== null )
			{
				if ( !empty( self::$_config[$key][$sub] ) )
				{
					return self::$_config[$key][$sub];
				}
			} 
			//If not then proceed with the normal {$key} search;
			else 
			{
				if ( !empty( self::$_config[$key] ) )
				{
					return self::$_config[$key];
				}
			}
		}

	}


	/**
	 * Loads a config file into the {$_config} array;
	 * @param  String  $key  Specifies the new key;
	 * @param  String  $file Filename
	 * @param  boolean $json Wheather the type is json or not;
	 * @param  String  $path Specifies the path to where the config file is;
	 * @return boolean/Array
	 */
	public static function loadFile( $key, $file , $json = true, $path = BASEPATH)
	{
		//Check if the type of file is json;
		if( $json === true )
		{
			if( $path != BASEPATH )
			{
				$path = BASEPATH . 'app/config/';
			}
			//Now load the file;
			$_data = file_get_contents( $path . $file );
			//Decode the json;
			$_data = json_decode($_data, true);
			//Now merge the arrays;
			if( empty( $key ) )
			{
				//TODO: Split the extension off the filename and that would be the default key;	
			}
			//Check wheather they key is already set;
			if( !isset( self::$_config[$key]))
			{
				self::$_config[$key] = $_data;
				return self::$_config[$key];
			} 
			else 
			{
				return false;
			}
		}
	}


}

/** ./#Scale/Core/Config/Config.php -- End of file **/