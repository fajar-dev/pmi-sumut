<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'pages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'slug',
        'content'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
