<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tutor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,tutor'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if ($request->role === 'tutor') {
            $rules['bio'] = ['required', 'string', 'max:1000'];
            $rules['skills'] = ['required', 'string', 'max:500'];
            $rules['hourly_rate'] = ['required', 'numeric', 'min:0'];
            $rules['document'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        if ($request->role === 'tutor') {
            $documentPath = $request->file('document')->store('tutor-documents', 'public');

            Tutor::create([
                'user_id' => $user->id,
                'bio' => $request->bio,
                'skills' => array_map('trim', explode(',', $request->skills)),
                'hourly_rate' => $request->hourly_rate,
                'document' => $documentPath,
                'status' => 'pending',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
