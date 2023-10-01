<?php 

namespace Classes;
use classes\Config;

class Redirect{
    public static function to($urlPath = null){
        if (isset($urlPath)) {
            if (is_numeric($urlPath)) {
                switch ($urlPath) {
                    case 400:
                        header('HTTP/0.1 404 found');
                        header('location: ' . Config::get("root/path") . "page_parts/errors/404.php");
                        exit();
                    break;
                }
            }

            header('Location: ' . $urlPath);
        }
    }
}