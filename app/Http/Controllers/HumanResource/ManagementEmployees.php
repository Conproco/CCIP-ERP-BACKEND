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

    /**
     * PUT /api/human-resource/employees/{id}
     * Actualizar empleado existente
     */
    public function update(UpdateManagementEmployees $request, int $id): JsonResponse
    {
        $dto = $this->updateNormalizer->normalize($request, $id);
        $this->updateUseCase->execute($dto);
        return response()->json(['message' => 'Employee updated successfully'], 200);
    }

    /**
     * DELETE /api/human-resource/employees/{id}
     * Eliminar empleado
     */
    public function destroy(int $id): JsonResponse
    {
        $this->deleteUseCase->execute($id);
        return response()->json(['message' => 'Employee deleted successfully'], 200);
    }

    /**
     * GET /api/human-resource/employees/{id}/details
     * Detalles completos del empleado
     */
    public function details(int $id): JsonResponse
    {
        $details = $this->queryService->getEmployeeDetails($id);
        return response()->json($details->toArray(), 200);
    }

    /**
     * POST /api/human-resource/employees/{id}/fire
     * Despedir empleado
     */
    public function fired(FiredContractEmployees $request, int $id): JsonResponse
    {
        $dto = $this->fireNormalizer->normalize($request, $id);
        $this->fireUseCase->execute($dto);
        return response()->json(['message' => 'Employee fired successfully'], 200);
    }

    /**
     * POST /api/human-resource/employees/{id}/reentry
     * Reingreso de empleado
     */
    public function reentry(ReentryEmployeeRequest $request, int $id): JsonResponse
    {
        // $id aquí es el contract_id según el legacy
        $dto = new ReentryEmployeeDto(
            contractId: $id,
            reentryDate: $request->input('reentry_date')
        );
        $this->reentryUseCase->execute($dto);
        return response()->json(['message' => 'Employee re-entry successful'], 200);
    }

    /**
     * GET /api/human-resource/employees/{id}/profile-image
     * Obtener imagen de perfil
     */
    public function showProfileImage(int $id)
    {
        $image = $this->queryService->getProfileImage($id);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }
        return $image;
    }

    /**
     * GET /api/human-resource/employees/education/{id}/download-cv
     * Descargar CV del empleado
     */
    public function downloadCv(int $id)
    {
        $file = $this->queryService->downloadCurriculum($id);

        if (!$file) {
            return response()->json(['error' => 'CV not found'], 404);
        }

        return $file;
    }

    /**
     * GET /api/human-resource/employees/contract/{id}/discharge-document
     * Ver documento de baja
     */
    public function showDischargeDocument(int $id)
    {
        $document = $this->queryService->getDischargeDocument($id);

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        return $document;
    }

    /**
     * GET /api/human-resource/employees/happy-birthday
     * Empleados con cumpleaños próximos
     */
    public function happyBirthday(): JsonResponse
    {
        $data = $this->queryService->getUpcomingBirthdays();
        return response()->json(['happyBirthday' => $data], 200);
    }

    /**
     * GET /api/human-resource/employees/active-constants
     * Obtener constantes de empleados activos para payroll
     */
    public function getActiveEmployeesConstant(): JsonResponse
    {
        $data = $this->queryService->getActiveEmployeesConstant();
        return response()->json($data, 200);
    }

}
