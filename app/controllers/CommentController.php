<?php

namespace app\controllers;

use app\models\CommentModel;

class CommentController extends AppController
{
    public function addCommentAction(): void
    {
        if ($_POST) {
            $comment_model = new CommentModel();
            $data = $_POST;
            $comment_model->addComment($data);
            redirect();
        }
    }

    public function deleteCommentAction(): void
    {
        if ($_POST) {
            $comment_model = new CommentModel();
            $id = $_POST['id'];
            $comment_model->deleteComment($id);
            exit('1');
        }
    }

    public function updateCommentAction(): void
    {
        if ($_POST) {
            $comment_model = new CommentModel();
            $id = $_POST['id'];
            $data = $_POST['data'];
            $comment_model->updateComment($id, $data);
            exit('1');
        }
    }
}
