<?php

namespace Kinetise\Controller;

use Kinetise\Mapper\CategoriesMapper;
use KinetiseApi\Controller\AbstractController;
use KinetiseApi\HTTP\KinetiseDataFeedResponse;
use KinetiseApi\HTTP\KinetiseItemResponse;
use KinetiseApi\HTTP\Response;
use KinetiseApi\UrlGenerator;

class CategoriesController extends AbstractController
{
    /**
     * @Kinetise\MethodDesc
     */
    public function indexAction()
    {
        $parent = $this->getRequest()->query->get('parentCat', null);

        $options = array('hide_empty' => 0, 'pad_counts' => false);
        if ($parent) {
            $options['parent'] = $parent;
        }

        $categories = \get_categories($options);


        return new KinetiseDataFeedResponse(new CategoriesMapper($categories));
    }

    public function listAction()
    {
        $options = array('orderby'=>'ID', 'hide_empty'=>0);
        $categories = \get_categories($options);
        $categoriesList = array();

        foreach ($categories as $row) {
            $catID = $row->term_id;
            $catName = $row->cat_name;
            array_push($categoriesList, array('value' => $catID, 'text' => $catName));
        }
        $categoriesResponse = array(array("id"=>1, "categoriesList" => json_encode($categoriesList)));

        return new KinetiseItemResponse($categoriesResponse);
    }
}
