<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'messages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read',
    ];
}
