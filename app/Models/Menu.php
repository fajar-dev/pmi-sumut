<?php

namespace App\Models;

use App\Models\SubMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'menus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'sort',
        'url',
        'is_blank',
    ];

    public function subMenus()
    {
        return $this->hasMany(SubMenu::class);
    }
}
