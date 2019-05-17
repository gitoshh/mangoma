<?php

namespace App\Domains;
use App\Comment as CommentModel;

class Comment
{
    /**
     * create a new comment.
     *
     * @param string $comment
     * @param int $rating
     * @param int $userId
     * @param int $musicId
     * @return array
     */
    public function newComment(
        int $userId,
        int $musicId,
        ?string $comment = null,
        ?int $rating = null
    ): array {
        $commentModel = new CommentModel();
        $commentModel->comment = $comment;
        $commentModel->rating = $rating;
        $commentModel->userId = $userId;
        $commentModel->musicId = $musicId;

        if ($commentModel->save()) {
            return $commentModel->toArray();
        }

        return [];
    }

}