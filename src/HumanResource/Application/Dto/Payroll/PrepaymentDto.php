<?php

namespace Src\HumanResource\Application\Dto\Payroll;

use Illuminate\Http\UploadedFile;

class PrepaymentDto
{
    public function __construct(
        public readonly int $installmentId,
        public readonly UploadedFile $depositVoucher,
    ) {
    }
}
