<?php

namespace App\Http\Controllers\Comment;


use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Requests\Comment\CommentCreateRequest;
use App\Services\Comment\Interfaces\CommentInterface as CommentService;

class CommentController extends Controller
{
    use ApiResponser;

    public function get(CommentService $comment, Request $request)
    {
        return $this->success($request->comment());
    }

    public function update($id, CommentService $comment, CommentUpdateRequest $request)
    {
        return $this->success($comment->save($request, $id), __('comment.successfully_updated'));
    }

    public function create(CommentService $comment, CommentCreateRequest $request)
    {
        return $this->success($comment->create($request));
    }

    public function list(CommentService $comment, ListRequest $request, $skip = 0, $take = 10)
    {
        return $this->success($comment->list($skip, $take, $request));
    }

    public function delete($id, CommentService $comment)
    {
        return $this->success($comment->delete($id));
    }

    public function getById($id, CommentService $comment)
    {
        return $this->success($comment->getById($id));
    }

    public function getByUser(CommentService $user, Request $request)
    {
        return $this->success($user->getByUser($request));
    }
}
