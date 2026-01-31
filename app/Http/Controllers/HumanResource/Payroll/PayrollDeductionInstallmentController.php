<?php

namespace App\Http\Controllers\HumanResource\Payroll;

use App\Http\Controllers\Controller;
use Src\HumanResource\Application\Services\Payroll\PayrollDeductionInstallmentQueryService;
use Src\HumanResource\Application\Services\Payroll\PayrollDeductionInstallmentCommandService;
use Src\HumanResource\Application\Dto\Payroll\PrepaymentDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollDeductionInstallmentController extends Controller
{
    public function __construct(
        private readonly PayrollDeductionInstallmentQueryService $queryService,
        private readonly PayrollDeductionInstallmentCommandService $commandService
    ) {
    }

    /**
     * Get installments for a deduction
     */
    public function getDeductionInstallment(Request $request, int $deductionId): JsonResponse
    {
        $filters = $request->only(['searchQuery', 'payment_status']);
        $data = $this->queryService->getDeductionInstallments($deductionId, $filters);
        
        return response()->json($data, 200);
    }

    /**
     * Register prepayment for an installment
     */
    public function prepayment(Request $request, int $installmentId): JsonResponse
    {
        // Si falla, Laravel lanza automáticamente ValidationException (422)
        $request->validate([
            'deposit_voucher' => 'required|file'
        ]);

        $dto = new PrepaymentDto(
            installmentId: $installmentId,
            depositVoucher: $request->file('deposit_voucher')
        );

        $data = $this->commandService->prepayment($dto);
        
        return response()->json($data, 200);
    }

    /**
     * Show file for an installment
     */
    public function showFile(int $installmentId)
    {
        return $this->queryService->showFile($installmentId);
    }
}