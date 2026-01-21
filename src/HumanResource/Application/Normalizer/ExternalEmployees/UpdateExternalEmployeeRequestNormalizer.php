<?php

namespace Src\HumanResource\Application\Normalizer\ExternalEmployees;

use App\Http\Requests\HumanResource\ExternalEmployer\UpdateExternalEmployeeRequest;
use Src\HumanResource\Application\Dto\ExternalEmployees\UpdateExternalEmployeeDto;

class UpdateExternalEmployeeRequestNormalizer
{
    public function normalize(UpdateExternalEmployeeRequest $request, int $id): UpdateExternalEmployeeDto
    {
        return new UpdateExternalEmployeeDto(
            id: $id,
            name: $request->input('name'),
            lastname: $request->input('lastname'),
            costLineId: (int) $request->input('cost_line_id'),
            gender: $request->input('gender'),
            address: $request->input('address'),
            birthdate: $request->input('birthdate'),
            dni: $request->input('dni'),
            email: $request->input('email'),
            emailCompany: $request->input('email_company'),
            phone1: $request->input('phone1'),
            salary: $request->input('salary') ? (float) $request->input('salary') : null,
            sctr: $request->input('sctr') ? (int) $request->input('sctr') : null,
            lPolicy: $request->input('l_policy'),
            sctrExpDate: $request->input('sctr_exp_date'),
            policyExpDate: $request->input('policy_exp_date'),
            croppedImage: $request->hasFile('cropped_image') ? $request->file('cropped_image') : null,
            curriculumVitae: $request->hasFile('curriculum_vitae') ? $request->file('curriculum_vitae') : null,
        );
    }
}
