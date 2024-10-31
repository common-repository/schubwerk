<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3f8531a3e5a297c92a1eaad530ca8ee6
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Schubwerk\\Core\\' => 15,
        ),
        'M' => 
        array (
            'MaxMind\\WebService\\' => 19,
            'MaxMind\\Exception\\' => 18,
            'MaxMind\\Db\\' => 11,
        ),
        'G' => 
        array (
            'GeoIp2\\' => 7,
        ),
        'C' => 
        array (
            'Cschalenborgh\\IpAnonymizer\\' => 27,
            'Composer\\CaBundle\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Schubwerk\\Core\\' => 
        array (
            0 => __DIR__ . '/..' . '/schubwerk/core/src',
        ),
        'MaxMind\\WebService\\' => 
        array (
            0 => __DIR__ . '/..' . '/maxmind/web-service-common/src/WebService',
        ),
        'MaxMind\\Exception\\' => 
        array (
            0 => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception',
        ),
        'MaxMind\\Db\\' => 
        array (
            0 => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db',
        ),
        'GeoIp2\\' => 
        array (
            0 => __DIR__ . '/..' . '/geoip2/geoip2/src',
        ),
        'Cschalenborgh\\IpAnonymizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/cschalenborgh/laravel-ip-anonymizer/src',
        ),
        'Composer\\CaBundle\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/ca-bundle/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3f8531a3e5a297c92a1eaad530ca8ee6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3f8531a3e5a297c92a1eaad530ca8ee6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3f8531a3e5a297c92a1eaad530ca8ee6::$classMap;

        }, null, ClassLoader::class);
    }
}
