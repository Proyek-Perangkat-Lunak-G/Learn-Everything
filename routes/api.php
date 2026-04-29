<?php

use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\ForumApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\QuizApiController;
use App\Http\Controllers\Api\SubscriptionApiController;
use App\Http\Controllers\Api\TutorApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
});

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        'role' => 'user',
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user], 201);
});

// Public Courses
Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/courses/{course}', [CourseApiController::class, 'show']);

// Public Tutors
Route::get('/tutors', [TutorApiController::class, 'index']);
Route::get('/tutors/{tutor}', [TutorApiController::class, 'show']);

// Public Forum
Route::get('/forum', [ForumApiController::class, 'index']);
Route::get('/forum/{thread}', [ForumApiController::class, 'show']);

// Public Marketplace
Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{product}', [ProductApiController::class, 'show']);

// Subscription Packages
Route::get('/subscriptions/packages', [SubscriptionApiController::class, 'packages']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Token Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    // Profile & Account
    Route::get('/user', [ProfileApiController::class, 'show']);
    Route::put('/user', [ProfileApiController::class, 'update']);
    Route::put('/user/password', [ProfileApiController::class, 'updatePassword']);
    Route::get('/dashboard', [ProfileApiController::class, 'dashboard']);

    // Addresses
    Route::get('/addresses', [ProfileApiController::class, 'addresses']);
    Route::post('/addresses', [ProfileApiController::class, 'storeAddress']);
    Route::put('/addresses/{address}', [ProfileApiController::class, 'updateAddress']);
    Route::delete('/addresses/{address}', [ProfileApiController::class, 'destroyAddress']);

    // Transactions & Certificates
    Route::get('/transactions', [ProfileApiController::class, 'transactions']);
    Route::get('/certificates', [ProfileApiController::class, 'certificates']);

    // Courses & Learning
    Route::post('/courses/{course}/enroll', [CourseApiController::class, 'enroll']);
    Route::get('/courses/{course}/modules', [CourseApiController::class, 'modules']);
    Route::get('/modules/{module}/learn', [CourseApiController::class, 'learnModule']);
    Route::post('/modules/{module}/complete', [CourseApiController::class, 'completeModule']);
    Route::get('/my/enrollments', [CourseApiController::class, 'myEnrollments']);

    // Quiz
    Route::get('/quizzes/{quiz}', [QuizApiController::class, 'show']);
    Route::post('/quizzes/{quiz}/submit', [QuizApiController::class, 'submit']);

    // Tutors - Booking & Reviews
    Route::post('/tutors/{tutor}/reviews', [TutorApiController::class, 'storeReview']);
    Route::post('/tutors/{tutor}/book', [TutorApiController::class, 'book']);
    Route::get('/my/bookings', [TutorApiController::class, 'myBookings']);
    Route::put('/bookings/{booking}/status', [TutorApiController::class, 'updateBookingStatus']);

    // Forum (write)
    Route::post('/forum', [ForumApiController::class, 'store']);
    Route::post('/forum/{thread}/replies', [ForumApiController::class, 'reply']);
    Route::post('/forum/replies/{reply}/like', [ForumApiController::class, 'toggleLike']);

    // Chat
    Route::get('/chats/{user}', [ChatApiController::class, 'messages']);
    Route::post('/chats', [ChatApiController::class, 'send']);

    // Marketplace (manage own products)
    Route::get('/my/products', [ProductApiController::class, 'myProducts']);
    Route::post('/products', [ProductApiController::class, 'store']);
    Route::put('/products/{product}', [ProductApiController::class, 'update']);
    Route::delete('/products/{product}', [ProductApiController::class, 'destroy']);

    // Cart & Checkout
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart/product', [CartApiController::class, 'addProduct']);
    Route::post('/cart/course', [CartApiController::class, 'addCourse']);
    Route::delete('/cart/{cartItem}', [CartApiController::class, 'removeItem']);
    Route::post('/checkout', [CartApiController::class, 'checkout']);

    // Subscription
    Route::get('/subscriptions/current', [SubscriptionApiController::class, 'current']);
    Route::post('/subscriptions', [SubscriptionApiController::class, 'subscribe']);
});
