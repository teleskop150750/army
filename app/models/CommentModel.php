<?php

namespace app\models;

use RedBeanPHP\R;

class CommentModel extends AppModel
{

    public static function addComment(array $data)
    {
        /** @var object $comment */
        $comment = R::dispense('comments');
        $comment->user_id = $data['user'];
        $comment->article_id = $data['article'];
        $comment->text = $data['comment'];
        return R::store($comment);
    }

    public function deleteComment(int $id)
    {
        R::hunt('comments', 'id = ?', [$id]);
    }

    public function updateComment($id, $data): void
    {
        /** @var object $order */
        $comment = R::load('comments', $id);
        $comment->text = $data;
        R::store($comment);
    }
}
