<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\CustomRequest;

class CommentCreateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'task_id' => 'required|integer',
            'parent_id' => 'integer',
            'text' => 'required|string'
        ];
    }
}
