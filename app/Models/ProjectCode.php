<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_title_id',
        'code_id',
        'status',
    ];

    protected $appends = [
        'replaceable_status',
        'rejected_quantity',
        // 'code_name'
    ];

    //CALCULATED
    public function getReplaceableStatusAttribute()
    {
        $hasImages = $this->imagecodeproject()->exists();

        return $hasImages ? "En proceso" : "Sin Trabajar";
    }

    public function getRejectedQuantityAttribute()
    {
        return $this->imagecodeproject()->where('state', 0)->count();
    }

    // public function getCodeNameAttribute()
    // {
    //     $code = $this->code;
    //     return $code->code;
    // }


    //RELATIONS

    // Relación con el modelo Preproject
    public function projectTitle()
    {
        return $this->belongsTo(ProjectTitle::class, 'project_title_id');
    }

    // Relación con el modelo Code
    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }

    public function imagecodeproject()
    {
        return $this->HasMany(Imagesproject::class);
    }
}
