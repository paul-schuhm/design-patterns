<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbb96ec6b3124f09d30c68cfeb2fbf941
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Paul\\DP\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Paul\\DP\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitbb96ec6b3124f09d30c68cfeb2fbf941::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbb96ec6b3124f09d30c68cfeb2fbf941::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbb96ec6b3124f09d30c68cfeb2fbf941::$classMap;

        }, null, ClassLoader::class);
    }
}