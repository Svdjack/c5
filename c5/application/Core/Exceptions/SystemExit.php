<?php
namespace wMVC\Core\Exceptions;

Class SystemExit extends \Exception
{
    //http error codes stated here, actual debug information goes to error_log() from SystemExit::getMessage();
    const NO_TEMPLATE = 500;
    const DB_HAS_GONE_AWAY = 500;
    const CALL_TO_PROTECTED_METHOD = 404;
    const NO_MODEL = 500;
    const NOT_FOUND = 404;
    const NO_CONTROLLER = 404;
    const NO_ROUTE_PATTERN = 500;
    const ACCESS_DENIED = 500;
}