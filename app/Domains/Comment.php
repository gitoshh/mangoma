<?php

namespace App\Domains;

use App\Comment as CommentModel;
use App\Exceptions\NotFoundException;

class Comment
{
    /**
     * create a new comment.
     *
     * @param string $comment
     * @param int    $rating
     * @param int    $userId
     * @param int    $musicId
     *
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

    /**
     * Remove comment.
     *
     * @param int $commentId
     *
     * @throws NotFoundException
     */
    public function removeComment(int $commentId): void
    {
        $comment = CommentModel::find($commentId);
        if (empty($comment)) {
            throw new NotFoundException('comment not found');
        }
        $comment->delete();
    }
}
