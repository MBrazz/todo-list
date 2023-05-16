<?php
namespace App\Services\Exemplo;

use App\Services\Base\BaseService;
use App\Services\Exemplo\Interfaces\ExemploInterface;
use App\Repositories\Exemplo\Interfaces\ExemploInterface as ExemploRepository;

use Illuminate\Http\Request;

class ExemploService extends BaseService implements ExemploInterface
{
    private $exemploRepository;

    public function __construct (
        ExemploRepository $exemploRepository
    )
    {
        $this->exemploRepository = $exemploRepository;
    }

    public function save( Request $request, $id )
    {
        $exemplo = $this->exemploRepository->update( $request->validated(), $id );

        return $exemplo->fresh();
    }

    public function create( Request $request )
    {
        $exemplo = $this->exemploRepository->create($request->validated());

        return $exemplo->fresh();
    }

    public function delete( $id )
    {
        $exemplo = $this->exemploRepository->getById($id);
        $exemplo->delete();
    }

    public function getById( $id )
    {
        return $this->exemploRepository->getById( $id );
    }

    public function list( $skip, $take, $request )
    {
        return $this->exemploRepository->list($skip, $take, $request, [], ['byExemplo']);
    }

}
