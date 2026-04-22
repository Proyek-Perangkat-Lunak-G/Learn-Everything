<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        return view('tutor.profile', compact('tutor'));
    }

    public function update(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
        ]);

        $tutor->update($request->only([
            'bio', 'hourly_rate', 'bank_name', 'bank_account_holder', 'bank_account_number'
        ]));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
