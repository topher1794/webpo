<?php 

namespace webpo;

class Registry {
   private static $store = [];

    public static function set($key, $value) {
        self::$store[$key] = $value;
    }

    public static function get($key) {
        return isset(self::$store[$key]) ? self::$store[$key] : null;
    }

    public static function all() {
        return self::$store;
    }
}