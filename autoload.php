<?php

function __getLiaAutoloadLoader($rootDir, $pathOfAutoloadConfig=''){
    require 'LiaAutoLoad.php';

    LiaAutoLoad::setPathOfRoot  ($rootDir);
    LiaAutoLoad::setComposerPath(__DIR__ . '/../../composer/');
    if($pathOfAutoloadConfig){
        LiaAutoLoad::setPathOfAutoloadConfig($pathOfAutoloadConfig);
    }

    /**
     * @var \Doctrine\Common\ClassLoader $loader
     */
    $loader = LiaAutoLoad::getLoader();

    \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

    return $loader;
}

class LiaAutoLoad {

    private static $loader;

    private static $pathOfRoot;

    private static $pathOfVendor;

    private static $pathOfComposer;

    private static $hashAlgo = 'md5';
    private static $hashFileName = 'autoload_hash.php';

    private static $configFiles = array(
        'autoload_namespaces.php',
        'autoload_classmap.php',
        'autoload_psr4.php',
        'autoload_files.php',
    );

    private static $pathOfAutoloadConfig;

    private static $customAutoloadConfigEnabled = false;

    /**
     * @param string $class
     */
    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require self::getPathOfComposer().'ClassLoader.php';
        }
    }

    /**
     * @param string $pathOfRoot
     */
    public static function setPathOfRoot($pathOfRoot){
        self::$pathOfRoot = $pathOfRoot;
    }

    public static function getPathOfRoot(){
        return self::$pathOfRoot;
    }

    /**
     * @param string $pathOfComposer
     */
    public static function setComposerPath($pathOfComposer){
        self::$pathOfComposer = $pathOfComposer;
    }

    /**
     * @param string $pathOfAutoloadConfig
     */
    public static function setPathOfAutoloadConfig($pathOfAutoloadConfig){
        self::$pathOfAutoloadConfig = $pathOfAutoloadConfig;
        self::$customAutoloadConfigEnabled = true;
    }

    private static function getPathOfVendor(){
        if(!self::$pathOfVendor){
            self::$pathOfVendor = realpath(__DIR__ . '/../../..');
        }
        return self::$pathOfVendor;
    }

    private static function getPathOfComposer(){
        if(!self::$pathOfComposer){
            self::$pathOfComposer = self::getPathOfVendor().'/composer/';
        }
        return self::$pathOfComposer;
    }

    private static function getPathOfAutoloadConfig(){
        return self::$pathOfAutoloadConfig
            ? self::$pathOfAutoloadConfig
            : self::getPathOfComposer()
            ;
    }

    public static function getLoader()
    {
        if(self::$customAutoloadConfigEnabled){
            self::rewriteAutoloadConfigFiles();
        }

        if (null !== self::$loader) {
            return self::$loader;
        }

        $pathOfAutoloadConfig = self::getPathOfAutoloadConfig();

        spl_autoload_register(array('LiaAutoLoad', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('LiaAutoLoad', 'loadClassLoader'));

        $map = require $pathOfAutoloadConfig . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }

        $map = require $pathOfAutoloadConfig . '/autoload_psr4.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }

        $classMap = require $pathOfAutoloadConfig . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        $loader->register(true);

        $includeFiles = require $pathOfAutoloadConfig . '/autoload_files.php';
        foreach ($includeFiles as $file) {
            require $file;
        }

        return $loader;
    }

    /**
     * @return array
     */
    private static function compareHashFiles()
    {
        $filesToGenerate = array();
        $hashFileMustBeRegeneratedAtEnd = false;

        // -

        // Check if hash file exist :
        // if not we generate it
        $hashFile = self::getPathOfAutoloadConfig().self::$hashFileName;
        if(!is_file($hashFile)) {
            self::generateHashMapFile();
        }
        $hash = unserialize(file_get_contents($hashFile));

        // -

        // We iterate on the config files
        foreach(self::$configFiles as $key=>$fileName){
            $configFile = self::getPathOfComposer().$fileName;

            // We check if the original config file exist
            if(!is_file($configFile)) {
                continue;
            }

            // if hash exist
            if(isset($hash[$fileName]) && is_file(self::getPathOfAutoloadConfig().$fileName)){
                // We compare the hash value :
                //  if the same of custom config file then do nothing
                if($hash[$fileName] == hash_file(self::$hashAlgo, $configFile)){
                    continue;
                } else {
                    $hashFileMustBeRegeneratedAtEnd = true;
                }
            }
            $filesToGenerate[] = $fileName;
        }

        // -

        // If the originals config files was modified then we regenerate the hash file
        if($hashFileMustBeRegeneratedAtEnd){
            self::generateHashMapFile();
        }

        // -

        return $filesToGenerate;
    }

    private static function generateHashMapFile(){
        $hash = array();
        foreach(self::$configFiles as $fileName){
            $file = self::getPathOfComposer().$fileName;
            if(isset($file)) {
                $hash[$fileName] = hash_file(self::$hashAlgo, $file);
            }
        }
        file_put_contents(
            self::getPathOfAutoloadConfig().self::$hashFileName,
            serialize($hash)
        );
    }

    private static function rewriteAutoloadConfigFiles()
    {
        $filesToGenerate = self::compareHashFiles();
        if(!count($filesToGenerate)){
            return;
        }

        $header = '<?php'
            ."\r\n"
            ."\r\n".'// @generated by Lia AutoLoad'
            ."\r\n"
            ."\r\n".'$vendorDir = "'. self::getPathOfVendor() .'";'
            ."\r\n".'$baseDir   = "'. self::getPathOfRoot()   .'";'
            ."\r\n"
            ;

        foreach($filesToGenerate as $fileName){
            $content = file_get_contents(self::getPathOfComposer().$fileName);
            file_put_contents(
                self::getPathOfAutoloadConfig().$fileName,
                $header.substr(
                    $content,
                    strpos($content, 'return')
                )
            );
        }
    }
}