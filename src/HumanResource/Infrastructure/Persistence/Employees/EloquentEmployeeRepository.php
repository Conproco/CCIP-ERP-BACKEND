<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Entities\Employees\Employee as EmployeeEntity;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use App\Models\Employee as EmployeeModel;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Src\Shared\Domain\ValueObjects\Dni;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Telefono;

class EloquentEmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(
        private EmployeeModel $model
    ) {
    }

    public function find(int $id): ?EmployeeEntity
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function findWithRelations(int $id): ?EmployeeModel
    {
        return $this->model->with([
            'contract.cost_line',
            'education',
            'address',
            'emergency',
            'family',
            'health'
        ])->find($id);
    }

    public function save(EmployeeEntity $employee): EmployeeEntity
    {
        $data = $employee->toArray();
        if ($employee->getId()) {
            $model = $this->model->findOrFail($employee->getId());
            $model->update($data);
        } else {
            $model = $this->model->create($data);
            $employee->setId($model->id);
        }
        return $employee;
    }

    public function update(EmployeeEntity $employee): EmployeeEntity
    {
        $model = $this->model->findOrFail($employee->getId());
        $model->update($employee->toArray());
        return $employee;
    }

    public function delete(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getActiveEmployees(): Collection
    {
        return $this->model->select(['id', 'name', 'lastname', 'dni', 'phone1', 'cropped_image'])
            ->with('contract.cost_line')
            ->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getEmployeesByState(string $state, bool $paginate = true, int $perPage = 15): mixed
    {
        $query = $this->model->select(['id', 'name', 'lastname', 'dni', 'phone1', 'cropped_image'])
            ->with('contract.cost_line')
            ->whereHas('contract', function ($query) use ($state) {
                $query->where('state', $state);
            });
        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    public function search(?string $state, ?string $searchTerm, ?array $costLines, bool $paginate = true, int $perPage = 15): mixed
    {
        $query = $this->model->select(['id', 'name', 'lastname', 'dni', 'phone1', 'cropped_image'])
            ->with('contract.cost_line')
            ->whereHas('contract', function ($q) use ($state) {
                if ($state) {
                    $q->where('state', $state);
                }
            });

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone1', 'like', '%' . $searchTerm . '%')
                    ->orWhere('dni', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($costLines && count($costLines) > 0) {
            $query->whereHas('contract.cost_line', function ($q) use ($costLines) {
                $q->whereIn('name', $costLines);
            });
        }

        $query->orderBy('lastname', 'asc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    public function getBirthdaysInRange(\DateTime $startDate, \DateTime $endDate): Collection
    {
        $dates = [];
        $start = Carbon::instance($startDate);
        $end = Carbon::instance($endDate);
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $dates[] = $date->format('m-d');
        }
        $employees = $this->model->select(['id', 'name', 'lastname', 'birthdate'])
            ->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
            ->get();
        return $employees->filter(function ($employee) use ($dates) {
            return in_array(Carbon::parse($employee->birthdate)->format('m-d'), $dates);
        });
    }

    //Metodo para constantes de payroll de empleados activos
    public function getActiveEmployeesConstant(): Collection
    {
        return $this->model
            ->select(['id', 'name'])
            ->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get active employees for a specific payroll month
     */
    public function getActiveEmployeesForMonth(string $month): Collection
    {
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        return $this->model
            ->select('id')
            ->with('contract')
            ->whereHas('contract', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereDate('hire_date', '<=', $endOfMonth)
                    ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('state', 'Active')
                            ->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                                $subQuery->where('state', 'Inactive')
                                    ->whereBetween('fired_date', [$startOfMonth, $endOfMonth]);
                            });
                    });
            })
            ->get();
    }

    private function toDomainEntity(EmployeeModel $model): EmployeeEntity
    {
        return new EmployeeEntity(
            id: $model->id,
            name: $model->name,
            lastname: $model->lastname,
            gender: $model->gender,
            stateCivil: $model->state_civil,
            birthdate: $model->birthdate,
            dni: new Dni($model->dni),
            email: new Email($model->email),
            emailCompany: $model->email_company ? new Email($model->email_company) : null,
            phone1: new Telefono($model->phone1),
            croppedImage: $model->cropped_image,
            lPolicy: $model->l_policy,
            sctrExpDate: $model->sctr_exp_date,
            policyExpDate: $model->policy_exp_date,
            userId: $model->user_id,
        );
    }
}
