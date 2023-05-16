<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\CustomRequest;

class TaskCreateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'content' => 'required|string',
            // 'user_id' => 'required|integer',
            'is_done' => 'required|boolean',
        ];
    }
}
