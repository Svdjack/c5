<?php

namespace wMVC\Entity;

Class Breadcrumb {

    private $crumbs = array();

    public function addCrumb($name, $url = '', $title = '') {

        $this->crumbs[] = [
            'name' => $name,
            'url' => $url,
            'title' => $title
        ];

        return $this;
    }

    public function getArray() {
        return $this->crumbs;
    }

}
