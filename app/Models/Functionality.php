<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Functionality extends Model
{
    use HasFactory;

    protected $table = 'functionalities';

    protected $fillable = [
        "key_name",
        "display_name",
        "module_id",
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'functionality_permissions');
    }


}
