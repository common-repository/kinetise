<?php

namespace Kinetise\Mapper;

use KinetiseApi\Mapper;
use KinetiseApi\UrlGenerator;

class CommentsMapper implements Mapper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        foreach ($this->data as $key => $comment) {
            $commentID = $comment->comment_ID;

            $this->data[$key] = $comment->to_array();

            // format dates
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$key]['comment_date']);
            $this->data[$key]['comment_date'] = $date->format(\DateTime::RFC3339);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$key]['comment_date_gmt']);
            $this->data[$key]['comment_date_gmt'] = $date->format(\DateTime::RFC3339);

            // add dynamic links
            $this->data[$key]['comment_edit_url'] = UrlGenerator::generate('comments', 'edit', array('comment_ID' => $commentID));
            $this->data[$key]['comment_delete_url'] = UrlGenerator::generate('comments', 'remove', array('comment_ID' => $commentID));
            if (\get_option('thread_comments') == 1) {
                $this->data[$key]['comment_reply_url'] = UrlGenerator::generate('comments', 'reply', array('postId' => $this->data[$key]['comment_post_ID'], 'parentId' => $commentID));
            }
            //  handle treated comments data
            $this->data[$key]['children'] = array_keys($comment->get_children());
            $this->data[$key]['populated_children'] = (bool) sizeof($this->data[$key]['children']);

            // append default kinetise values
            if (isset($this->data[$key]['comment_content'])) {
                $this->data[$key] = array('description' => $this->data[$key]['comment_content']) + $this->data[$key];
            }
            if (isset($this->data[$key]['comment_author'])) {
                $this->data[$key] = array('title' => $this->data[$key]['comment_author']) + $this->data[$key];
            }
            $this->data[$key] = array('id' => $commentID) + $this->data[$key];

            unset($this->data[$key]['post_fields']);
        }

        return $this->data;
    }
}
