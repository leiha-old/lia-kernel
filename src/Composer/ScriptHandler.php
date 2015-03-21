<?php

namespace Lia\KernelBundle\Composer;

class ScriptHandler {

    public static function rewriteAutoload(/*CommandEvent $event*/)
    {
        $rootDir     = getcwd()  .'/';
        $vendorDir   = realpath($rootDir  .'../../symfony-vendors').'/';
        $directories = array(
            'root'     => $rootDir,
            'cache'    => $rootDir.'app/cache/',
            'vendor'   => $vendorDir,
            'composer' => $vendorDir.'composer/',
        );

        self::getReturnValues('autoload_namespaces.php', $directories);
        self::getReturnValues('autoload_classmap.php'  , $directories);
        self::getReturnValues('autoload_psr4.php'      , $directories);
        self::getReturnValues('autoload_files.php'     , $directories);
    }

    private function getReturnValues($fileName, array $directories)
    {
        $file = file_get_contents($directories['composer'].$fileName);
        $file = substr($file, strpos($file, 'return'));

        $content = '<?php '."\r\n"
            .'$vendorDir = "'. $directories['vendor'] .'";'."\r\n"
            .'$baseDir   = "'. $directories['root'  ] .'";'."\r\n"
            .$file
        ;
        $w = file_put_contents($directories['cache'].$fileName, $content);
    }
}