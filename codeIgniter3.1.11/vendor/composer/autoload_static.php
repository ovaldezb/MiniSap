<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit675004119725dd692b38fd83cc90cd93
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'tests\\' => 6,
        ),
        'S' => 
        array (
            'SWServices\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/lunasoft/sw-sdk-php/tests',
        ),
        'SWServices\\' => 
        array (
            0 => __DIR__ . '/..' . '/lunasoft/sw-sdk-php/SWServices',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit675004119725dd692b38fd83cc90cd93::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit675004119725dd692b38fd83cc90cd93::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
