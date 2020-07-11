<?php
namespace wMVC\Core;
use wMVC\Core\Exceptions\SystemExit;

Class View
{
    protected $variables = array();
    protected $template;

    public function loadTemplate($controller, $template)
    {
        $this->template = sprintf('%s%s%s%s.php', TEMPLATES_PATH, strtolower($controller), DIRECTORY_SEPARATOR, strtolower($template));
        if(!is_file($this->template)){
            throw new SystemExit("Template {$controller}/{$template} not found", SystemExit::NO_TEMPLATE);
        }
        return $this;
    }

    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }

    public function render()
    {
        if ($this->template) {
            extract($this->variables);
            ob_start();
            require $this->template;
            return ob_get_clean();
        }
        return 'load template first';
    }
}