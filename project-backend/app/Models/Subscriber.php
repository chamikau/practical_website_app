<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Subscriber extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email'];

    /**
     * @return BelongsToMany
     */
    public function websites(): BelongsToMany
    {
        return $this->belongsToMany(Website::class, 'subscriber_website');
    }

    /**
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'subscriber_post');
    }

}
