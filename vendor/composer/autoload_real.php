<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb5de666f0203054427f3e79ab2bd0ba5
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitb5de666f0203054427f3e79ab2bd0ba5', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitb5de666f0203054427f3e79ab2bd0ba5', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitb5de666f0203054427f3e79ab2bd0ba5::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
