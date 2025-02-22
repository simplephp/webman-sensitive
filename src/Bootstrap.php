<?php
declare (strict_types = 1);

namespace simplephp\sensitive;

use Webman\Bootstrap as WebmanBootstrap;
use Workerman\Worker;

class Bootstrap implements WebmanBootstrap
{
    protected static  $_instance = null;

    public static function start($worker)
    {
        if ($worker) {
            static::$_instance = new \simplephp\sensitive\Sensitive;
        }
        
        // 检测/config/plugin/simplephp/webman-sensitive敏感词库不存在时复制到该目录
        if (!is_file($file = config_path('plugin') . DIRECTORY_SEPARATOR .'simplephp'. DIRECTORY_SEPARATOR .'webman-sensitive'. DIRECTORY_SEPARATOR .'SensitiveWord.txt')) {

            if ((!is_dir($path = dirname($file)))) {
                mkdir($path, 0777, true);
            }

            $sensitiveWordFile = __DIR__ . DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR .'SensitiveWord.txt';

            if (!copy($sensitiveWordFile, $file)) {
                throw new SensitiveException('Failed to copy thesaurus. Please manually copy "'. $sensitiveWordFile .'" to "'. $file .'" manually.', 5);
            }
        }
    }

    public static function __callStatic($name, $arguments)
    {
        return static::instance()->{$name}(... $arguments);
    }
}
