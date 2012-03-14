<?php
namespace Package\Core;
/**
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 *
 *     // Example which loads classes for the Doctrine Common package in the
 *     // Doctrine\Common namespace.
 *     $classLoader = new SplClassLoader('Doctrine\Common', '/path/to/doctrine');
 *     $classLoader->register();
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class Autoloader
{
    private static $_fileExtension = '.php';
    private static $_namespace;
    private static $_includePath;
    private static $_namespaceSeparator = '\\';

    private static $_map = array();

    public static function add_classes($classes)
    {
        if(is_array($classes))
        {
            self::$_map = array_merge(self::$_map, $classes);
            return true;
        }
        return false;
    }

    public static function add_class($namespace, $path)
    {
        if($namespace and $path)
        {
            if( ! isset(self::$_map[$namespace]))
            {
                self::$_map[$namespace] = $path;
                return true;
            }
        }
        return false;
    }


    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     * 
     * @param string $sep The separator to use.
     */
    public static function setNamespaceSeparator($sep)
    {
        self::$_namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public static function getNamespaceSeparator()
    {
        return self::$_namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     * 
     * @param string $includePath
     */
    public static function setIncludePath($includePath)
    {
        self::$_includePath = $includePath;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public static function getIncludePath()
    {
        return self::$_includePath;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     * 
     * @param string $fileExtension
     */
    public static function setFileExtension($fileExtension)
    {
       self::$_fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public static function getFileExtension()
    {
        return self::$_fileExtension;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public static function register()
    {
        spl_autoload_register("Package\Core\Autoloader::loadClass");
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public static function unregister()
    {
        spl_autoload_unregister("Package\Core\Autoloader::loadClass");
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    public static function loadClass($className)
    {
        if (null === self::$_namespace || self::$_namespace.self::$_namespaceSeparator === substr($className, 0, strlen(self::$_namespace.self::$_namespaceSeparator))) {
            $fileName = '';
            $namespace = '';
            if (false !== ($lastNsPos = strripos($className, self::$_namespaceSeparator))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace(self::$_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }

            if( ! empty($namespace))
            {
                $newNamespace = $namespace . '\\' . $className;
                if(isset(self::$_map[$newNamespace]))
                {
                    //Voila ! Map!
                    $path = self::$_map[$newNamespace];
                    if(file_exists($path))
                    {
                        require $path;
                        return true;
                    }
                }
            }


            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . self::$_fileExtension;

            require (self::$_includePath !== null ? self::$_includePath : '') . $fileName;
        }
    }
}