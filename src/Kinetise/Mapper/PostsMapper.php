<?php

namespace Kinetise\Mapper;

use KinetiseApi\Mapper;
use KinetiseApi\UrlGenerator;

class PostsMapper implements Mapper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        foreach ($this->data as $key => $post) {
            $postId =  $post->ID;

            if ($post->comment_status == 'open') {
                $post->comments_url = UrlGenerator::generate('comments', null, array('postId' => $postId));
                $post->comments_url_add = UrlGenerator::generate('comments', 'add', array('postId' => $postId));
            }
            if ($post->post_type == 'post') {
                $post->edit_post_url = UrlGenerator::generate('posts', 'edit', array('postId' => $postId));
                $post->delete_post_url = UrlGenerator::generate('posts', 'remove', array('postId' => $postId));
            }

            $this->data[$key] = $post->to_array();

            if (isset($this->data[$key]['post_content'])) {
                $emptyImg = '<img src="' . \plugins_url('images/empty.png', KINETISE_ROOT . DS . 'kinetise.php') . '">';
                $val = $this->data[$key]['post_content'] . $emptyImg;
                unset($this->data[$key]['post_content']);
                $this->data[$key] = array('description' => $val) + $this->data[$key];
            }

            if (isset($this->data[$key]['post_title'])) {
                $val = $this->data[$key]['post_title'];
                unset($this->data[$key]['post_title']);
                $this->data[$key] = array('title' => $val) + $this->data[$key];
            }

            $this->data[$key] = array('id' => $postId) + $this->data[$key];
            unset($this->data[$key]['ID']);

            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$key]['post_date']);
            $this->data[$key]['post_date'] = $date->format(\DateTime::RFC3339);

            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$key]['post_date_gmt']);
            $this->data[$key]['post_date_gmt'] = $date->format(\DateTime::RFC3339);

            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$key]['post_modified']);
            $this->data[$key]['post_modified'] = $date->format(\DateTime::RFC3339);

            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$key]['post_modified_gmt']);
            $this->data[$key]['post_modified_gmt'] = $date->format(\DateTime::RFC3339);

            $this->data[$key]['preview_url'] = UrlGenerator::generate('preview', null, array('postId' => $post->ID));

            $thumbnailId = \get_post_thumbnail_id($postId);
            $image = \wp_get_attachment_image_src($thumbnailId, 'full');
            $thumbnail = \wp_get_attachment_image_src($thumbnailId, 'thumbnail');
            $medium = \wp_get_attachment_image_src($thumbnailId, 'medium');
            $large = \wp_get_attachment_image_src($thumbnailId, 'large');

            if ($image && is_array($image)) {
                $this->data[$key]['_coverImage'] = $image[0];
                $this->data[$key]['_coverImage_thumbnail'] = $thumbnail[0];
                $this->data[$key]['_coverImage_medium'] = $medium[0];
                $this->data[$key]['_coverImage_large'] = $large[0];
            }

        }

        return $this->data;
    }
}
