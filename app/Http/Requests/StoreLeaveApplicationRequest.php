<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveApplicationRequest extends FormRequest
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
            'user_id'       => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'manager_id'    => 'required|integer',
            'level_approve' => 'nullable|integer',
            'file_upload'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'user_id.required'       => 'Karyawan wajib dipilih.',
            'leave_type_id.required' => 'Jenis cuti wajib dipilih.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'manager_id.required'    => 'Atasan Belum dipilih Silahkan Hubungi SDM.',
            'file_upload.mimes'      => 'File harus berformat PDF, JPG, JPEG, atau PNG.',
            'file_upload.max'        => 'Ukuran file maksimal 2MB.',
        ];
    }
}
