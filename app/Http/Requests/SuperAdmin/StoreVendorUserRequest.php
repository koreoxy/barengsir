<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StoreVendorUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vendor = $this->route('vendor');
        $vendorId = $vendor instanceof \App\Models\Vendor ? $vendor->id : $vendor;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
            'role' => ['required', 'string', Rule::in(['owner', 'admin', 'cashier'])],
            'branch_id' => [
                'nullable',
                Rule::requiredIf(fn() => $this->input('role') === 'cashier'),
                Rule::exists('branches', 'id')->where(function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                }),
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Get the custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Alamat email ini sudah terdaftar di sistem.',
            'branch_id.required_if' => 'Pengguna dengan peran Kasir wajib ditugaskan ke salah satu Cabang.',
            'branch_id.exists' => 'Cabang yang dipilih tidak valid atau bukan milik vendor ini.',
        ];
    }
}
