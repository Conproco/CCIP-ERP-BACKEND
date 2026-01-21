<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HumanResource\ExternalEmployer\StoreExternalEmployeeRequest;
use App\Http\Requests\HumanResource\ExternalEmployer\UpdateExternalEmployeeRequest;
use Src\HumanResource\Application\Services\ExternalEmployees\ExternalEmployeesQueryService;
use Src\HumanResource\Application\Services\ExternalEmployees\ExternalEmployeesCommandService;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\StoreExternalEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\UpdateExternalEmployeeRequestNormalizer;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExternalEmployeesController extends Controller
{
    public function __construct(
        protected ExternalEmployeesQueryService $queryService,
        protected ExternalEmployeesCommandService $commandService,
        protected StoreExternalEmployeeRequestNormalizer $storeNormalizer,
        protected UpdateExternalEmployeeRequestNormalizer $updateNormalizer
    ) {
    }

    /**
     * GET /api/human-resource/external-employees
     * Index - returns cost lines for filtering
     */
    public function index(): JsonResponse
    {
        $response = $this->queryService->getIndexData();
        return response()->json($response->toArray(), 200);
    }

    /**
     * GET /api/human-resource/external-employees/list
     * Get paginated list of external employees with filters
     */
    public function getExternalEmployees(Request $request): JsonResponse
    {
        $filters = $request->only(['searchQuery', 'cost_line']);
        $employees = $this->queryService->getExternalEmployees($filters);
        return response()->json($employees, 200);
    }
    
    /**
     * POST /api/human-resource/external-employees
     * Create a new external employee
     */
    public function createExternalEmployee(StoreExternalEmployeeRequest $request): JsonResponse
    {
        $dto = $this->storeNormalizer->normalize($request);
        $externalEmployee = $this->commandService->store($dto);
        return response()->json($externalEmployee, 201);
    }

    /**
     * PUT /api/human-resource/external-employees/{external_id}
     * Update an existing external employee
     */
    public function updateExternalEmployee(UpdateExternalEmployeeRequest $request, int $external_id): JsonResponse
    {
        $dto = $this->updateNormalizer->normalize($request, $external_id);
        $externalEmployee = $this->commandService->update($dto);
        return response()->json($externalEmployee, 200);
    }

    /**
     * DELETE /api/human-resource/external-employees/{external_id}
     * Delete an external employee
     */
    public function deleteExternalEmployee(int $external_id): JsonResponse
    {
        $this->commandService->delete($external_id);
        return response()->json(['message' => 'External employee deleted successfully'], 200);
    }

   
}
