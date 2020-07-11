<?php

namespace wMVC\Core\Debug;

class TwigEnvironment extends \Twig_Environment {

    /**
     * @inheritdoc
     */
    public function render($name, array $context = array()) {
        Debugger::set('twig_' . $name, $context);
        return parent::render($name, $context);
    }

}
