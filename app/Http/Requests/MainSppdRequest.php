<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainSppdRequest extends FormRequest
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
            'auth_official' => 'required',
            'user_id' => 'required',
            'eslon_id' => 'required',
            'maksud_perjalanan' => 'required',
            'alat_angkutan' => 'required',
            'nama_kendaraan_lain' => 'nullable',
            'tempat_berangkat' => 'required',
            'maps_berangkat' => 'required',
            'tempat_tujuan' => 'required',
            'lama_perjalanan' => 'required',
            'date_time_berangkat' => 'required',
            'date_time_kembali' => 'required',
            'nama_pengikut' => 'nullable',
            'jabatan_pengikut' => 'nullable',
            'uang_saku' => 'required',
            'e_toll' => 'nullable',
            'makan' => 'nullable',

            'lain_lain' => 'nullable|array',
            'lain_lain.*' => 'nullable|string', // Validate each input inside the array
            'lain_lain_desc' => 'nullable|array',
            'lain_lain_desc.*' => 'nullable|string',

            'date_time_arrive' => 'nullable',
            'arrive_at'  => 'nullable',
            'foto_arrive'  => 'nullable',
            'continue' => 'nullable',
            'date_time_destination' => 'nullable',
            'foto_destination' => 'nullable',
            'nama_diperintah'  => 'nullable',
            'date_time'  => 'nullable',
            'verify'  => 'nullable',
            'note' => 'nullable',
            'maps_tiba' => 'nullable',
            'maps_tujuan' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'auth_official.required' => 'Field Yang Memberi Perintah wajib diisi.',
            'user_id.required' => 'Field Diperintah wajib diisi.',
            'eslon_id.required' => 'Field Eselon wajib diisi.',
            'maksud_perjalanan.required' => 'Field Maksud Perjalanan wajib diisi.',
            'alat_angkutan.required' => 'Field Alat Angkutan wajib diisi.',
            'tempat_berangkat.required' => 'Field Tempat Berangkat wajib diisi.',
            'maps_berangkat.required' => 'Field Maps Berangkat wajib diisi.',
            'tempat_tujuan.required' => 'Field Tempat Tujuan wajib diisi.',
            'lama_perjalanan.required' => 'Field Lama Perjalanan wajib diisi.',
            'date_time_berangkat.required' => 'Field Date Time Berangkat wajib diisi.',
            'date_time_kembali.required' => 'Field Date Time Kembali wajib diisi.',
            'uang_saku.required' => 'Field Uang Saku wajib diisi.'
        ];
    }
}
