<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'service';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'users_id',
        'title',
        'description',
        'delivery_time',
        'revision_limit',
        'price',
        'note',
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    // one to many
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'service_id');
    }

    public function advantageUser()
    {
        return $this->hasMany(AdvantageUser::class, 'service_id');
    }

    public function tagline()
    {
        return $this->hasMany(Tagline::class, 'service_id');
    }

    public function advantageService()
    {
        return $this->hasMany(AdvantageService::class, 'service_id');
    }

    public function thumbnailService()
    {
        return $this->hasMany(ThumbnailService::class, 'service_id');
    }
}
