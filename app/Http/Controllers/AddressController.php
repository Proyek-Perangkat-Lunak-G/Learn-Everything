<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('profile.addresses', compact('addresses'));
    }

    public function create()
    {
        return view('profile.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        $request->user()->addresses()->create($validated);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function edit(Address $address)
    {
        $this->authorize('view', $address);

        return view('profile.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        } else {
            $validated['is_default'] = false;
        }

        $address->update($validated);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy(Request $request, Address $address)
    {
        $this->authorize('delete', $address);

        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus!');
    }
}