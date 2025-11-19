<?php
namespace App\Domain\Websites\Models;
use App\Domain\Posts\Models\Post;
use App\Domain\Subscribers\Models\Subscriber;
use Database\Factories\WebsiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Website extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    protected static function newFactory()
    {
        return WebsiteFactory::new();
    }

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
