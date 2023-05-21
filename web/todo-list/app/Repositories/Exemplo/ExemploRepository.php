<?php

namespace App\Repositories\Exemplo;

use App\Repositories\Exemplo\Interfaces\ExemploInterface;
use App\Repositories\Base\BaseRepository;
use App\Models\Exemplo\Exemplo;

class ExemploRepository extends BaseRepository implements ExemploInterface
{

    public function __construct(Exemplo $model)
    {
        $this->model = $model;
    }

}