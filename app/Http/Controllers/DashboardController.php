<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Transaction;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Product;
use App\Models\HomeVisitBooking;
use App\Models\Subscription;
use App\Models\TutorPayment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
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
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_tutors' => Tutor::count(),
            'pending_tutors' => Tutor::where('status', 'pending')->count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'total_revenue' => Transaction::where('status', 'success')->sum('total_amount'),
            'total_products' => Product::count(),
            'total_subscriptions' => Subscription::where('status', 'active')->count(),
        ];

        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();
        $pendingTutors = Tutor::with('user')->where('status', 'pending')->latest()->get();
        $recentEnrollments = Enrollment::with(['user', 'course'])->latest()->take(10)->get();

        // Monthly revenue for chart
        $monthlyRevenue = Transaction::where('status', 'success')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'pendingTutors', 'recentEnrollments', 'monthlyRevenue'));
    }

    private function tutorDashboard(User $user)
    {
        $tutor = $user->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        // Count unique students from chat and bookings
        $chatStudentIds = \App\Models\Chat::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->filter(fn($id) => $id !== $user->id);
        
        $bookingStudentIds = HomeVisitBooking::where('tutor_id', $tutor->id)
            ->pluck('user_id')
            ->unique();
        
        $totalStudents = $chatStudentIds->merge($bookingStudentIds)->unique()->count();
        
        $totalRevenue = TutorPayment::where('tutor_id', $tutor->id)
            ->where('status', 'paid')
            ->sum('amount');

        $bookings = HomeVisitBooking::where('tutor_id', $tutor->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentReviews = $tutor->reviews()->with('user')->latest()->take(5)->get();

        return view('tutor.dashboard', compact('tutor', 'totalStudents', 'totalRevenue', 'bookings', 'recentReviews'));
    }

    private function userDashboard(User $user)
    {
        $enrollments = $user->enrollments()->with('course.modules')->latest()->get();

        $totalSpent = $user->transactions()->where('status', 'success')->sum('total_amount');
        $recentTransactions = $user->transactions()->latest()->take(5)->get();
        $subscription = $user->subscription;
        $bookings = $user->bookings()->with('tutor.user')->latest()->take(5)->get();

        // Marketplace sales (user as seller)
        $products = $user->products;
        $salesRevenue = 0;
        if ($products->isNotEmpty()) {
            $productNames = $products->pluck('name')->toArray();
            $salesRevenue = Transaction::where('status', 'success')
                ->get()
                ->sum(function ($t) use ($productNames) {
                    return collect($t->items)->where('type', 'Product')
                        ->whereIn('name', $productNames)
                        ->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
                });
        }

        return view('dashboard', compact('enrollments', 'totalSpent', 'recentTransactions', 'subscription', 'bookings', 'salesRevenue', 'products'));
    }
}
