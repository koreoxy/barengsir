<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function updateStore(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'required|string|max:255',
            'store_phone' => 'required|string|max:20',
        ]);

        Setting::updateOrCreate(['key' => 'store_name'], ['value' => $request->store_name]);
        Setting::updateOrCreate(['key' => 'store_address'], ['value' => $request->store_address]);
        Setting::updateOrCreate(['key' => 'store_phone'], ['value' => $request->store_phone]);

        return redirect()->back()->with('success', 'Pengaturan toko berhasil diperbarui.');
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => ['nullable', Password::defaults(), 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}
