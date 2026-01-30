<?php

namespace App\Http\Controllers\HumanResource\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\Payroll\StorePayrollRequest;
use Src\HumanResource\Application\Services\Payroll\PayrollQueryService;
use Src\HumanResource\Application\Services\Payroll\PayrollCommandService;
use Src\HumanResource\Application\Data\Payroll\StorePayrollData;
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
     * 
     * @response array{payroll: PayrollData[], pagination: array}
     */
    public function index(): JsonResponse
    {
        $data = $this->payrollQueryService->getIndexData();
        return response()->json($data);
    }

    /**
     * Store a new payroll with all associated records
     * 
     * @bodyParam month string required The payroll month (YYYY-MM format). Example: 2026-01
     * @bodyParam state boolean required Initial payroll state. Example: false
     */
    public function store(StorePayrollRequest $request): JsonResponse
    {
        try {
            $data = StorePayrollData::from($request->validated());
            $payroll = $this->payrollCommandService->store($data);
            return response()->json($payroll, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update payroll state to closed/completed
     *
     * @urlParam id integer required The payroll ID. Example: 1
     */
    public function updateState(int $id): JsonResponse
    {
        $payroll = $this->payrollCommandService->updateState($id);
        return response()->json($payroll);
    }

    /**
     * Delete a payroll and its associated records
     *
     * @urlParam id integer required The payroll ID to delete. Example: 1
     */
    public function destroy(int $id): JsonResponse
    {
        $this->payrollCommandService->destroy($id);
        return response()->json(['message' => 'Planilla eliminada exitosamente'], 200);
    }

    /**
     * Get a specific payroll with its details and metadata (pension types)
     *
     * @urlParam id integer required The payroll ID. Example: 1
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->payrollQueryService->find($id);
        return response()->json($data);
    }
}
