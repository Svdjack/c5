<?php
namespace wMVC\Controllers;

use wMVC\Core\abstractController;

Class ErrorHandler extends abstractController
{
    public function code404()
    {
        http_response_code(404);
        $url = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        print $this->view->render('@errorhandler/404.twig', [
                'url' => $url,
                'head' =>
                    [
                        'title' => 'Страница не найдена'
                    ]
            ]);
    }

    public function code500()
    {
        http_response_code(500);
        print $this->view->render('@errorhandler/500.twig');
    }
}