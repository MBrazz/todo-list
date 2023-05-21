<?php

namespace App\Http\Requests\Exemplo;

use App\Http\Requests\CustomRequest;

class ExemploUpdateRequest extends CustomRequest
{

    public function rules()
    {
        return [
            'exemplo' => 'required|string',
        ];
    }

}
