<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResource\Payroll\SpreadsheetsController;
use App\Http\Controllers\HumanResource\Payroll\PayrollDeductionController;
use App\Http\Controllers\HumanResource\Payroll\PayrollDeductionInstallmentController;

/*
|--------------------------------------------------------------------------
| Rutas de Planillas (Human Resource - Payroll)
|--------------------------------------------------------------------------
| Prefijo: /api/human-resource/payroll
*/

Route::prefix('human-resource/payroll')->group(function () {
    // Spreadsheets - Index
    Route::get('/', [SpreadsheetsController::class, 'index'])->name('hr.payroll.index');

    // Deductions
    Route::prefix('deductions')->group(function () {
        Route::get('/', [PayrollDeductionController::class, 'index'])->name('hr.payroll.deductions.index');
        Route::get('/list', [PayrollDeductionController::class, 'getPayrollDeductions'])->name('hr.payroll.deductions.list');
        Route::post('/', [PayrollDeductionController::class, 'store'])->name('hr.payroll.deductions.store');
        Route::put('/{deduction_id}', [PayrollDeductionController::class, 'update'])->name('hr.payroll.deductions.update')->whereNumber('deduction_id');
        Route::delete('/{deduction_id}', [PayrollDeductionController::class, 'destroy'])->name('hr.payroll.deductions.destroy')->whereNumber('deduction_id');
        Route::get('/{deduction_id}/file/{file}', [PayrollDeductionController::class, 'showFile'])->name('hr.payroll.deductions.file')->whereNumber('deduction_id');

        // Installments (Nested by resource)
       
    });

    // Installments (Direct access)
    Route::prefix('installments')->group(function () {
        Route::get('/{deduction_id}/installments', [PayrollDeductionInstallmentController::class, 'getDeductionInstallment'])->name('hr.payroll.deductions.installments.list')->whereNumber('deduction_id');
        Route::post('/{installment_id}/prepayment', [PayrollDeductionInstallmentController::class, 'prepayment'])->name('hr.payroll.installments.prepayment')->whereNumber('installment_id');
        Route::get('/{installment_id}/file', [PayrollDeductionInstallmentController::class, 'showFile'])->name('hr.payroll.installments.file')->whereNumber('installment_id');
    });

    
});

