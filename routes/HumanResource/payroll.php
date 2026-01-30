<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResource\Payroll\SpreadsheetsController;
use App\Http\Controllers\HumanResource\Payroll\PayrollDeductionController;
use App\Http\Controllers\HumanResource\Payroll\PayrollDeductionInstallmentController;
use App\Http\Controllers\HumanResource\Payroll\PayrollExpenseController;

/*
|--------------------------------------------------------------------------
| Rutas de Planillas (Human Resource - Payroll)
|--------------------------------------------------------------------------
| Prefijo: /api/human-resource/payroll
*/

Route::prefix('human-resource/payroll')->group(function () {
    // Spreadsheets controller
    Route::get('/', [SpreadsheetsController::class, 'index'])->name('payroll.index');
    Route::post('/', [SpreadsheetsController::class, 'store'])->name('payroll.store');

    Route::patch('/{id}/state', [SpreadsheetsController::class, 'updateState'])->name('payroll.state.update')->whereNumber('id');



    // Deductions
    Route::prefix('deductions')->group(function () {
        Route::get('/', [PayrollDeductionController::class, 'index'])->name('hr.payroll.deductions.index');
        Route::get('/list', [PayrollDeductionController::class, 'getPayrollDeductions'])->name('hr.payroll.deductions.list');
        Route::post('/', [PayrollDeductionController::class, 'store'])->name('hr.payroll.deductions.store');
        Route::put('/{deduction_id}', [PayrollDeductionController::class, 'update'])->name('hr.payroll.deductions.update')->whereNumber('deduction_id');
        Route::delete('/{deduction_id}', [PayrollDeductionController::class, 'destroy'])->name('hr.payroll.deductions.destroy')->whereNumber('deduction_id');
        Route::get('/{deduction_id}/file/{file}', [PayrollDeductionController::class, 'showFile'])->name('hr.payroll.deductions.file')->whereNumber('deduction_id');

    });

    // Installments (Direct access)
    Route::prefix('installments')->group(function () {
        Route::get('/{deduction_id}/installments', [PayrollDeductionInstallmentController::class, 'getDeductionInstallment'])->name('hr.payroll.deductions.installments.list')->whereNumber('deduction_id');
        Route::post('/{installment_id}/prepayment', [PayrollDeductionInstallmentController::class, 'prepayment'])->name('hr.payroll.installments.prepayment')->whereNumber('installment_id');
        Route::get('/{installment_id}/file', [PayrollDeductionInstallmentController::class, 'showFile'])->name('hr.payroll.installments.file')->whereNumber('installment_id');
    });

    // Expenses
    Route::prefix('expenses')->group(function () {
        Route::get('/constants', [PayrollExpenseController::class, 'constants'])->name('payroll.detail.expense.constants.show');
        Route::get('/amount', [PayrollExpenseController::class, 'getAmount'])->name('payroll.detail.expense.getAmountByExpenseType');
        Route::get('/amount/massive', [PayrollExpenseController::class, 'getAmountMassive'])->name('payroll.detail.expense.getAmountByExpenseTypeMassive');
        Route::put('/massive', [PayrollExpenseController::class, 'massiveUpdate'])->name('payroll.detail.expenses.massive.update.opnuda');

        Route::put('/{id}', [PayrollExpenseController::class, 'update'])->name('payroll.detail.expense.update')->whereNumber('id');
        Route::delete('/{id}', [PayrollExpenseController::class, 'destroy'])->name('payroll.detail.expense.destroy')->whereNumber('id');
        Route::get('/{id}/file', [PayrollExpenseController::class, 'showFile'])->name('payroll.detail.expense.showPhoto')->whereNumber('id');
    });

    // Expenses (nested by payroll)
    Route::prefix('{payroll_id}/expenses')->whereNumber('payroll_id')->group(function () {
        Route::get('/', [PayrollExpenseController::class, 'index'])->name('payroll.detail.expense.index');
        Route::get('/list', [PayrollExpenseController::class, 'list'])->name('payroll.detail.getPayrollDetail.expense');
        Route::get('/search', [PayrollExpenseController::class, 'search'])->name('payroll.detail.expense.search');
    });

    // Expenses (nested by payroll detail)
    Route::get('/details/{payroll_detail_id}/expenses', [PayrollExpenseController::class, 'show'])
        ->name('payroll.detail.expenses.show')
        ->whereNumber('payroll_detail_id');
});

