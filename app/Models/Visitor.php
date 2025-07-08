<?php

namespace App\Models;

use App\Models\Page;
use App\Models\Post;
use App\Models\Agenda;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'visitors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'page_id',
        'post_id',
        'agenda_id',
        'ip_address',
        'user_agent',
        'url',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }
}
