<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'services';
    protected $primaryKey = 'id';

    protected $fillable = [
        'icon',
        'description',
        'cta_url'
    ];
}
