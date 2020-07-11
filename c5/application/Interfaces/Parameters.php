<?php

namespace wMVC\Interfaces;

interface ParametersInterface
{
    public function get($key);
    public function set($key, $value);
}