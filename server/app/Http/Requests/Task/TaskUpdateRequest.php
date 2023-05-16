<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\CustomRequest;

class TaskUpdateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'content' => 'string',
            'is_done' => 'boolean',
        ];
    }

}
