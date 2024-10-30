<?php

namespace Kinetise\Mapper;

use KinetiseApi\Mapper;
use KinetiseApi\UrlGenerator;

class CategoriesMapper implements Mapper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        foreach ($this->data as $key => $value) {
            $catId = $value->cat_ID;
            $this->data[$key] = (array) $value;

            $this->data[$key]['children'] = UrlGenerator::generate('categories', null, array('parentCat' => $catId));
            $this->data[$key]['posts'] = UrlGenerator::generate('posts', null, array('cat_ID' => $catId));
            $this->data[$key]['category_add_post_url'] = UrlGenerator::generate('posts', 'add', array('cat_ID' => $catId));

            if (isset($value->category_description)) {
                $this->data[$key] = array('description' => $value->category_description) + $this->data[$key];
            }
            if (isset($value->cat_name)) {
                $this->data[$key] = array('title' => $value->cat_name) + $this->data[$key];
            }
            $this->data[$key] = array('id' => $catId) + $this->data[$key];
        }

        return $this->data;
    }
}
