<?php

namespace Kinetise\Controller;

use Kinetise\Mapper\PostsMapper;
use KinetiseApi\Controller\AbstractController;
use KinetiseApi\HTTP\KinetiseDataFeedResponse;
use KinetiseApi\HTTP\KinetiseErrorResponse;
use KinetiseApi\HTTP\KinetiseResponse;
use KinetiseApi\UrlGenerator;

class PostsController extends AbstractController
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

        $categoryId = $this->getRequest()->query->get('cat_ID', '');
        $categoryName = $this->getRequest()->query->get('cat_name', '');

        if (!in_array($order, array('ASC', 'DESC'))) {
            throw new \Exception('Parameter order accept only \'DESC\' or \'ASC\' values.');
        }

        $posts = \get_posts(array(
            'posts_per_page' => (int)$limit,
            'offset' => (int)$start,
            'category_name' => $categoryName,
            'category' => $categoryId,
            'order' => $order,
            'orderby' => $sort,
            'post_type' => 'post',
            'post_status' => 'publish'
        ));

        $nextUrl = null;
        if ($limit && sizeof($posts) == $limit) {
            $query = $this->generateNextUrlQuery($limit, $start, $sort, $order);
            $nextUrl = UrlGenerator::generate('posts', null, $query);
        }

        return new KinetiseDataFeedResponse(new PostsMapper($posts), $nextUrl);
    }

    public function addAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $user = $this->getUserBySessionId();
            if ($user) {
                if (!$user->has_cap('publish_posts')) {
                    return new KinetiseErrorResponse("You don't have permissions to create new post");
                }
                $author = $user->ID;
            } else {
                return new KinetiseErrorResponse("Login to manage posts");
            }

            $postData = $this->resolvePostDataFromRequest();
            if (!isset($postData['post_title']) || !isset($postData['post_content'])) {
                return new KinetiseErrorResponse("Post title or content is missing.");
            }
            $postData['post_author'] = $author;

            $postId = \wp_insert_post($postData, true);
            if (\is_wp_error($postId)) {
                return new KinetiseErrorResponse(strip_tags($postId->get_error_message()));
            }

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $coverImage = $this->createAttachmentFromRequestedPicture('cover_image');
            $contentImage = $this->createAttachmentFromRequestedPicture('content_image');

            if (false !== $coverImage) {
                $cover_attach_id = \wp_insert_attachment($coverImage, $coverImage['post_title'], $postId);
                $cover_attach_data = \wp_generate_attachment_metadata($cover_attach_id, $coverImage['post_title']);
                \wp_update_attachment_metadata($cover_attach_id, $cover_attach_data);

                \set_post_thumbnail($postId, $cover_attach_id);
            }

            if (false !== $contentImage) {
                $attach_id = \wp_insert_attachment($contentImage, $contentImage['post_title'], $postId);
                $attach_data = \wp_generate_attachment_metadata($attach_id, $contentImage['post_title']);
                \wp_update_attachment_metadata($attach_id, $attach_data);

                $new_attach = \wp_get_attachment_image($attach_id, 'large');
                $post_content = $new_attach .  "\n"  . $postData['post_content'];
                $post_data = array('ID' => $postId, 'post_content' => $post_content);
                \wp_update_post($post_data);
            }
        }
        return new KinetiseResponse();
    }

    public function editAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $user = $this->getUserBySessionId();
            $postId = $this->getRequest()->query->get('postId', -1);
            $post = \get_post($postId);
            if (!$post) {
                return new KinetiseErrorResponse("Post not found");
            }

            if ($user) {
                if (($post->post_author == $user->ID && $user->has_cap('edit_posts')) || $user->has_cap('edit_others_posts')) {
                    $postData = $this->resolvePostDataFromRequest();

                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $contentImage = $this->createAttachmentFromRequestedPicture('content_image');
                    if (false !== $contentImage) {
                        $attach_id = \wp_insert_attachment($contentImage, $contentImage['post_title'], $postId);
                        $attach_data = \wp_generate_attachment_metadata($attach_id, $contentImage['post_title']);
                        \wp_update_attachment_metadata($attach_id, $attach_data);

                        $new_attach = \wp_get_attachment_image($attach_id, 'large');
                        $postData['post_content'] = $new_attach .  "\n"  . $postData['post_content'];
                    }
                    $postData['ID'] = $postId;
                    $postId = \wp_update_post($postData, true);
                    if (\is_wp_error($postId)) {
                        return new KinetiseErrorResponse(strip_tags($postId->get_error_message()));
                    }

                    $coverImage = $this->createAttachmentFromRequestedPicture('cover_image');
                    if (false !== $coverImage) {
                        $attach_id = \wp_insert_attachment($coverImage, $coverImage['post_title'], $postId);
                        $attach_data = \wp_generate_attachment_metadata($attach_id, $coverImage['post_title']);
                        \wp_update_attachment_metadata($attach_id, $attach_data);

                        \set_post_thumbnail($postId, $attach_id);
                    }

                    return new KinetiseResponse();
                }
                return new KinetiseErrorResponse("You don't have permissions to edit this post");
            }
            return new KinetiseErrorResponse("Login to manage posts");
        }
        return new KinetiseResponse();
    }

    public function removeAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $user = $this->getUserBySessionId();
            $postId = $this->getRequest()->query->get('postId', -1);
            $post = \get_post($postId);
            if (!$post) {
                return new KinetiseErrorResponse("Post not found");
            }

            if ($user) {
                if (($post->post_author == $user->ID && $user->has_cap('delete_posts')) || $user->has_cap('delete_other_posts')) {
                    \wp_delete_post($postId);
                    return new KinetiseResponse();
                }
                return new KinetiseErrorResponse("You don't have permissions to delete this post");
            }
            return new KinetiseErrorResponse("Login to manage posts");
        }
        return new KinetiseResponse();
    }

    private function resolvePostDataFromRequest()
    {
        $title = $this->getRequest()->request->get('title', null);
        $content = $this->getRequest()->request->get('content', null);
        $categories = $this->getRequest()->request->get('cat_ID', null);
        $postData = array(
            'post_title' => (strlen(trim($title)) > 0) ? trim($title) : null,
            'post_content' => (strlen(trim($content)) > 0) ? trim($content) : null,
            'post_status' => 'publish'
        );
        if ($categories) {
            $categories = explode(',', $categories);
            $postData['post_category'] = $categories;
        } else {
            if ($categories = $this->getRequest()->query->get('cat_ID', null)) {
                $categories = explode(',', $categories);
                $postData['post_category'] = $categories;
            }
        }

        $postData = array_filter($postData, function ($a) {
            return !empty($a);
        });
        return $postData;
    }

    /**
     * @param $type string cover_image|content_image
     * @return array|bool
     */
    private function createAttachmentFromRequestedPicture($type)
    {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $upload_dir = \wp_upload_dir();
        $uploadPath = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR;

        $title = $this->getRequest()->request->get('title', null);
        $title = strlen(trim($title )) > 0 ? $title : md5(time());

        $image = $this->getRequest()->request->get($type, null);
        $image = base64_decode($image);
        $imgInfo = getimagesizefromstring($image);
        $titleReplaced = preg_replace('/\W/', '_', $title);
        $filename = $titleReplaced . '_' . $type . image_type_to_extension($imgInfo[2], true);
        if ($image) {
            file_put_contents($uploadPath . $filename, $image);
            $file = array();
            $file['error'] = '';
            $file['tmp_name'] = $uploadPath . $filename;
            $file['name'] = $filename;
            $file['type'] = $imgInfo['mime'];
            $file['size'] = filesize($uploadPath . $filename);
            $file_return = \wp_handle_sideload($file, array('test_form' => false));
            $filename = $file_return['file'];

            return array(
                'post_mime_type' => $imgInfo['mime'],
                'post_title' => $filename,
                'post_content' => '',
                'post_status' => 'inherit'
            );
        }
        return false;
    }
}
