<?php

namespace Kinetise\Controller;

use Kinetise\Mapper\CommentsMapper;
use KinetiseApi\Controller\AbstractController;
use KinetiseApi\HTTP\KinetiseDataFeedResponse;
use KinetiseApi\HTTP\KinetiseErrorResponse;
use KinetiseApi\HTTP\KinetiseResponse;
use KinetiseApi\UrlGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentsController extends AbstractController
{
    /**
     * @Kinetise\MethodDesc
     */
    public function indexAction()
    {
        $postId = $this->getRequest()->query->get('postId');
        $limit = $this->getRequest()->query->get('pageSize', 100);
        $start = $this->getRequest()->query->get('pageOffset', 0);
        $order = strtoupper($this->getRequest()->query->get('_order', 'ASC'));
        $sort = $this->getRequest()->query->get('_sort', null);

        if (!$postId) {
            throw new \Exception('Post not found');
        }

        $options = array(
            'number' => $limit,
            'offset' => $start,
            'order' => $order,
            'post_id' => $postId,
            'status' => 'approve',
            'date_query' => null, // See WP_Date_Query
        );
        if ($sort) {
            $options['orderby'] = $sort;
        }
        $comments = \get_comments($options);
        $nextUrl = null;
        if ($limit && sizeof($comments) == $limit) {
            $query = $this->generateNextUrlQuery($limit, $start, $sort, $order);
            $query['postId'] = $postId;
            $nextUrl = UrlGenerator::generate('comments', null, $query);
        }
        if ($comments == true) {
            return new KinetiseDataFeedResponse(new CommentsMapper($comments), $nextUrl);
        }
        return new KinetiseResponse();
    }

    public function addAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $commentArray = $this->resolveCommentArrayFromRequest();

            $comment =  \wp_new_comment($commentArray);
            if ($comment === false) {
                return new KinetiseErrorResponse('Cannot add this comment.');
            }
        }
        return new KinetiseResponse();
    }

    public function replyAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $parentId = $this->getRequest()->query->get('parentId', -1);
            $parent = \get_comment($parentId);
            if (!$parent) {
                return new KinetiseErrorResponse("Parent comment not found.");
            }
            $commentArray = $this->resolveCommentArrayFromRequest();
            $commentArray['comment_parent'] = $parent->comment_ID;

            $comment =  \wp_new_comment($commentArray);
            if ($comment === false) {
                return new KinetiseErrorResponse('Cannot add this comment.');
            }
            $parent->add_child(\get_comment($comment));
            $parent->populated_children(true);
        }
        return new JsonResponse();
    }

    public function editAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $user = $this->getUserBySessionId();
            $commentId = $this->getRequest()->query->get('comment_ID', -1);
            $comment = \get_comment($commentId);
            if (!$comment) {
                return new KinetiseErrorResponse("Comment not found");
            }
            $body = $this->getRequest()->request->get('body', null);
            if ($user) {
                if ($comment->comment_author_email == $user->get('user_email') || $user->has_cap('moderate_comments')) {
                    if ($body) {
                        $commentArr = $comment->to_array();
                        $commentArr['comment_content'] = $body;
                        \wp_update_comment($commentArr);
                        return new KinetiseResponse();
                    }
                    return new KinetiseErrorResponse("Nothing to update");
                }
                return new KinetiseErrorResponse("You don't have permissions to manage comments");
            }
            return new KinetiseErrorResponse("Login to manage posts");
        }
        return new KinetiseResponse();
    }

    public function removeAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $user = $this->getUserBySessionId();
            if ($user) {
                $commentId = $this->getRequest()->query->get('comment_ID', -1);
                $comment = \get_comment($commentId);
                if (!$comment) {
                    return new KinetiseErrorResponse("Comment not found");
                }
                if ($comment->comment_author_email == $user->get('user_email') || $user->has_cap('moderate_comments')) {
                    $this->deleteChildrenRecursive($comment);
                    \wp_delete_comment($comment->comment_ID);

                    return new KinetiseResponse();
                }
                return new KinetiseErrorResponse("You don't have permissions to manage comments");
            }
            return new KinetiseErrorResponse("Login to manage comments");
        }
        return new KinetiseResponse();
    }

    private function deleteChildrenRecursive($comment)
    {
        $children = $comment->get_children();
        if (sizeof($children)) {
            foreach ($children as $child) {
                $this->deleteChildrenRecursive($child);
                \wp_delete_comment($child->comment_ID);
            }
        }
        return true;
    }

    private function resolveCommentArrayFromRequest()
    {
        $postId = $this->getRequest()->query->get('postId', -1);

        $user = $this->getUserBySessionId();

        $post = \get_post($postId);

        if (!$post) {
            throw new \Exception('Post not found.');
        }

        if (!\comments_open($post)) {
            throw new \Exception('Comments are closed for this post.');
        }

        if ($user) {
            $author = $user->get('display_name');
            $email = $user->get('user_email');
            $website = $user->get('user_url');
        } else {
            if (\get_option('comment_registration') == 1) {
                throw new \Exception('Users must be registered and logged in to comment.');
            }
            $author = $this->getRequest()->request->get('author', null);
            $email = $this->getRequest()->request->get('email', null);
            $website = $this->getRequest()->request->get('website', null);
        }
        $body = $this->getRequest()->request->get('body', null);

        if (\get_option('require_name_email') == 1 && (!$author || !$email)) {
            throw new \Exception('Comment author must fill out name and email.');
        }

        if ($body) {
            $commentArray = array(
                'comment_post_ID' => $post->ID,
                'comment_author' => $author,
                'comment_author_email' => $email,
                'comment_author_url' => $website,
                'comment_content' => $body
            );
            if ($user) {
                $commentArray['user_id'] = $user->ID;
            }

            return $commentArray;
        }
        throw new \Exception('Comment content missing.');
    }
}
