<?php

namespace Src\HumanResource\Application\Normalizer;

use Src\HumanResource\Application\Dto\StoreEmployeeDto;
use App\Http\Requests\HumanResource\Employees\CreateManagementEmployees;

/**
 * Normaliza el request de creación de empleado a un DTO.
 */
class StoreEmployeeRequestNormalizer
{
    public function normalize(CreateManagementEmployees $request): StoreEmployeeDto
    {
        return new StoreEmployeeDto(
            // Datos personales
            name: $request->input('name'),
            lastname: $request->input('lastname'),
            gender: $request->input('gender'),
            stateCivil: $request->input('state_civil'),
            birthdate: $request->input('birthdate'),
            dni: $request->input('dni'),
            email: $request->input('email'),
            emailCompany: $request->input('email_company'),
            phone1: $request->input('phone1'),
            croppedImage: $request->file('cropped_image'),

            // Contrato
            costLineId: (int) $request->input('cost_line_id'),
            typeContract: $request->input('type_contract'),
            basicSalary: (float) $request->input('basic_salary'),
            hireDate: $request->input('hire_date'),
            pensionType: $request->input('pension_type'),
            cuspp: $request->input('cuspp'),
            stateTravelExpenses: (bool) $request->input('state_travel_expenses', false),
            amountTravelExpenses: $request->input('amount_travel_expenses') ? (float) $request->input('amount_travel_expenses') : null,
            nroCuenta: $request->input('nro_cuenta'),
            lifeLey: (bool) $request->input('life_ley', false),
            discountRemuneration: (bool) $request->input('discount_remuneration', false),
            discountSctr: (bool) $request->input('discount_sctr', false),

            // Educación
            educationLevel: $request->input('education_level'),
            educationStatus: $request->input('education_status'),
            specialization: $request->input('specialization'),
            curriculumVitae: $request->file('curriculum_vitae'),

            // Dirección
            streetAddress: $request->input('street_address'),
            department: $request->input('department'),
            province: $request->input('province'),
            district: $request->input('district'),

            // Contactos de emergencia
            emergencyContacts: $request->input('emergencyContacts', []),

            // Dependientes familiares
            familyDependents: $request->input('familyDependents', []),

            // Salud
            medicalCondition: $request->input('medical_condition'),
            allergies: $request->input('allergies'),
            operations: $request->input('operations'),
            accidents: $request->input('accidents'),
            vaccinations: $request->input('vaccinations'),
            bloodGroup: $request->input('blood_group'),
            weight: $request->input('weight') ? (float) $request->input('weight') : null,
            height: $request->input('height') ? (float) $request->input('height') : null,
            shoeSize: $request->input('shoe_size') ? (float) $request->input('shoe_size') : null,
            shirtSize: $request->input('shirt_size'),
            pantsSize: $request->input('pants_size') ? (float) $request->input('pants_size') : null,
        );
    }
}
