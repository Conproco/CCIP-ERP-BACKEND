<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HumanResource\Employees\CreateManagementEmployees;
use App\Http\Requests\HumanResource\Employees\UpdateManagementEmployees;
use App\Http\Requests\HumanResource\Employees\FiredContractEmployees;
use App\Http\Requests\HumanResource\Employees\ReentryEmployeeRequest;
use Src\HumanResource\Application\Services\Employees\EmployeeQueryService;
use Src\HumanResource\Application\UseCases\Employees\StoreEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\UpdateEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\FireEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\ReentryEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\DeleteEmployeeUseCase;
use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use Src\HumanResource\Application\Normalizer\StoreEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\UpdateEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\FireEmployeeRequestNormalizer;
use Src\HumanResource\Application\Dto\ReentryEmployeeDto;
use App\Http\Controllers\Controller;

class ManagementEmployees extends Controller
{
    public function __construct(
        protected EmployeeQueryService $queryService,
        protected EmployeeListNormalizer $listNormalizer,
        protected StoreEmployeeUseCase $storeUseCase,
        protected StoreEmployeeRequestNormalizer $storeNormalizer,
        protected UpdateEmployeeUseCase $updateUseCase,
        protected UpdateEmployeeRequestNormalizer $updateNormalizer,
        protected FireEmployeeUseCase $fireUseCase,
        protected FireEmployeeRequestNormalizer $fireNormalizer,
        protected ReentryEmployeeUseCase $reentryUseCase,
        protected DeleteEmployeeUseCase $deleteUseCase
    ) {
    }

    /**
     * GET /api/human-resource/employees
     * Lista empleados activos paginados
     */
    public function index(Request $request): JsonResponse
    {
        $response = $this->queryService->getAllActive(true, 15);
        return response()->json($response->toArray(), 200);
    }

    /**
     * GET /api/human-resource/employees/search
     * Buscar empleados con filtros
     */
    public function search(Request $request): JsonResponse
    {
        $state = $request->input('state');
        $search = $request->input('search');
        $costLine = $request->input('cost_line', []);
        $employees = $this->queryService->searchEmployees($state, $search, $costLine);

        return response()->json($employees, 200);
    }

    /**
     * GET /api/human-resource/employees/information_additional
     * Datos para formulario de creación
     */
    public function create(): JsonResponse
    {
        $response = $this->queryService->getCreateFormData();
        return response()->json($response, 200);
    }

    /**
     * POST /api/human-resource/employees
     * Crear nuevo empleado
     */
    public function store(CreateManagementEmployees $request): JsonResponse
    {
        $dto = $this->storeNormalizer->normalize($request);
        $employeeId = $this->storeUseCase->execute($dto);
        return response()->json(['employee_id' => $employeeId], 201);
    }

    /**
     * GET /api/human-resource/employees/{id}
     * Datos para formulario de edición
     */
    public function edit(int $id): JsonResponse
    {
        $response = $this->queryService->getEmployeeForEdit($id);
        return response()->json($response, 200);
    }

    
}
