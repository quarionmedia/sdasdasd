<?php

class Config {
    private static $settings = [];

    public static function load($file) {
        $path = __DIR__ . "/config/{$file}.php";
        if (file_exists($path)) {
            self::$settings[$file] = require $path;
        }
    }

    public static function get($key) {
        $keys = explode('.', $key);
        $data = self::$settings;
        foreach ($keys as $k) {
            if (isset($data[$k])) {
                $data = $data[$k];
            } else {
                return null;
            }
        }
        return $data;
    }
}