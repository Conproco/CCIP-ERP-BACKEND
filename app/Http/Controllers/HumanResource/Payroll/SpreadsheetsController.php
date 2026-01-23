<?php

namespace App\Http\Controllers\HumanResource\Payroll;

use App\Http\Controllers\Controller;
use Src\HumanResource\Application\Services\Payroll\PayrollQueryService;
use Illuminate\Http\JsonResponse;

class SpreadsheetsController extends Controller
{
    public function __construct(
        private PayrollQueryService $payrollQueryService
    ) {
    }

    /**
     * Get paginated list of payrolls
     */
    public function index(): JsonResponse
    {
        $data = $this->payrollQueryService->getIndexData();
        return response()->json($data->toArray());
    }
}
