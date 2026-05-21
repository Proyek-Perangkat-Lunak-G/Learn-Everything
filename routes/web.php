<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TutorReviewController;
use App\Http\Controllers\ForumThreadController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    $courses = \App\Models\Course::with('modules')->where('is_published', true)->latest()->take(6)->get();
    $tutors = \App\Models\Tutor::with('user')->where('status', 'approved')->latest()->take(4)->get();
    $coursesCount = \App\Models\Course::where('is_published', true)->count();
    $tutorsCount = \App\Models\Tutor::where('status', 'approved')->count();
    return view('landing', compact('courses', 'tutors', 'coursesCount', 'tutorsCount'));
})->name('landing');

// Course Catalog (public)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Tutors (public - only approved tutors)
Route::get('/tutors', [TutorController::class, 'index'])->name('tutors.index');
Route::get('/tutors/{tutor}', [TutorController::class, 'show'])->name('tutors.show');

// Forum (public read)
Route::get('/forum', [ForumThreadController::class, 'index'])->name('forum.index');
Route::get('/forum/{thread}', [ForumThreadController::class, 'show'])->name('forum.show');

// Marketplace (public)
Route::get('/marketplace', [ProductController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{product}', [ProductController::class, 'show'])->name('marketplace.show')->where('product', '[0-9]+');

// Auth required routes
Route::middleware('auth')->group(function () {
    // Dashboard (role-based)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Enrollment
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');

    // Learning
    Route::get('/courses/{course}/learn/{module}', [ModuleController::class, 'learn'])->name('courses.learn');
    Route::post('/courses/{course}/learn/{module}/complete', [ModuleController::class, 'complete'])->name('courses.module.complete');

    // Quiz
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    // Booking
    Route::get('/tutors/{tutor}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Tutor Review
    Route::post('/tutors/{tutor}/reviews', [TutorReviewController::class, 'store'])->name('tutors.reviews.store');

    // Forum (write)
    Route::get('/forum-create', [ForumThreadController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumThreadController::class, 'store'])->name('forum.store');
    Route::post('/forum/{thread}/replies', [ForumReplyController::class, 'store'])->name('forum.replies.store');
    Route::post('/forum/replies/{reply}/like', [ForumReplyController::class, 'toggleLike'])->name('forum.replies.like');

    // Chat (with tutor verification check)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{receiver}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    // Marketplace (manage — only users can sell, not tutors)
    Route::middleware('role:user')->group(function () {
        Route::get('/marketplace/create', [ProductController::class, 'create'])->name('marketplace.create');
        Route::post('/marketplace', [ProductController::class, 'store'])->name('marketplace.store');
        Route::get('/marketplace/{product}/edit', [ProductController::class, 'edit'])->name('marketplace.edit');
        Route::put('/marketplace/{product}', [ProductController::class, 'update'])->name('marketplace.update');
        Route::delete('/marketplace/{product}', [ProductController::class, 'destroy'])->name('marketplace.destroy');
        Route::post('/marketplace/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('marketplace.toggle');
    });

    // Cart & Checkout (users only — tutors don't buy/sell products)
    Route::middleware('role:user')->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add-product', [CartController::class, 'addProduct'])->name('cart.addProduct');
        Route::post('/cart/add-course', [CartController::class, 'addCourse'])->name('cart.addCourse');
        Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    });

    // Subscription
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('/subscriptions/payment/{package}', [SubscriptionController::class, 'payment'])->name('subscriptions.payment');
    Route::post('/subscriptions/process', [SubscriptionController::class, 'process'])->name('subscriptions.process');

    // Transaction Payment Proof
    Route::get('/transactions/{transaction}/upload-proof', [CheckoutController::class, 'uploadProofForm'])->name('transactions.upload-proof');
    Route::post('/transactions/{transaction}/upload-proof', [CheckoutController::class, 'uploadProof'])->name('transactions.upload-proof.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // History & Certificates
    Route::get('/history', [CertificateController::class, 'history'])->name('history');
    Route::get('/certificates/{enrollment}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // My Orders
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

    // My Courses (Detail Page)
    Route::get('/my-courses', [\App\Http\Controllers\MyCoursesController::class, 'index'])->name('my-courses.index');

    // My Expenses (Detail Page)
    Route::get('/my-expenses', [\App\Http\Controllers\MyExpensesController::class, 'index'])->name('my-expenses.index');

    // Seller Wallet & Withdrawals
    Route::get('/seller/wallet', [\App\Http\Controllers\SellerWalletController::class, 'index'])->name('seller.wallet');
    Route::post('/seller/wallet/withdraw', [\App\Http\Controllers\SellerWalletController::class, 'requestWithdrawal'])->name('seller.wallet.withdraw');

    // My Products (Etalase)
    Route::get('/my-products', [\App\Http\Controllers\MyProductsController::class, 'index'])->name('my-products.index');
});

// ── Admin Routes ────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Courses
    Route::get('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [\App\Http\Controllers\Admin\CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [\App\Http\Controllers\Admin\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{course}/modules', [\App\Http\Controllers\Admin\CourseController::class, 'modules'])->name('courses.modules');
    Route::post('/courses/{course}/modules', [\App\Http\Controllers\Admin\CourseController::class, 'storeModule'])->name('courses.modules.store');
    Route::put('/modules/{module}', [\App\Http\Controllers\Admin\CourseController::class, 'updateModule'])->name('modules.update');
    Route::delete('/modules/{module}', [\App\Http\Controllers\Admin\CourseController::class, 'destroyModule'])->name('modules.destroy');
    Route::post('/modules/{module}/regenerate-quiz', [\App\Http\Controllers\Admin\CourseController::class, 'regenerateQuiz'])->name('modules.regenerateQuiz');

    // Tutors
    Route::get('/tutors', [\App\Http\Controllers\Admin\TutorController::class, 'index'])->name('tutors.index');
    Route::post('/tutors/{tutor}/verify', [\App\Http\Controllers\Admin\TutorController::class, 'verify'])->name('tutors.verify');
    Route::get('/tutors/{tutor}', [\App\Http\Controllers\Admin\TutorController::class, 'show'])->name('tutors.show');

    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/{transaction}/approve', [\App\Http\Controllers\Admin\TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [\App\Http\Controllers\Admin\TransactionController::class, 'reject'])->name('transactions.reject');

    // Tutor Payments (admin pays tutors)
    Route::get('/tutor-payments', [\App\Http\Controllers\Admin\TutorPaymentController::class, 'index'])->name('tutor-payments.index');
    Route::post('/tutor-payments/{tutor}/pay', [\App\Http\Controllers\Admin\TutorPaymentController::class, 'payTutor'])->name('tutor-payments.pay');

    // Withdrawals
    Route::get('/withdrawals', [\App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [\App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/complete', [\App\Http\Controllers\Admin\WithdrawalController::class, 'complete'])->name('withdrawals.complete');
    Route::post('/withdrawals/{withdrawal}/reject', [\App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');
});

// ── Tutor Routes ────────────────────────────────────────
Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    // Profile (accessible even if not verified)
    Route::get('/profile', [\App\Http\Controllers\Tutor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Tutor\ProfileController::class, 'update'])->name('profile.update');

    // Middleware to restrict unverified tutors
    Route::middleware(\App\Http\Middleware\VerifiedTutorMiddleware::class)->group(function () {
        Route::get('/payments', [\App\Http\Controllers\Tutor\CourseController::class, 'payments'])->name('payments');
        
        // Availability Management
        Route::get('/availability', [\App\Http\Controllers\Tutor\AvailabilityController::class, 'index'])->name('availability.index');
        Route::post('/availability', [\App\Http\Controllers\Tutor\AvailabilityController::class, 'store'])->name('availability.store');
        Route::delete('/availability/{availability}', [\App\Http\Controllers\Tutor\AvailabilityController::class, 'destroy'])->name('availability.destroy');
        Route::post('/availability/{availability}/toggle', [\App\Http\Controllers\Tutor\AvailabilityController::class, 'toggle'])->name('availability.toggle');
        Route::post('/bookings/{booking}/complete', [\App\Http\Controllers\Tutor\AvailabilityController::class, 'completeBooking'])->name('bookings.complete');

        // Withdrawals
        Route::get('/withdrawals', [\App\Http\Controllers\Tutor\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/create', [\App\Http\Controllers\Tutor\WithdrawalController::class, 'create'])->name('withdrawals.create');
        Route::post('/withdrawals', [\App\Http\Controllers\Tutor\WithdrawalController::class, 'store'])->name('withdrawals.store');
    });
});

require __DIR__.'/auth.php';
