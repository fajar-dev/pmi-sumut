<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubMenu extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'sub_menus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'menu_id',
        'name',
        'description',
        'sort',
        'url',
        'is_blank',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
