<?php
namespace wMVC\Controllers;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;


Class Images extends abstractController
{
    private $styles
        = [
            'firm_gallery' => [
                'width'  => 220,
                'height' => 220
            ],
            'company_logo' => [
                'width'  => 200,
                'height' => 100
            ]

        ];


    public function thumb($style, $url)
    {
        if (!in_array($style, array_keys($this->styles))) {
            throw new SystemExit('no style like this', 404);
        }

        print_r($url);

        $image = new Imagine();
        try {
            $image = $image->open(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'asset' . DIRECTORY_SEPARATOR . $url);
        } catch (\InvalidArgumentException $e) {
            throw new SystemExit('no image like this', 404);
        }
        $sizes = $this->styles[$style];

        if (is_dir(dirname(THUMBS_PATH . $style . DIRECTORY_SEPARATOR . $url)) === false) {
            mkdir(dirname(THUMBS_PATH . $style . DIRECTORY_SEPARATOR . $url), 0755, true);
        }

        $image = $image->thumbnail(new Box($sizes['width'], $sizes['height']), ImageInterface::THUMBNAIL_OUTBOUND);
        $image->save(THUMBS_PATH . $style . DIRECTORY_SEPARATOR . $url);
        $image->show('jpg');
    }
}
