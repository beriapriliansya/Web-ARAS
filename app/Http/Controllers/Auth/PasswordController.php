<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        \App\Models\UserNotification::ensureTableExists();
        \App\Models\UserNotification::create([
            'user_id' => $user->id,
            'type' => 'security_password',
            'title' => 'Kata Sandi Diubah',
            'message' => 'Kata sandi akun Anda berhasil diperbarui demi menjaga keamanan akun Anda.',
        ]);

        return back()->with('status', 'password-updated');
    }
}
