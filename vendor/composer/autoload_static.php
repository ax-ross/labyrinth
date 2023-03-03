<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb5de666f0203054427f3e79ab2bd0ba5
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb5de666f0203054427f3e79ab2bd0ba5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb5de666f0203054427f3e79ab2bd0ba5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb5de666f0203054427f3e79ab2bd0ba5::$classMap;

        }, null, ClassLoader::class);
    }
}
