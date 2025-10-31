<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Website extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    /**
     * @return HasMany
     */
    public function subscribers(): HasMany
    {
        return $this->belongsToMany(Subscriber::class, 'subscriber_website')
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
