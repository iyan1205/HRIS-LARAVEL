<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class OvertimeSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'nullable|string'
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $start = $this->start_date;
            $end   = $this->end_date;

            if ($start && $end) {
                $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end));

                if ($diff > 90) {
                    $validator->errors()->add(
                        'date_range',
                        'Rentang tanggal tidak boleh lebih dari 90 hari.'
                    );
                }
            }
        });
    }
}
