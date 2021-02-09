<?php

namespace app\models;

use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class CommentModel extends AppModel
{
    /**
     * @param array $data
     * @return mixed
     * @throws SQL
     */
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
        return R::exec('DELETE FROM comments WHERE id = ?', [$id]);
    }

    public function updateComment($id, $data): void
    {
        /** @var object $order */
        $comment = R::load('comments', $id);
        $comment->text = $data;
        R::store($comment);
    }
}
