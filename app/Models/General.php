<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class General extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'generals';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'subtitle',
        'logo',
        'meta_description',
        'donation_url',
        'about_photo',
        'about_name',
        'about_description',
        'welcome_speech',
        'vision_mission',
        'goals',
        'address',
        'phone',
        'hotline',
        'email',
        'maps_url',
        'social_instagram',
        'social_youtube',
        'social_facebook',
        'social_twitter',
    ];

}
