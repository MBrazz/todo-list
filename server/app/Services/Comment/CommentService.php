<?php
namespace App\Services\Comment;

use App\Services\Base\BaseService;
use App\Services\Comment\Interfaces\CommentInterface;
use App\Repositories\Comment\Interfaces\CommentInterface as CommentRepository;

use Illuminate\Http\Request;

class CommentService extends BaseService implements CommentInterface
{
    private $commentRepository;

    public function __construct (
        CommentRepository $commentRepository
    )
    {
        $this->commentRepository = $commentRepository;
    }

    public function save( Request $request, $id )
    {
        $comment = $this->commentRepository->update( $request->validated(), $id );

        return $comment->fresh();
    }

    public function create( Request $request )
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        return $this->commentRepository->create($validated);
    }

    public function delete( $id )
    {
        $comment = $this->commentRepository->getById($id);
        $comment->delete();
    }

    public function getById( $id )
    {
        return $this->commentRepository->getById( $id );
    }

    public function getByUser (Request $request) {
        $comment = $this->commentRepository->newInstance();
        return $comment->byUserId($request->user()->id)->get();
    }

    public function list( $skip, $take, $request )
    {
        return $this->commentRepository->list($skip, $take, $request, [], ['byTask']);
    }

}
