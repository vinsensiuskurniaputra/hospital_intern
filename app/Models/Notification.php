<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'icon',
    ];

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Get the max ID
            $maxId = static::max('id');
            
            // Find the next available ID by checking gaps
            $nextId = 1;
            while (static::find($nextId) && $nextId <= $maxId) {
                $nextId++;
            }
            
            $model->id = $nextId;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
