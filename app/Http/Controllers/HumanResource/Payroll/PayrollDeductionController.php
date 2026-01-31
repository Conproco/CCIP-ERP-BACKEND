<?php

namespace App\Http\Controllers\HumanResource\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\Payroll\StorePayrollDeductionRequest;
use App\Http\Requests\HumanResource\Payroll\UpdatePayrollDeductionRequest;
use Src\HumanResource\Application\Services\Payroll\PayrollDeductionQueryService;
use Src\HumanResource\Application\Services\Payroll\PayrollDeductionCommandService;
use Src\HumanResource\Application\Normalizer\Payroll\StorePayrollDeductionRequestNormalizer;
use Src\HumanResource\Application\Normalizer\Payroll\UpdatePayrollDeductionRequestNormalizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollDeductionController extends Controller
{
    public function __construct(
        private readonly PayrollDeductionQueryService $queryService,
        private readonly PayrollDeductionCommandService $commandService,
        private readonly StorePayrollDeductionRequestNormalizer $storeNormalizer,
        private readonly UpdatePayrollDeductionRequestNormalizer $updateNormalizer
    ) {
    }

    /**
     * Get index data with reason options for the deduction form
     */
    public function index(): JsonResponse
    {
        $data = $this->queryService->getIndexData();
        
        // Asumiendo que getIndexData retorna un DTO o Arrayable
        return response()->json($data->toArray());
    }

    /**
     * Get paginated list of deductions with filters
     */
    public function getPayrollDeductions(Request $request): JsonResponse
    {
        $filters = $request->only(['searchquery', 'reason', 'opStartDate', 'opEndDate', 'opNoDate']);
        
        $data = $this->queryService->getPayrollDeductions($filters);
        
        return response()->json($data);
    }

    /**
     * Store a new payroll deduction
     */
    public function store(StorePayrollDeductionRequest $request): JsonResponse
    {
        // 1. Normalizar Request -> DTO
        $dto = $this->storeNormalizer->normalize($request);
        // 2. Ejecutar Servicio
        $deduction = $this->commandService->store($dto);
        // 3. Responder
        return response()->json($deduction, 201);
    }

    /**
     * Update a payroll deduction
     */
    public function update(UpdatePayrollDeductionRequest $request, int $deductionId): JsonResponse
    {
        // 1. Normalizar Request -> DTO
        $dto = $this->updateNormalizer->normalize($request, $deductionId);
        
        // 2. Ejecutar Servicio
        $deduction = $this->commandService->update($dto);
        
        // 3. Responder
        return response()->json($deduction);
    }

    /**
     * Delete a payroll deduction
     */
    public function destroy(int $deductionId): JsonResponse
    {
        $this->commandService->delete($deductionId);
        
        return response()->json(['Payroll deduction deleted successfully'], 200);
    }

    /**
     * Show a specific file from a deduction
     * Nota: No forzamos JsonResponse aquí porque suele retornar un Stream o Download
     */
    public function showFile(int $deductionId, string $file)
    {
        return $this->queryService->showFile($file, $deductionId);
    }
}