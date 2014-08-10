<?php
class Scripts {
    private static $mScripts;
    
    public static function add($script) {
        if (!self::$mScripts) self::$mScripts = array();
        self::$mScripts[] = $script;        
    }
    
    public static function write() {
        if (!self::$mScripts) return '';
        $result = '';
        foreach(self::$mScripts as $script) {
            $result .= '<script src="js/' . $script . '.js"></script>';
        }
        return $result;
    }
}
