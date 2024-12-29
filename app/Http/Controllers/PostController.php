<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::with('comment')->get());
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $fields = $request->validated();
        $post = $request->user()->posts()->create($fields);
        return PostResource::make($post);
    }

    public function storeValidate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ], 403);
        }

        try {
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;
            $post->user_id = Auth::user()->id;
            $post->save();

            return response()->json([
                PostResource::make($post)
            ], 200);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // return $post->with('user', 'comment')->first();
        return PostResource::make($post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $fields = $request->validated();
        $post->update($fields);
        return PostResource::make($post);
    }

    public function updateValidate(Request $request, Post $post)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ], 403);
        }

        try {
            $post->update([
                'title' => $request->title,
                'body' => $request->body,
            ]);
            return response()->json([
                'message' => 'The Post successfully updated.',
                'post' => $post,

            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => 'The Post Successfuly Deleted',
        ]);
    }
}
