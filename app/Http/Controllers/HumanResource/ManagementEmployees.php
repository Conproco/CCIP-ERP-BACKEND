<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\HumanResource\Application\Services\Employees\EmployeeQueryService;
use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use App\Http\Controllers\Controller;

class ManagementEmployees extends Controller
{
    public function __construct(
        protected EmployeeQueryService $queryService,
        protected EmployeeListNormalizer $listNormalizer
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
        $employees = $this->queryService->searchEmployees($state,$search,$costLine);    
        
        return response()->json($employees, 200);
    }

}
