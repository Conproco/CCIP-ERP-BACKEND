<?php

// Bindings for Repositories

use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentEmployeeRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentContractRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentCostLineRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\AddressRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentAddressRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\EducationRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentEducationRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\EmergencyContactRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentEmergencyContactRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\FamilyDependentRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentFamilyDependentRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\HealthRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentHealthRepository;

use Src\HumanResource\Domain\Ports\Repositories\Employees\DocumentSectionRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentDocumentSectionRepository;

// External Employees Repositories
use Src\HumanResource\Domain\Ports\Repositories\ExternalEmployees\ExternalEmployeeRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\ExternalEmployees\EloquentExternalEmployeeRepository;

// GrupalDocuments Repositories
use Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments\GrupalDocumentRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\GrupalDocuments\EloquentGrupalDocumentRepository;

// PayrollDetail Repository
use Src\HumanResource\Domain\Ports\Repositories\Employees\PayrollDetailRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Employees\EloquentPayrollDetailRepository;

// Payroll Repository
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Payroll\EloquentPayrollRepository;

// PayrollDeduction Repository
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Payroll\EloquentPayrollDeductionRepository;

// PayrollDeductionInstallment Repository
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionInstallmentRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Payroll\EloquentPayrollDeductionInstallmentRepository;

// PayrollDetailExpense Repository
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailExpenseRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Payroll\EloquentPayrollDetailExpenseRepository;

// PayrollDetailMonetaryIncome Repository
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailMonetaryIncomeRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Payroll\EloquentPayrollDetailMonetaryIncomeRepository;

// PayrollDetailTaxAndContribution Repository
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailTaxAndContributionRepositoryInterface;
use Src\HumanResource\Infrastructure\Persistence\Payroll\EloquentPayrollDetailTaxAndContributionRepository;

return [
        // Employees
    EmployeeRepositoryInterface::class => EloquentEmployeeRepository::class,
    ContractRepositoryInterface::class => EloquentContractRepository::class,
    CostLineRepositoryInterface::class => EloquentCostLineRepository::class,
    AddressRepositoryInterface::class => EloquentAddressRepository::class,
    EducationRepositoryInterface::class => EloquentEducationRepository::class,
    EmergencyContactRepositoryInterface::class => EloquentEmergencyContactRepository::class,
    FamilyDependentRepositoryInterface::class => EloquentFamilyDependentRepository::class,
    HealthRepositoryInterface::class => EloquentHealthRepository::class,
    DocumentSectionRepositoryInterface::class => EloquentDocumentSectionRepository::class,
    PayrollDetailRepositoryInterface::class => EloquentPayrollDetailRepository::class,

        // External Employees
    ExternalEmployeeRepositoryInterface::class => EloquentExternalEmployeeRepository::class,

        // GrupalDocuments
    GrupalDocumentRepositoryInterface::class => EloquentGrupalDocumentRepository::class,

        // Payroll
    PayrollRepositoryInterface::class => EloquentPayrollRepository::class,
    PayrollDeductionRepositoryInterface::class => EloquentPayrollDeductionRepository::class,
    PayrollDeductionInstallmentRepositoryInterface::class => EloquentPayrollDeductionInstallmentRepository::class,
    PayrollDetailExpenseRepositoryInterface::class => EloquentPayrollDetailExpenseRepository::class,
    PayrollDetailMonetaryIncomeRepositoryInterface::class => EloquentPayrollDetailMonetaryIncomeRepository::class,
    PayrollDetailTaxAndContributionRepositoryInterface::class => EloquentPayrollDetailTaxAndContributionRepository::class,

];







