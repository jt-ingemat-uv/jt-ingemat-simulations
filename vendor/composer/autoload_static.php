<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite950e339ffcba93b1af2d2155ce611da
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phpml\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phpml\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ai/php-ml/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'N' => 
        array (
            'NumPHP\\' => 
            array (
                0 => __DIR__ . '/..' . '/numphp/numphp/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite950e339ffcba93b1af2d2155ce611da::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite950e339ffcba93b1af2d2155ce611da::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite950e339ffcba93b1af2d2155ce611da::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
