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
            'tempat_berangkat' => 'required',
            'maps_berangkat' => 'required',
            'tempat_tujuan' => 'required',
            'lama_perjalanan' => 'required',
            'date_time_berangkat' => 'required',
            'date_time_kembali' => 'required',
            'nama_pengikut' => 'nullable',
            'jabatan_pengikut' => 'nullable',
            'budget_id' => 'required',
            'e_toll' => 'nullable',
            'makan' => 'nullable',
            'lain_lain_desc' => 'nullable',
            'lain_lain' => 'nullable',
            'date_time_arrive' => 'nullable',
            'arrive_at'  => 'nullable',
            'foto_arrive'  => 'nullable',
            'continue' => 'nullable',
            'date_time_destination' => 'nullable',
            'foto_destination' => 'nullable',
            'nama_diperintah'  => 'nullable',
            'date_time'  => 'nullable',
            'verify'  => 'nullable',
            'note' => 'nullable'
        ];
    }
}
