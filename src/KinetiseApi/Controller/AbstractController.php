<?php

namespace KinetiseApi\Controller;

use Symfony\Component\HttpFoundation\Request;
use KinetiseApi\View;

abstract class AbstractController
{
    private $request;
    private $view;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->view = new View($this);
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function getView()
    {
        return $this->view;
    }

    protected function getUserBySessionId()
    {
        $sessionId = $this->getRequest()->query->get('sessionId', false);

        $user = reset(
            \get_users(
                array(
                    'meta_key' => '_kinetise_session_id',
                    'meta_value' => $sessionId,
                    'number' => 1,
                    'count_total' => false
                )
            )
        );

        if ($user instanceof \WP_User) {
            return $user;
        }

        return false;
    }

    protected function generateNextUrlQuery($limit, $start, $sort, $order)
    {
        $query = array('pageSize' => $limit, 'pageOffset' => $start + $limit);
        if ($sort && $sort !== 'ID') {
            $query['_sort'] = $sort;
        }
        if ($order !== 'DESC') {
            $query['_order'] = $order;
        }

        return $query;
    }
}
