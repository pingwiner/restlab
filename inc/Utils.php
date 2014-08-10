<?php

class Utils {
    
    public static function go($url) {
        header('Location: ' . $url);
        die;
    }

}
