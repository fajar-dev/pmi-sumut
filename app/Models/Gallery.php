<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'galleries';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
