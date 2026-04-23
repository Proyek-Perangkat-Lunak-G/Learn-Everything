<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Transaction;
use App\Models\TutorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load(['tutor', 'subscription']);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    // ── Addresses ──

    public function addresses(Request $request)
    {
        return response()->json($request->user()->addresses);
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'sometimes|boolean',
        ]);

        $user = $request->user();

        if (!empty($validated['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create($validated);

        return response()->json($address, 201);
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'label' => 'sometimes|string|max:50',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string|max:100',
            'province' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:10',
            'is_default' => 'sometimes|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return response()->json($address);
    }

    public function destroyAddress(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $address->delete();

        return response()->json(['message' => 'Alamat berhasil dihapus']);
    }

    // ── Transactions ──

    public function transactions(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($transactions);
    }

    // ── Certificates ──

    public function certificates(Request $request)
    {
        $certificates = Certificate::with('enrollment.course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($certificates);
    }

    // ── Dashboard Stats ──

    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isTutor()) {
            return $this->tutorDashboard($user);
        }

        return $this->userDashboard($user);
    }

    private function adminDashboard()
    {
        return response()->json([
            'total_users' => \App\Models\User::count(),
            'total_courses' => \App\Models\Course::count(),
            'total_tutors' => \App\Models\Tutor::where('status', 'approved')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('total_amount'),
        ]);
    }

    private function tutorDashboard($user)
    {
        $tutor = $user->tutor;
        $totalEarnings = TutorPayment::where('tutor_id', $tutor->id)
            ->where('status', 'paid')
            ->sum('amount');

        return response()->json([
            'total_courses' => $tutor->courses()->count(),
            'total_students' => Enrollment::whereIn('course_id', $tutor->courses()->pluck('id'))->count(),
            'total_earnings' => $totalEarnings,
            'rating' => $tutor->rating,
        ]);
    }

    private function userDashboard($user)
    {
        $enrollments = Enrollment::where('user_id', $user->id);
        $salesRevenue = Transaction::where('status', 'completed')
            ->where('user_id', '!=', $user->id)
            ->get()
            ->filter(function ($t) use ($user) {
                return collect($t->items)->contains(fn($item) =>
                    ($item['seller_id'] ?? null) == $user->id
                );
            })
            ->sum('total_amount');

        return response()->json([
            'total_enrolled' => $enrollments->count(),
            'completed_courses' => (clone $enrollments)->where('is_completed', true)->count(),
            'total_products' => $user->products()->count(),
            'sales_revenue' => $salesRevenue,
        ]);
    }
}
