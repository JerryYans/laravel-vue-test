<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Favorite;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    /**
     * Fields that are mass assignable
     * @var array
     */
    protected $fillable = [
        'title',
        'body'
    ];

    public function favorited()
    {
        return (bool) Favorite::where('user_id', Auth::id())
            ->where('post_id', $this->id)
            ->first();
    }
}
