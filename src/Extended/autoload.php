<?php

define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD',@$_SERVER['REQUEST_METHOD']);
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);

$dir = opendir(__DIR__);
while($row = readdir($dir))
{
    if ($row != "." && $row != "..") {
        $exp = explode('.', $row);
        if(@$exp[1] == 'extend' && @$exp[2] == 'php'){
            require __DIR__ . '/'. $row;
        }
    }
}


