<?php
if (\file_exists(__DIR__ . '/config-local.php')) {
    return require __DIR__ . '/config-local.php';
}

return require __DIR__ . '/config-prod.php';
