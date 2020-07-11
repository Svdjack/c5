<?php

if (empty($_SERVER['REMOTE_ADDR'])) {
    $_SERVER['REMOTE_ADDR'] = '999.999.999.999';
}
if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_SERVER['HTTP_X_FORWARDED_FOR'] = $_SERVER['REMOTE_ADDR'];
}

require APP_PATH . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'Application.php';
