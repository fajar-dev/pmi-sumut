<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'sliders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'title',
        'description',
        'featured',
        'sort',
    ];
}
