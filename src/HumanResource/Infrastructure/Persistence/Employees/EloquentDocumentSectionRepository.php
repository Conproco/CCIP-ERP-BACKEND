<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\DocumentSectionRepositoryInterface;
use App\Models\DocumentSection as DocumentSectionModel;

class EloquentDocumentSectionRepository implements DocumentSectionRepositoryInterface
{
    public function __construct(private DocumentSectionModel $model)
    {
    }

    public function getAllVisibleWithSubdivisions(): object
    {
        return $this->model->with([
            'subdivisions' => function ($subq) {
                $subq->where('is_visible', true);
            }
        ])
            ->where('is_visible', true)
            ->get();
    }
}
