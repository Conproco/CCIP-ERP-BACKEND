<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorDocument extends Model
{
    use HasFactory;

    protected $connection = 'mysql_contractors';
    protected $table = 'documents';

    protected $fillable = [
        'title',
        'subdivision_id',
        'exp_date',
        'e_employee_id',
        'employee_id',
    ];

    protected $appends = [
        'extension',
        'emp_name'
    ];

    public function subdivision()
    {
        return $this->belongsTo(ContractorSubdivision::class, 'subdivision_id');
    }
    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }

    public function e_employee()
    {
        return $this->belongsTo(ContractorExternalEmployee::class, 'e_employee_id');
    }

    public function getExtensionAttribute()
    {
        $fileName = $this->title;
        $fileInfo = pathinfo($fileName);
        return $fileInfo['extension'] ?? null;
    }

    public function getEmpNameAttribute()
    {
        if ($emp = $this->employee()->first()) {
            return $emp->name . ' ' . $emp->lastname;
        }
        if ($emp = $this->e_employee()->first()) {
            return $emp->name . ' ' . $emp->lastname;
        }
        return null;
    }

    protected static function booted()
    {
        static::created(function ($item) {
            $docreg = static::getDocumentRegister($item);
            static::storeDocumentRegisters($docreg, $item);
        });

        static::updated(function ($item) {
            $docreg = static::getDocumentRegister($item);
            static::storeDocumentRegisters($docreg, $item);
        });

        static::deleting(function ($item) {
            $docreg = ContractorDocumentRegister::where('document_id', $item->id)->first();
            if ($docreg) {
                $docreg->delete();
            }
        });

    }

    protected static function getDocumentRegister($item)
    {
        return $item->employee_id
            ? ContractorDocumentRegister::where('subdivision_id', $item->subdivision_id)
                ->where('employee_id', $item->employee_id)
                ->first()
            : ($item->e_employee_id
                ? ContractorDocumentRegister::where('subdivision_id', $item->subdivision_id)
                    ->where('e_employee_id', $item->e_employee_id)
                    ->first()
                : null
            );
    }

    protected static function storeDocumentRegisters($docreg, $item)
    {
        if ($docreg) {
            $docreg->update([
                'subdivision_id' => $item->subdivision_id,
                'document_id' => $item->id,
                'employee_id' => $item->employee_id,
                'e_employee_id' => $item->e_employee_id,
                'exp_date' => $item->exp_date,
                'state' => 'Completado',
            ]);
        } else {
            $docreg = ContractorDocumentRegister::create([
                'subdivision_id' => $item->subdivision_id,
                'document_id' => $item->id,
                'employee_id' => $item->employee_id,
                'e_employee_id' => $item->e_employee_id,
                'exp_date' => $item->exp_date,
                'state' => 'Completado',
            ]);
        }
    }
}
