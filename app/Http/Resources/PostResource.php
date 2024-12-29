<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'user' => new UserResource($this->user),
            'comments_count' => $this->comment->count(),
            $this->mergeWhen($request->is('api/post/*'), [
                'comments' => $this->comment->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'email' => $comment->user->email
                        ],
                        'created_at' => $comment->created_at,
                        'updated_at' => $comment->updated_at
                    ];
                })
            ])


        ];
    }
}
