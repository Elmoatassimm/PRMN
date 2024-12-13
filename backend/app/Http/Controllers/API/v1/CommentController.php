<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreCommentRequest;
use App\Models\{Project, Comment};
use App\Services\ResponseService;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        return $this->success(
            trans('messages.retrieved'),
            $project->comments()->with('user')->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Project $project)
    {
        $comment = $project->comments()->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return $this->success(
            trans('messages.created'),
            $comment->load('user'),
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Comment $comment ,Project $project)
    {
        if ($comment->project_id !== $project->id) {
            return $this->error(trans('messages.not_found'), [], 404);
        }

        

        $comment->delete();

        return $this->success(trans('messages.deleted'));
    }
}
