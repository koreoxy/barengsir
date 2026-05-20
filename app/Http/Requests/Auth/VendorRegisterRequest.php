<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class VendorRegisterRequest extends FormRequest
{
    /**
     * Tentukan apakah user diperbolehkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        return [
            // Data User (Owner)
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::defaults()],
            
            // Data Bisnis / Vendor
            'business_name'         => ['required', 'string', 'max:255'],
            'business_phone'        => ['nullable', 'string', 'max:20'],
            'business_address'      => ['nullable', 'string', 'max:500'],
            
            // Data Cabang Utama
            'branch_name'           => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Custom error messages untuk lokalisasi bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'Nama lengkap pemilik wajib diisi.',
            'email.required'          => 'Alamat email wajib diisi.',
            'email.unique'            => 'Alamat email ini sudah terdaftar di sistem.',
            'password.confirmed'      => 'Konfirmasi password tidak cocok.',
            'business_name.required'  => 'Nama bisnis atau toko wajib diisi.',
            'branch_name.required'    => 'Nama cabang utama wajib diisi.',
        ];
    }
}
