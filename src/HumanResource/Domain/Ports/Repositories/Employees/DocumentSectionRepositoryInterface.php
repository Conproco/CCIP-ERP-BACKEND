<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

interface DocumentSectionRepositoryInterface
{
    /**
     * Get all visible document sections with their visible subdivisions
     */
    public function getAllVisibleWithSubdivisions(): object;
}
