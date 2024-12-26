<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['comment'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
