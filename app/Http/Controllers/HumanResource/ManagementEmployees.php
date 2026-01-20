<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HumanResource\Employees\CreateManagementEmployees;
use Src\HumanResource\Application\Services\Employees\EmployeeQueryService;
use Src\HumanResource\Application\UseCases\Employees\StoreEmployeeUseCase;
use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use Src\HumanResource\Application\Normalizer\StoreEmployeeRequestNormalizer;
use App\Http\Controllers\Controller;

class ManagementEmployees extends Controller
{
    public function __construct(
        protected EmployeeQueryService $queryService,
        protected EmployeeListNormalizer $listNormalizer,
        protected StoreEmployeeUseCase $storeUseCase,
        protected StoreEmployeeRequestNormalizer $storeNormalizer
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->queryService->getAllActive(true, 15);
        return response()->json($response->toArray(), 200);
    }

    public function search(Request $request): JsonResponse
    {
        $state = $request->input('state');
        $search = $request->input('search');
        $costLine = $request->input('cost_line', []);
        $employees = $this->queryService->searchEmployees($state, $search, $costLine);

        return response()->json($employees, 200);
    }

    public function create(): JsonResponse
    {
        $response = $this->queryService->getCreateFormData();
        return response()->json($response, 200);
    }

    public function store(CreateManagementEmployees $request): JsonResponse
    {
        $dto = $this->storeNormalizer->normalize($request);
        $employeeId = $this->storeUseCase->execute($dto);
        return response()->json(['employee_id' => $employeeId], 201);
    }
}

