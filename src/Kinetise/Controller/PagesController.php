<?php

namespace Kinetise\Controller;

use Kinetise\Mapper\PostsMapper;
use KinetiseApi\Controller\AbstractController;
use KinetiseApi\HTTP\KinetiseDataFeedResponse;
use KinetiseApi\UrlGenerator;

class PagesController extends AbstractController
{
    /**
     * @Kinetise\MethodDesc
     */
    public function indexAction()
    {
        $limit = $this->getRequest()->query->get('pageSize', 100);
        $start = $this->getRequest()->query->get('pageOffset', 0);
        $sort = $this->getRequest()->query->get('_sort', null);
        $order = strtoupper($this->getRequest()->query->get('_order', 'DESC'));

        if (!in_array($order, array('ASC', 'DESC'))) {
            throw new \Exception('Parameter order accept only \'DESC\' or \'ASC\' values.');
        }

        $pages = \get_pages(array(
            'number' => (int)$limit,
            'offset' => (int) $start,
            'sort_column' => $sort,
            'sort_order' => $order,
        ));

        $nextUrl = null;
        if ($limit && sizeof($pages) == $limit) {
            $query = $this->generateNextUrlQuery($limit, $start, $sort, $order);
            $nextUrl = UrlGenerator::generate('pages', null, $query);
        }
        return new KinetiseDataFeedResponse(new PostsMapper($pages), $nextUrl);
    }
}
