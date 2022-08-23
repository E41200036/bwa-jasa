<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\map;

class DetailUser extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'detail_user';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'users_id',
        'photo',
        'role',
        'contact_number',
        'biography',
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    // one to one
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    // one to many
    public function expirienceUser()
    {
        return $this->hasMany(ExperienceUser::class, 'detail_user_id', 'id');
    }
}
