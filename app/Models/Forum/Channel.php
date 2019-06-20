<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

	protected $fillable = [
		'name', 'slug'
	];

	protected $table = 'forum_channels';

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * A channel consists of threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
