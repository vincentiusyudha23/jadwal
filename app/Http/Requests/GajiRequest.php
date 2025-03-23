<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GajiRequest extends FormRequest
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
            'idKaryawan' => 'required|exists:users,id_karyawan',
            'ijin' => 'nullable|numeric',
            'cuti' => 'nullable|numeric',
            'sakit' => 'nullable|numeric',
            'alpa' => 'nullable|numeric',
            'total_hari_kerja' => 'required|numeric',
            'total_absen' => 'required|numeric',
            'tj_komunikasi' => 'nullable|numeric',
            'tj_keahlian' => 'nullable|numeric',
            'tj_kesehatan' => 'nullable|numeric',
            'total_upah_tetap' => 'required|numeric',
            'tj_makan' => 'nullable|numeric',
            'tj_transport' => 'nullable|numeric',
            'lembur' => 'nullable|numeric',
            'pp' => 'nullable|numeric',
            'pll' => 'nullable|numeric',
            'lbpph21' => 'nullable|numeric',
            'total_upah_non_tetap' => 'required|numeric',
            'pt_pph21' => 'nullable|numeric',
            'pt_pp' => 'nullable|numeric',
            'pt_bpjs_kesehatan' => 'nullable|numeric',
            'pt_bpjs_kerja' => 'nullable|numeric',
            'pt_absensi' => 'nullable|numeric',
            'pt_ll' => 'nullable|numeric',
            'total_potongan' => 'required|numeric',
            'total_diterima' => 'required|numeric'
        ];
    }
}
