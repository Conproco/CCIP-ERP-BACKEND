<?php

declare(strict_types=1);

namespace App\Http\Controllers\HumanResource\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\HumanResource\Payroll\MassiveUpdateExpenseRequest;
use App\Http\Requests\HumanResource\Payroll\UpdatePayrollDetailExpenseRequest;
use App\Http\Requests\HumanResource\Payroll\GetAmountByExpenseTypeRequest;
use App\Http\Requests\HumanResource\Payroll\GetAmountMassiveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\HumanResource\Application\Normalizer\Payroll\MassiveUpdateExpenseNormalizer;
use Src\HumanResource\Application\Normalizer\Payroll\UpdatePayrollDetailExpenseNormalizer;
use Src\HumanResource\Application\Normalizer\Payroll\GetAmountByExpenseTypeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\Payroll\GetAmountMassiveRequestNormalizer;
use Src\HumanResource\Application\Services\Payroll\PayrollDetailExpenseCommandService;
use Src\HumanResource\Application\Services\Payroll\PayrollDetailExpenseQueryService;
use Src\HumanResource\Domain\Enums\Payroll\PayrollDocType;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseType;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseStateType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Mime\Message;

class PayrollExpenseController extends Controller
{
    public function __construct(
        private readonly PayrollDetailExpenseQueryService $queryService,
        private readonly PayrollDetailExpenseCommandService $commandService,
        private readonly UpdatePayrollDetailExpenseNormalizer $updateNormalizer,
        private readonly MassiveUpdateExpenseNormalizer $massiveNormalizer,
        private readonly GetAmountByExpenseTypeRequestNormalizer $getAmountNormalizer,
        private readonly GetAmountMassiveRequestNormalizer $getAmountMassiveNormalizer,
    ) {
    }

    /**
     * GET /expenses/constants
     * Get expense type and doc type constants
     */
    public function constants(): JsonResponse
    {
        return response()->json([
            'expenseTypes' => PayrollExpenseType::values(),
            'docTypes' => PayrollDocType::values(),
            'stateTypes' => PayrollExpenseStateType::values(),
        ]);
    }

    /**
     * GET /{payroll_id}/expenses
     * Get index data for a payroll (constants + payroll info)
     */
    public function index(int $payrollId): JsonResponse
    {
        return response()->json($this->queryService->getIndexData($payrollId));
    }

    /**
     * GET /{payroll_id}/expenses/list
     * Get paginated expenses for a payroll
     */
    public function list(int $payrollId): JsonResponse
    {
        return response()->json($this->queryService->getByPayrollId($payrollId));
    }

    /**
     * GET /details/{payroll_detail_id}/expenses
     * Get expenses for a specific payroll detail
     */
    public function show(int $payrollDetailId): JsonResponse
    {
        return response()->json($this->queryService->getByPayrollDetailId($payrollDetailId));
    }

    /**
     * PUT /expenses/{id}
     * Update a payroll detail expense
     * 
     */
    public function update(int $id, UpdatePayrollDetailExpenseRequest $request): JsonResponse
    {
        $dto = $this->updateNormalizer->normalize($request);
        $expense = $this->commandService->update($dto);
        return response()->json($expense);
    }

    /**
     * DELETE /expenses/{id}
     * Delete a payroll detail expense
     */
    public function destroy(int $id): JsonResponse
    {
        $this->commandService->delete($id);
        return response()->json(["Message" => "Usuario eliminado correctamente"], 204);
    }

    /**
     * PUT /expenses/massive
     * Massive update of expenses (operation date and number)
     */
    public function massiveUpdate(MassiveUpdateExpenseRequest $request): JsonResponse
    {
        $dto = $this->massiveNormalizer->normalize($request);
        $expenses = $this->commandService->massiveUpdate($dto);
        return response()->json($expenses);
    }

    /**
     * GET /{payroll_id}/expenses/search
     * Search/filter expenses with multiple criteria 
     */
    public function search(Request $request, int $payrollId): JsonResponse
    {
        $filters = $request->all();
        return response()->json($this->queryService->search($payrollId, $filters));
    }

    /**
     * GET /expenses/{id}/file
     * Get expense file/photo
     */
    public function showFile(int $id): BinaryFileResponse
    {
        return $this->queryService->showFile($id);
    }

    /**
     * Get amount by expense type for a single payroll detail
     * 
     * Returns the calculated amount for a specific expense type for a payroll detail.
     * 
     * @queryParam payroll_detail_id integer required The payroll detail ID. Example: 1
     * @queryParam type string required The expense type (from ExpenseType enum). Example: expense_type_value
     * 
     * @response 200 {
     *   "amount": 1500.50
     * }
     */
    public function getAmount(GetAmountByExpenseTypeRequest $request): JsonResponse
    {
        $dto = $this->getAmountNormalizer->normalize($request);
        $amount = $this->queryService->getAmountByExpenseType(
            $dto->payrollDetailId,
            $dto->type
        );

        return response()->json($amount);
    }

    /**
     * Get amounts by expense type for multiple payroll details
     * 
     * Returns calculated amounts for a specific expense type for multiple payroll details.
     * Accepts IDs as array (?ids[]=1&ids[]=2) or comma-separated string (?ids=1,2,3).
     * 
     * @queryParam ids[] integer[] required Array of payroll detail IDs. Example: [1,2,3]
     * @queryParam type string required The expense type (from ExpenseType enum). Example: expense_type_value
     * 
     * @response 200 {
     *   "1": 1500.50,
     *   "2": 2300.75,
     *   "3": 1800.00
     * }
     */
    public function getAmountMassive(GetAmountMassiveRequest $request): JsonResponse
    {
        $dto = $this->getAmountMassiveNormalizer->normalize($request);

        $amounts = $this->queryService->getAmountsByExpenseTypeMassive(
            $dto->ids,
            $dto->type
        );

        return response()->json($amounts);
    }
}
