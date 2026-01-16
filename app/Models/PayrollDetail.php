<?php

namespace App\Models;

use App\Constants\PintConstants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'state',
        'pension_id',
        'basic_salary',
        'amount_travel_expenses',
        'life_ley',
        'discount',
        'discount_remuneration',
        'discount_sctr',
        'hire_date',
        'fired_date',
        'days',
        'days_taken',
    ];

    protected $appends = [
        'employee_name',
    ];

    public function getEmployeeNameAttribute()
    {
        return $this->employee->name . ' ' . $this->employee->lastname;
    }

    public function getEmployeesSctrAttribute()
    {
        return $this->where('discount_sctr', 1)->count();
    }

    public function getSctrPAttribute()
    {
        if ($this->discount_sctr) {
            $data = $this->payroll;
            return ($data->sctr_p / 3) / $this->employees_sctr;
        } else {
            return 0;
        }
    }

    public function getSctrSAttribute()
    {
        if ($this->discount_sctr) {
            $data = $this->payroll;
            return ($data->sctr_s / 3) / $this->employees_sctr;
        } else {
            return 0;
        }
    }

    public function getTotalContributionAttribute()
    {
        return $this->healths + $this->life_ley + $this->sctr_p + $this->sctr_s;
    }

    // Relación con Payroll
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    // Relación con Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relación con Pension
    public function pension()
    {
        return $this->belongsTo(Pension::class);
    }

    public function payroll_detail_expense(): HasMany
    {
        return $this->hasMany(PayrollDetailExpense::class);
    }


    //////////////new

    // public function getModDaysAttribute()
    // {
    //     return $this->payroll->days;
    // }

    public function payroll_detail_work_schedule()
    {
        return $this->hasOne(PayrollDetailWorkSchedule::class);
    }
    public function payroll_detail_monetary_income()
    {
        return $this->hasMany(PayrollDetailMonetaryIncome::class);
    }
    public function payroll_detail_monetary_discounts()
    {
        return $this->hasMany(PayrollDetailMonetaryDiscount::class);
    }
    public function payroll_detail_tax_and_contributions()
    {
        return $this->hasMany(PayrollDetailTaxAndContribution::class);
    }

    public function getNetPayAttribute()
    {
        $income_paid_total = $this->payroll_detail_monetary_income()->sum('paid_amount');
        $discount_total = $this->payroll_detail_monetary_discounts()->sum('amount');
        $employee_tac_total = $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function ($query) {
                $query->where('type', 'employee');
            })
            ->sum('amount');
        return $income_paid_total - ($discount_total + $employee_tac_total);
    }

    public function getNewTotalsAttribute()
    {
        // $income_accrued_total = $this->payroll_detail_monetary_income()->sum('accrued_amount');
        $income_paid_total = $this->payroll_detail_monetary_income()->sum('paid_amount');
        $discount_total = $this->payroll_detail_monetary_discounts()->sum('amount');
        $employee_tac_total = $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function ($query) {
                $query->where('type', 'employee');
            })
            ->sum('amount');
        $employer_tac_total = $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function ($query) {
                $query->where('type', 'employer');
            })
            ->sum('amount');
        //Se agrego provisionalmente
        $essalud = $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function ($query) {
                $query->where('code', '0804');
            })
            ->sum('amount');
        $net_pay = $income_paid_total - ($discount_total + $employee_tac_total);
        return compact(
            // 'income_accrued_total',
            'income_paid_total',
            'discount_total',
            'employee_tac_total',
            'net_pay',
            'employer_tac_total',
            'essalud'
        );
    }

    public function getVerifiedAttribute()
    {
        $payment_details = $this->payroll_detail_expense()
            ->where('expense_type', '!=', 'Gasto financiero')
            ->sum('amount');
        $net_pay = $this->net_pay;

        if (empty($payment_details) || round($payment_details, 2) <= 0) {
            return ['state' => PintConstants::PENDIENTE, 'amount' => $net_pay];
        }

        if (round($net_pay, 2) == round($payment_details, 2)) {
            return ['state' => PintConstants::COMPLETADO, 'amount' => round($net_pay - $payment_details, 2)];
        }

        if (round($net_pay, 2) < round($payment_details, 2)) {
            return ['state' => PintConstants::EXCEDIDO, 'amount' => round($net_pay - $payment_details, 2)];
        }

        return ['state' => PintConstants::PROCESO, 'amount' => round($net_pay - $payment_details, 2)];
    }


    public function getMonetaryIncomesByIdsAttribute()
    {
        return $this->payroll_detail_monetary_income()->get()->keyBy('income_param_id')->toArray();
    }
    public function getMonetaryDiscountsByIdsAttribute()
    {
        return $this->payroll_detail_monetary_discounts()->get()->keyBy('discount_param_id')->toArray();
    }
    public function getTaxContributionEmployeeByIdsAttribute()
    {
        return $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function ($query) {
                $query->where('type', 'employee');
            })
            ->get()->keyBy('t_a_c_param_id')->toArray();
    }
    public function getTaxContributionEmployerByIdsAttribute()
    {
        return $this->payroll_detail_tax_and_contributions()
            ->whereHas('tax_and_contribution_param', function ($query) {
                $query->where('type', 'employer');
            })
            ->get()->keyBy('t_a_c_param_id')->toArray();
    }

    //campos necesarios para la boleta.
    public function getApAmountAttribute()
    {
        return $this->payroll_detail_tax_and_contributions
            ->firstWhere('t_a_c_param_id', 5)?->amount;
    }

    public function getPsAmountAttribute()
    {
        return $this->payroll_detail_tax_and_contributions
            ->firstWhere('t_a_c_param_id', 4)?->amount;
    }

    public function getRqAmountAttribute()
    {
        return $this->payroll_detail_tax_and_contributions
            ->firstWhere('t_a_c_param_id', 3)?->amount;
    }

    public function getCpAmountAttribute()
    {
        return $this->payroll_detail_tax_and_contributions
            ->firstWhere('t_a_c_param_id', 1)?->amount;
    }

    public function getRnpAmountAttribute()
    {
        return $this->employee->contract->amount_travel_expenses ?? 0;
    }

    public function getCtsAttribute()
    {
        return $this->payroll_detail_monetary_income
            ->firstWhere('income_param_id', 15)?->paid_amount;
    }
}
