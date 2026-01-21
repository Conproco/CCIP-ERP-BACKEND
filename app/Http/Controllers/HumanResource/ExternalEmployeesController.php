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

   
}
