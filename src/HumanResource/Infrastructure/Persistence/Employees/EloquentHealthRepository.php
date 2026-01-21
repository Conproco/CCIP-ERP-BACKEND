<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Entities\Employees\Health;
use Src\HumanResource\Domain\Ports\Repositories\Employees\HealthRepositoryInterface;
use App\Models\Health as HealthModel;

class EloquentHealthRepository implements HealthRepositoryInterface
{
    public function __construct(
        private HealthModel $model
    ) {
    }

    public function find(int $id): ?Health
    {
        $eloquentModel = $this->model->find($id);

        if (!$eloquentModel) {
            return null;
        }

        return $this->toDomainEntity($eloquentModel);
    }

    public function findByEmployeeId(int $employeeId): ?Health
    {
        $eloquentModel = $this->model->where('employee_id', $employeeId)->first();

        if (!$eloquentModel) {
            return null;
        }

        return $this->toDomainEntity($eloquentModel);
    }

    public function save(Health $health): Health
    {
        // Convertimos la Entidad a array para la BD
        $data = $health->toArray();

        // updateOrCreate maneja la lógica de "Si existe ID actualiza, si no crea"
        $eloquentModel = $this->model->updateOrCreate(
            ['id' => $health->getId()], // Criterio de búsqueda
            $data                       // Datos a guardar
        );

        // Si era una creación nueva, actualizamos el ID en la entidad de dominio
        if (!$health->getId()) {
            $health->setId($eloquentModel->id);
        }

        return $health;
    }

    /**
     * Este método recibe un array crudo ($data) y salta las validaciones de la Entidad.
     * En una implementación estricta DDD, deberías recuperar la entidad, 
     * modificarla y usar save(). Lo implemento porque está en tu interfaz.
     */
    public function update(int $employeeId, array $data): bool
    {
        // Eloquent retorna el número de filas afectadas
        return $this->model->where('employee_id', $employeeId)->update($data) > 0;
    }

    public function delete(int $id): void
    {
        $this->model->destroy($id);
    }

    /**
     * MAPPER: Eloquent Model -> Domain Entity
     * Convierte los datos crudos de la BD en un objeto de negocio válido.
     */
    private function toDomainEntity(HealthModel $model): Health
    {
        return new Health(
            id: $model->id,
            employeeId: $model->employee_id,
            // Usamos ?? '' para proteger strings obligatorios de valores null en BD
            medicalCondition: $model->medical_condition ?? '',
            allergies: $model->allergies ?? '',
            operations: $model->operations ?? '',
            accidents: $model->accidents ?? '',
            vaccinations: $model->vaccinations ?? '',
            // Campos opcionales (nullable)
            bloodGroup: $model->blood_group,
            weight: $model->weight !== null ? (float) $model->weight : null,
            height: $model->height !== null ? (float) $model->height : null,
            shoeSize: $model->shoe_size !== null ? (float) $model->shoe_size : null,
            shirtSize: $model->shirt_size,
            pantsSize: $model->pants_size !== null ? (float) $model->pants_size : null,
        );
    }
}
