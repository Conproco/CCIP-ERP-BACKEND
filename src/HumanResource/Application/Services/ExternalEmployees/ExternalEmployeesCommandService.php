<?php

namespace Src\HumanResource\Application\Services\ExternalEmployees;

use Illuminate\Support\Facades\DB;
use Src\HumanResource\Domain\Ports\Repositories\ExternalEmployees\ExternalEmployeeRepositoryInterface;
use Src\HumanResource\Application\Dto\ExternalEmployees\StoreExternalEmployeeDto;
use Src\HumanResource\Application\Dto\ExternalEmployees\UpdateExternalEmployeeDto;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Exception;
use Throwable;

class ExternalEmployeesCommandService
{
    private const PROFILE_IMAGE_PATH = 'image/profile/';
    private const CURRICULUM_PATH = 'documents/curriculum_vitae/';

    public function __construct(
        private ExternalEmployeeRepositoryInterface $externalEmployeeRepository,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Store a new external employee
     */
    public function store(StoreExternalEmployeeDto $dto): object
    {
        // Array para rastrear archivos subidos en este proceso (para rollback)
        $uploadedFiles = [];

        DB::beginTransaction();

        try {
            $data = $dto->toArray();

            // 1. Subir Imagen
            if ($dto->croppedImage) {
                $filename = $this->fileStorage->generateFilename(
                    'eemployee', 'profile', $dto->croppedImage->getClientOriginalExtension()
                );
                $this->fileStorage->store($dto->croppedImage, self::PROFILE_IMAGE_PATH, $filename);
                
                $data['cropped_image'] = $filename;
                // Registramos ruta completa para borrar si falla la BD
                $uploadedFiles[] = self::PROFILE_IMAGE_PATH . $filename;
            }

            // 2. Subir CV
            if ($dto->curriculumVitae) {
                $filename = $this->fileStorage->generateFilename(
                    'eemployee', 'cv', $dto->curriculumVitae->getClientOriginalExtension()
                );
                $this->fileStorage->store($dto->curriculumVitae, self::CURRICULUM_PATH, $filename);
                
                $data['curriculum_vitae'] = $filename;
                $uploadedFiles[] = self::CURRICULUM_PATH . $filename;
            }

            // 3. Intentar guardar en BD
            $employee = $this->externalEmployeeRepository->create($data);

            DB::commit();

            return $employee;

        } catch (Throwable $e) {
            DB::rollBack();

            // 4. COMPENSACIÓN: Borrar archivos físicos si la BD falló
            foreach ($uploadedFiles as $filePath) {
                $this->fileStorage->delete($filePath);
            }

            throw $e; // Re-lanzar el error para que el controlador lo maneje
        }
    }

    /**
     * Update an existing external employee
     */
    public function update(UpdateExternalEmployeeDto $dto): object
    {
        $uploadedFiles = []; // Archivos nuevos que subamos ahora
        $filesToDeleteOnSuccess = []; // Archivos viejos que borraremos solo si todo sale bien

        // Obtenemos el empleado actual para saber qué archivos viejos borrar
        // Asumo que tu repositorio tiene un find(), si no, debes obtener el modelo actual.
        $currentEmployee = $this->externalEmployeeRepository->find($dto->id); 

        DB::beginTransaction();

        try {
            $data = $dto->toArray();

            // 1. Manejo Imagen
            if ($dto->croppedImage) {
                // Subir NUEVA imagen
                $filename = $this->fileStorage->generateFilename(
                    'eemployee', 'profile', $dto->croppedImage->getClientOriginalExtension()
                );
                $this->fileStorage->store($dto->croppedImage, self::PROFILE_IMAGE_PATH, $filename);
                
                $data['cropped_image'] = $filename;
                $uploadedFiles[] = self::PROFILE_IMAGE_PATH . $filename;

                // Marcar VIEJA imagen para borrar al final (si existe)
                if ($currentEmployee->cropped_image) {
                    $filesToDeleteOnSuccess[] = self::PROFILE_IMAGE_PATH . $currentEmployee->cropped_image;
                }
            }

            // 2. Manejo CV
            if ($dto->curriculumVitae) {
                // Subir NUEVO CV
                $filename = $this->fileStorage->generateFilename(
                    'eemployee', 'cv', $dto->curriculumVitae->getClientOriginalExtension()
                );
                $this->fileStorage->store($dto->curriculumVitae, self::CURRICULUM_PATH, $filename);
                
                $data['curriculum_vitae'] = $filename;
                $uploadedFiles[] = self::CURRICULUM_PATH . $filename;

                // Marcar VIEJO CV para borrar al final
                if ($currentEmployee->curriculum_vitae) {
                    $filesToDeleteOnSuccess[] = self::CURRICULUM_PATH . $currentEmployee->curriculum_vitae;
                }
            }

            // 3. Actualizar BD
            $updatedEmployee = $this->externalEmployeeRepository->update($dto->id, $data);

            DB::commit();

            // 4. ÉXITO: Borrar los archivos ANTIGUOS que fueron reemplazados
            // Solo llegamos aquí si el commit funcionó
            foreach ($filesToDeleteOnSuccess as $oldFile) {
                $this->fileStorage->delete($oldFile);
            }

            return $updatedEmployee;

        } catch (Throwable $e) {
            DB::rollBack();

            // 5. ERROR: Borrar los archivos NUEVOS que acabamos de subir
            // Porque la actualización de BD falló, no debemos dejarlos en el servidor
            foreach ($uploadedFiles as $newFile) {
                $this->fileStorage->delete($newFile);
            }

            throw $e;
        }
    }

    /**
     * Delete an external employee
     */
    public function delete(int $id): bool
    {
        // Buscar primero para tener las rutas de los archivos
        $employee = $this->externalEmployeeRepository->find($id);

        if (!$employee) {
            return false;
        }

        DB::beginTransaction();

        try {
            // 1. Borrar de BD
            $deleted = $this->externalEmployeeRepository->delete($id);

            if (!$deleted) {
                throw new Exception("No se pudo eliminar el registro");
            }

            DB::commit();

            // 2. ÉXITO: Borrar archivos físicos
            // Solo si la BD confirmó el borrado, procedemos a borrar los archivos
            if ($employee->cropped_image) {
                $this->fileStorage->delete(self::PROFILE_IMAGE_PATH . $employee->cropped_image);
            }
            if ($employee->curriculum_vitae) {
                $this->fileStorage->delete(self::CURRICULUM_PATH . $employee->curriculum_vitae);
            }

            return true;

        } catch (Throwable $e) {
            DB::rollBack();
            // No borramos archivos porque la BD falló, los datos siguen ahí
            throw $e;
        }
    }
}