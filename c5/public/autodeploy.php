<?php
/* Auto deploy */

if (!isset($_GET['bitbucket'])) {
    return;
}

$__auto__dir = __DIR__ . '/';

/* Standart or $site name public dir */
$__auto__public = \dirname($__auto__dir) . '/autodeploy/public.php';
if (\file_exists($__auto__public)) {
    require_once $__auto__public;
    exit(0);
}

/* Drupal-like */
$__auto__public = $__auto__dir . '/autodeploy/public.php';
if (\file_exists($__auto__public)) {
    require_once $__auto__public;
    exit(0);
}