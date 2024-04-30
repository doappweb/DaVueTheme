<?php

namespace SuiteCRM\DaVue\Infrastructure\AutoLoader;

class AutoLoader
{
    private const CACHE_PATH = 'cache/themes/DaVue/modules/_Autoload';
    private const CACHE_CLASS_MAP = 'cache/themes/DaVue/modules/_Autoload/classMap.php';
    private const CACHE_EXCLUDED_CLASSES = 'cache/themes/DaVue/modules/_Autoload/excludedClasses.php';

    private const STATIC_CLASS_MAP = 'lib/DaVue/Infrastructure/AutoLoader/classMap.php';
    private const CUSTOM_STATIC_CLASS_MAP = 'custom/lib/DaVue/Infrastructure/AutoLoader/classMap.php';

    private static $classMap = [];
    private static $excludedClasses = [];
    private static $customStaticClassMap = [];
    private static $staticClassMap = [];

    // Connecting files that cannot be found automatically
    private static function init()
    {
        if (empty(self::$customStaticClassMap)) {
            if (file_exists(self::STATIC_CLASS_MAP)) {
                self::$customStaticClassMap = include self::STATIC_CLASS_MAP;
            }
        }

        if (empty(self::$staticClassMap)) {
            if (file_exists(self::CUSTOM_STATIC_CLASS_MAP)) {
                self::$staticClassMap = include self::CUSTOM_STATIC_CLASS_MAP;
            }
        }
    }

    public static function loadClass()
    {
        spl_autoload_register(function ($class_name) {

            self::init();

            // Checking in a static array
            if (key_exists($class_name, self::$customStaticClassMap) && file_exists(self::$customStaticClassMap[$class_name])) {
                require_once(self::$customStaticClassMap[$class_name]);
                return;
            }

            if (key_exists($class_name, self::$staticClassMap) && file_exists(self::$staticClassMap[$class_name])) {
                require_once(self::$staticClassMap[$class_name]);
                return;
            }

            // Initialize the cache if it has not been done before
            self::initializeCache();

            // Checking classes excluded from the search
            if (in_array($class_name, self::$excludedClasses)) {
                return;
            }

            // Checking in the saved cache
            if (key_exists($class_name, self::$classMap)) {
                require_once(self::$classMap[$class_name]);
                return;
            }

            //Folders for class search...
            $paths = [
                "custom/modules/*/",
                "custom/include/*/",
                "custom/include/*/*/",
                "modules/*/",
                "include/*/",
                "include/*/*/",
            ];

            $classFound = false;
            foreach ($paths as $path) {
                foreach (glob($path) as $filePath) {
                    $file = $filePath . $class_name . '.php';
                    if (file_exists($file) !== false) {

                        if (self::fileContainsClass($file)){
                            require_once($file);
                            // Обновление AutoloadClassMap
                            self::$classMap[$class_name] = $file;
                            $classFound = true;
                            break 2;
                        }
                    }
                }
            }

            if (!$classFound){
                self::$excludedClasses[] = $class_name;
            }

            // Updating the cache
            self::updateCache();
        });
    }

    private static function initializeCache()
    {
        if (empty(self::$classMap)) {
            if (file_exists(self::CACHE_CLASS_MAP)) {
                self::$classMap = include self::CACHE_CLASS_MAP;
            }
        }

        if (empty(self::$excludedClasses)) {
            if (file_exists(self::CACHE_EXCLUDED_CLASSES)) {
                self::$excludedClasses = include self::CACHE_EXCLUDED_CLASSES;
            }
        }
    }

    private static function fileContainsClass($filePath) {
        $content = file_get_contents($filePath);
        $tokens = token_get_all($content);
        $classTokenFound = false;

        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            // Checking if the current token is T_CLASS
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_CLASS) {
                // After finding T_CLASS, check whether the class name follows it
                for ($j = $i + 1; $j < $count; $j++) {
                    if (is_array($tokens[$j]) && in_array($tokens[$j][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT])) {
                        // Skip spaces and comments
                        continue;
                    } else if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                        // If after T_CLASS and omissions we find T_STRING (class name), then the class is really declared
                        $classTokenFound = true;
                        break 2;
                    } else {
                        // If we find something other than the class name after T_CLASS, we interrupt the search
                        break;
                    }
                }
            }
        }

        return $classTokenFound;
    }

    private static function updateCache()
    {
        if (!file_exists(self::CACHE_PATH)){
            mkdir(self::CACHE_PATH, 0775, true);
        }

        $classMapPath = self::CACHE_CLASS_MAP;
        file_put_contents($classMapPath, '<?php return ' . var_export(self::$classMap, true) . ';', LOCK_EX);

        $excludedClassesPath = self::CACHE_EXCLUDED_CLASSES;
        file_put_contents($excludedClassesPath, '<?php return ' . var_export(self::$excludedClasses, true) . ';', LOCK_EX);
    }
}
