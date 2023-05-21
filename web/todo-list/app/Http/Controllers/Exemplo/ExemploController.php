<?php

namespace App\Http\Controllers\Exemplo;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\Exemplo\ExemploUpdateRequest;
use App\Http\Requests\Exemplo\ExemploCreateRequest;
use App\Services\Exemplo\Interfaces\ExemploInterface as ExemploService;


class ExemploController extends Controller
{
    use ApiResponser;

    public function get( ExemploService $exemplo, Request $request )
    {
        return $this->success( $request->exemplo() );
    }

    public function update( $id, ExemploService $exemplo, ExemploUpdateRequest $request )
    {
        return $this->success( $exemplo->save($request, $id), __('exemplo.successfully_updated'));
    }

    public function create( ExemploService $exemplo, ExemploCreateRequest $request )
    {
        return $this->success( $exemplo->create( $request ) );
    }

    public function list( ExemploService $exemplo, ListRequest $request, $skip=0, $take=10 )
    {
        return $this->success( $exemplo->list( $skip, $take, $request ) );
    }

    public function delete( $id, ExemploService $exemplo )
    {
        return $this->success( $exemplo->delete($id) );
    }

    public function getById( $id, ExemploService $exemplo )
    {
        return $this->success( $exemplo->getById( $id ) );
    }

}