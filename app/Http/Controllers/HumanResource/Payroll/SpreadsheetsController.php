<?php

namespace App\Http\Controllers\HumanResource\Payroll;

use App\Http\Controllers\Controller;
use Src\HumanResource\Application\Services\Payroll\PayrollQueryService;
use Src\HumanResource\Application\Services\Payroll\PayrollCommandService;
use Illuminate\Http\JsonResponse;

class SpreadsheetsController extends Controller
{
    public function __construct(
        private PayrollQueryService $payrollQueryService,
        private PayrollCommandService $payrollCommandService
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

    /**
     * PATCH /payroll/{id}/state
     * Update payroll state to true (closed/completed)
     * 
     * @urlParam id integer required The payroll ID. Example: 1
     * @response 200 {
     *   "id": 1,
     *   "month": "2024-01",
     *   "state": true,
     *   ...
     * }
     */
    public function updateState(int $id): JsonResponse
    {
        $payroll = $this->payrollCommandService->updateState($id);
        return response()->json($payroll);
    }
}
