<?php

namespace Src\HumanResource\Application\Data\Payroll;

use Spatie\LaravelData\Data;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Data object for paginated Payroll list.
 */
class PayrollPaginatedData extends Data
{
    public function __construct(
        /** @var PayrollData[] */
        public readonly array $payroll,
        public readonly array $pagination,
    ) {
    }

    /**
     * Create from LengthAwarePaginator
     */
    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $payrollItems = $paginator->getCollection()->map(
            fn($item) => PayrollData::fromModel($item)
        )->all();

        $pagination = [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];

        return new self(
            payroll: $payrollItems,
            pagination: $pagination,
        );
    }
}
