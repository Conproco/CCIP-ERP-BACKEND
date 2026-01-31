<?php

namespace Src\HumanResource\Application\Services\ExternalEmployees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\ExternalEmployees\ExternalEmployeeRepositoryInterface;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\ExternalEmployeeIndexNormalizer;
use Src\HumanResource\Application\Dto\ExternalEmployees\ExternalEmployeeIndexDto;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExternalEmployeesQueryService
{
    private const PROFILE_IMAGE_PATH = 'image/profile/';
    private const CURRICULUM_PATH = 'documents/curriculum_vitae/';
    private const DEFAULT_IMAGE_PATH = 'image/projectimage/';

    public function __construct(
        private CostLineRepositoryInterface $costLineRepository,
        private ExternalEmployeeRepositoryInterface $externalEmployeeRepository,
        private ExternalEmployeeIndexNormalizer $indexNormalizer,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Get index data for external employees view
     * Returns cost lines for filtering
     */
    public function getIndexData(): ExternalEmployeeIndexDto
    {
        $costLines = $this->costLineRepository->getAll();
        return $this->indexNormalizer->normalize($costLines);
    }

    /**
     * Get external employees with pagination and filters
     */
    public function getExternalEmployees(array $filters = []): LengthAwarePaginator
    {
        return $this->externalEmployeeRepository->getAllPaginateWithRelations($filters);
    }

    /**
     * Get all cost lines as array
     */
    public function getCostLines(): array
    {
        $costLines = $this->costLineRepository->getAll();
        $result = [];

        foreach ($costLines as $costLine) {
            $result[] = [
                'id' => $costLine->id,
                'name' => $costLine->name,
            ];
        }

        return $result;
    }

    /**
     * Get profile image for external employee
     */
    public function getProfileImage(int $externalEmployeeId): BinaryFileResponse
    {
        $employee = $this->externalEmployeeRepository->find($externalEmployeeId);

        if (!$employee || !$employee->getCroppedImage()) {
            return $this->fileStorage->get(self::DEFAULT_IMAGE_PATH . 'DefaultUser.png');
        }

        return $this->fileStorage->get(self::PROFILE_IMAGE_PATH . $employee->getCroppedImage());
    }

    /**
     * Get curriculum vitae preview for external employee
     */
    public function getCurriculumVitae(int $externalEmployeeId): BinaryFileResponse
    {
        $employee = $this->externalEmployeeRepository->find($externalEmployeeId);

        if (!$employee || !$employee->getCurriculumVitae()) {
            abort(404, 'CV no encontrado');
        }

        return $this->fileStorage->get(self::CURRICULUM_PATH . $employee->getCurriculumVitae());
    }
}

