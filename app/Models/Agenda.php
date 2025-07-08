<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'agendas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'content',
        'address',
        'start_date',
        'end_date',
        'maps_url',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function visitorCount()
    {
        return $this->hasMany(Visitor::class, 'agenda_id', 'id')->count();
    }
}
