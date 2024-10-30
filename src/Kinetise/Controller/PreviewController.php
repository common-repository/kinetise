<?php

namespace Kinetise\Controller;

use KinetiseApi\Controller\AbstractController;
use KinetiseApi\HTTP\Request;
use KinetiseApi\HTTP\Response;

class PreviewController extends AbstractController
{
    public function indexAction()
    {
        $id = $this->getRequest()->query->get('postId');

        if (!$id) {
            return new Response('<h1>Post not found</h1>', Response::HTTP_NOT_FOUND);
        }

        $post = \get_post($id);

        if (!$post) {
            return new Response('<h1>Post not found</h1>', Response::HTTP_NOT_FOUND);
        }

        $view = $this->getView();
        $view->setView('post.php');

        $view
            ->setViewParam('post', $post);

        return $view->render();
    }
}
