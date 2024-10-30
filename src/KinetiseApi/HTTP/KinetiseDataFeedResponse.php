<?php

namespace KinetiseApi\HTTP;

use KinetiseApi\Mapper;
use Symfony\Component\HttpFoundation\JsonResponse;

class KinetiseDataFeedResponse extends JsonResponse
{
    private $feed;
    private $pagination;

    public function __construct($data, $pagination = null)
    {
        $this->feed = $data instanceof Mapper ? $data->toArray() : $data;
        $this->pagination = $pagination;

        parent::__construct($this->prepareDataFeed());
    }


    public function prepareDataFeed()
    {
        $results = array();

        if (sizeof($this->feed)) {
            foreach ($this->feed as $el) {
                $el = (array)$el;

                $el['_coverImage'] = isset($el['_coverImage']) ? $el['_coverImage'] : \plugins_url('images/tutorial/icon_wordpress.png', KINETISE_ROOT . DS . 'kinetise.php');
                $el['base_title'] = \get_bloginfo('name');
                $el['base_description'] = \get_bloginfo('description');
                $el['base_url'] = \site_url();

                $results[] = $el;
            }
        }

        $preparedResponse = array('results' => $results);
        if ($this->pagination) {
            $preparedResponse['pagination'] = array('next_url' => $this->pagination);
        }

        return $preparedResponse;
    }
}
