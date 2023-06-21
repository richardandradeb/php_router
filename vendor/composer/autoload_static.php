<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf1dbd49629881641e557746c1caccb9d
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Richard\\PhpRouter\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Richard\\PhpRouter\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitf1dbd49629881641e557746c1caccb9d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf1dbd49629881641e557746c1caccb9d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf1dbd49629881641e557746c1caccb9d::$classMap;

        }, null, ClassLoader::class);
    }
}