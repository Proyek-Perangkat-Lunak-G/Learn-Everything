<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Tutor;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Enrollment;
use App\Models\ModuleProgress;
use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\Product;
use App\Models\Address;
use App\Models\TutorPayment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────
        $admin = User::create([
            'name'     => env('ADMIN_NAME', 'Administrator'),
            'email'    => env('ADMIN_EMAIL', 'admin@learn.test'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            'role'     => 'admin',
            'phone'    => '081200000001',
        ]);
        Admin::create(['user_id' => $admin->id]);

        // ── Tutors ────────────────────────────────────────
        $tutorUsers = [];
        $tutorsData = [
            ['name' => 'Budi Santoso',   'email' => 'budi@learn.test',   'bio' => 'Full-stack web developer with 10+ years experience.', 'skills' => ['Laravel', 'Vue.js', 'MySQL', 'Docker'],       'hourly_rate' => 150000],
            ['name' => 'Siti Rahayu',    'email' => 'siti@learn.test',   'bio' => 'Data scientist & AI researcher at top university.',    'skills' => ['Python', 'TensorFlow', 'Pandas', 'SQL'],      'hourly_rate' => 200000],
            ['name' => 'Agus Pratama',   'email' => 'agus@learn.test',   'bio' => 'Mobile developer specializing in Flutter and React Native.', 'skills' => ['Flutter', 'Dart', 'React Native', 'Firebase'], 'hourly_rate' => 175000],
        ];

        foreach ($tutorsData as $td) {
            $u = User::create([
                'name'     => $td['name'],
                'email'    => $td['email'],
                'password' => Hash::make('password'),
                'role'     => 'tutor',
                'phone'    => '0812' . rand(10000000, 99999999),
            ]);
            $tutor = Tutor::create([
                'user_id'       => $u->id,
                'bio'           => $td['bio'],
                'skills'        => $td['skills'],
                'hourly_rate'   => $td['hourly_rate'],
                'rating'        => round(rand(40, 50) / 10, 1),
                'total_reviews' => rand(5, 30),
                'status'        => 'approved',
            ]);
            $tutorUsers[] = ['user' => $u, 'tutor' => $tutor];
        }

        // ── Regular Users ─────────────────────────────────
        $students = [];
        $studentsData = [
            ['name' => 'Dewi Lestari',  'email' => 'dewi@learn.test'],
            ['name' => 'Rizky Hidayat', 'email' => 'rizky@learn.test'],
            ['name' => 'Ayu Wulandari', 'email' => 'ayu@learn.test'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar@learn.test'],
            ['name' => 'Putri Amelia',  'email' => 'putri@learn.test'],
        ];

        foreach ($studentsData as $sd) {
            $students[] = User::create([
                'name'     => $sd['name'],
                'email'    => $sd['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
                'phone'    => '0812' . rand(10000000, 99999999),
            ]);
        }

        // ── Courses ───────────────────────────────────────
        $coursesData = [
            [
                'tutor_index' => 0,
                'title'       => 'Laravel 12 Masterclass',
                'description' => 'Complete guide to building modern web apps with Laravel 12. From routing to deployment.',
                'category'    => 'Web Development',
                'price'       => 299000,
                'modules'     => [
                    ['title' => 'Introduction to Laravel', 'content' => "Welcome to the Laravel Masterclass!\n\nIn this module you'll learn the fundamentals of Laravel framework, including installation, directory structure, and the MVC pattern.\n\nLaravel is a PHP framework that provides an elegant syntax and powerful tools for building robust web applications."],
                    ['title' => 'Routing & Controllers', 'content' => "Routes define how your application responds to HTTP requests.\n\nIn Laravel, routes are defined in the routes/ directory. You can use closures or controller methods.\n\nControllers group related request handling logic into classes."],
                    ['title' => 'Eloquent ORM', 'content' => "Eloquent is Laravel's built-in ORM that makes database interactions elegant.\n\nEach database table has a corresponding Model. You can query, insert, update, and delete records using expressive PHP syntax.\n\nRelationships: hasMany, belongsTo, belongsToMany, morphMany, etc."],
                    ['title' => 'Authentication & Authorization', 'content' => "Laravel provides multiple authentication scaffolding options.\n\nBreeze provides minimal auth views. Sanctum handles API token authentication.\n\nAuthorization can be handled with Gates, Policies, and Middleware."],
                    ['title' => 'Deployment & Best Practices', 'content' => "Learn how to deploy your Laravel app to production.\n\nTopics: environment config, caching, queue workers, nginx configuration, SSL certificates, and monitoring.\n\nBest practices: use .env, never commit secrets, write tests."],
                ],
            ],
            [
                'tutor_index' => 1,
                'title'       => 'Data Science with Python',
                'description' => 'Learn data analysis, visualization, and machine learning using Python ecosystem.',
                'category'    => 'Data Science',
                'price'       => 349000,
                'modules'     => [
                    ['title' => 'Python for Data Science', 'content' => "Python is the most popular language for data science.\n\nWe'll cover NumPy for numerical computing, Pandas for data manipulation, and Matplotlib for visualization.\n\nSetup: Install Anaconda or use Google Colab."],
                    ['title' => 'Data Cleaning & Analysis', 'content' => "Real-world data is messy. Learn techniques to clean and prepare data.\n\nHandling missing values, duplicates, outliers, and data type conversions.\n\nExploratory Data Analysis (EDA) helps understand data distribution and patterns."],
                    ['title' => 'Machine Learning Basics', 'content' => "Introduction to supervised and unsupervised learning.\n\nAlgorithms: Linear Regression, Decision Trees, K-Means Clustering.\n\nUse scikit-learn library for model training and evaluation."],
                    ['title' => 'Deep Learning with TensorFlow', 'content' => "Neural networks learn complex patterns from data.\n\nBuild models with TensorFlow/Keras: Sequential API, layers, activation functions.\n\nTrain image classifiers and text processors."],
                ],
            ],
            [
                'tutor_index' => 2,
                'title'       => 'Flutter Mobile Development',
                'description' => 'Build beautiful cross-platform mobile apps with Flutter and Dart.',
                'category'    => 'Mobile Development',
                'price'       => 249000,
                'modules'     => [
                    ['title' => 'Dart Language Fundamentals', 'content' => "Dart is the programming language behind Flutter.\n\nLearn variables, functions, classes, async/await, and null safety.\n\nDart compiles to native ARM code for high performance."],
                    ['title' => 'Flutter Widgets & Layouts', 'content' => "Everything in Flutter is a widget.\n\nStatelessWidget vs StatefulWidget. Layout widgets: Row, Column, Stack, Container.\n\nBuild responsive UIs with MediaQuery and LayoutBuilder."],
                    ['title' => 'State Management', 'content' => "Managing state is crucial in Flutter apps.\n\nOptions: setState, Provider, Riverpod, BLoC, GetX.\n\nWe'll focus on Provider pattern for clean architecture."],
                    ['title' => 'Firebase Integration', 'content' => "Connect your Flutter app to Firebase backend.\n\nAuth, Firestore database, Cloud Storage, Push Notifications.\n\nBuild a real-time chat feature with Firestore streams."],
                    ['title' => 'Publishing to App Stores', 'content' => "Prepare your app for release.\n\nAndroid: signing, Play Console setup, release build.\n\niOS: certificates, App Store Connect, TestFlight.\n\nCI/CD with Codemagic or GitHub Actions."],
                ],
            ],
            [
                'tutor_index' => 0,
                'title'       => 'Git & GitHub for Teams',
                'description' => 'Master version control and collaborative development workflows.',
                'category'    => 'DevOps',
                'price'       => 0,
                'modules'     => [
                    ['title' => 'Git Basics', 'content' => "Git tracks changes in your code over time.\n\nCommands: git init, add, commit, log, diff, status.\n\nUnderstand the staging area and commit history."],
                    ['title' => 'Branching & Merging', 'content' => "Branches let you work on features independently.\n\ngit branch, checkout, merge, rebase.\n\nResolving merge conflicts and using git stash."],
                    ['title' => 'GitHub Collaboration', 'content' => "GitHub adds collaboration on top of Git.\n\nPull Requests, Code Reviews, Issues, Projects.\n\nBranch protection rules and CI/CD with GitHub Actions."],
                ],
            ],
        ];

        foreach ($coursesData as $cd) {
            $course = Course::create([
                'tutor_id'     => $tutorUsers[$cd['tutor_index']]['tutor']->id,
                'title'        => $cd['title'],
                'description'  => $cd['description'],
                'category'     => $cd['category'],
                'price'        => $cd['price'],
                'is_published' => true,
            ]);

            foreach ($cd['modules'] as $order => $md) {
                $module = Module::create([
                    'course_id' => $course->id,
                    'title'     => $md['title'],
                    'content'   => $md['content'],
                    'order'     => $order + 1,
                ]);

                // Add quiz to every module
                $quiz = Quiz::create([
                    'module_id'     => $module->id,
                    'title'         => 'Quiz: ' . $md['title'],
                    'passing_score' => 60,
                ]);

                QuizQuestion::insert([
                    [
                        'quiz_id'        => $quiz->id,
                        'question'       => 'What is the main topic of "' . $md['title'] . '"?',
                        'options'         => json_encode([$md['title'], 'Cooking recipes', 'Sports news', 'Weather forecast']),
                        'correct_answer' => 0,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ],
                    [
                        'quiz_id'        => $quiz->id,
                        'question'       => 'Which best describes a key concept from this module?',
                        'options'         => json_encode(['A fundamental building block', 'An unrelated topic', 'A cooking technique', 'A sports term']),
                        'correct_answer' => 0,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ],
                    [
                        'quiz_id'        => $quiz->id,
                        'question'       => 'What should you do after studying this material?',
                        'options'         => json_encode(['Practice the concepts learned', 'Ignore everything', 'Skip to next course', 'Delete the files']),
                        'correct_answer' => 0,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ],
                ]);
            }
        }

        // ── Enrollments ───────────────────────────────────
        $allCourses = Course::all();
        foreach ($students as $i => $student) {
            // Each student enrolls in 1-2 courses
            $enrolled = $allCourses->random(min(2, $allCourses->count()));
            foreach ($enrolled as $course) {
                $progress = rand(0, 100);
                Enrollment::create([
                    'user_id'      => $student->id,
                    'course_id'    => $course->id,
                    'is_completed' => $progress === 100,
                    'progress'     => $progress,
                    'completed_at' => $progress === 100 ? now() : null,
                ]);
            }
        }

        // ── Forum Threads ─────────────────────────────────
        $threads = [
            ['title' => 'Tips for learning Laravel quickly?',      'content' => "I'm new to Laravel and want to learn it efficiently. What resources and strategies do you recommend for beginners?\n\nI have basic PHP knowledge and understand MVC concepts."],
            ['title' => 'How to handle file uploads in Laravel?',  'content' => "I need to upload images for my project. What's the best approach? Should I use local storage or cloud storage like S3?\n\nAlso, how do I validate file types and sizes?"],
            ['title' => 'Flutter vs React Native in 2026',        'content' => "What are your thoughts on Flutter vs React Native for new projects in 2026? I need to choose one for my startup.\n\nPerformance, ecosystem, and hiring are my main concerns."],
            ['title' => 'Best practices for REST API design',     'content' => "I'm designing an API for a mobile app. What conventions should I follow?\n\nNaming routes, versioning, error responses, pagination — any tips?"],
        ];

        foreach ($threads as $i => $td) {
            $thread = ForumThread::create([
                'user_id'  => $students[$i % count($students)]->id,
                'title'    => $td['title'],
                'content'  => $td['content'],
            ]);

            // Add 1-3 replies
            $replyCount = rand(1, 3);
            for ($r = 0; $r < $replyCount; $r++) {
                ForumReply::create([
                    'forum_thread_id' => $thread->id,
                    'user_id'         => $students[($i + $r + 1) % count($students)]->id,
                    'content'         => 'Great question! ' . fake()->sentence(rand(8, 20)),
                ]);
            }
        }

        // ── Products (Marketplace) ────────────────────────
        $productsData = [
            ['name' => 'Laravel Cheat Sheet (PDF)',    'description' => 'Comprehensive Laravel reference card. Covers Artisan commands, Eloquent methods, Blade directives, and more.', 'price' => 25000,  'stock' => 100],
            ['name' => 'Programming Sticker Pack',     'description' => 'Set of 20 high-quality vinyl stickers with programming logos and funny developer quotes.',                    'price' => 35000,  'stock' => 50],
            ['name' => 'Web Dev Course Notes Bundle',  'description' => 'Handwritten digital notes covering HTML, CSS, JavaScript, PHP, and Laravel fundamentals.',                    'price' => 45000,  'stock' => 200],
            ['name' => 'Mechanical Keyboard (Used)',   'description' => 'Cherry MX Brown switches, TKL layout. Used for 6 months, excellent condition.',                                'price' => 450000, 'stock' => 1],
            ['name' => 'UI/UX Design Templates',      'description' => 'Figma templates for 10 common app screens: login, dashboard, profile, settings, and more.',                    'price' => 75000,  'stock' => 999],
        ];

        foreach ($productsData as $i => $pd) {
            Product::create([
                'seller_id'   => $students[$i % count($students)]->id,
                'name'        => $pd['name'],
                'description' => $pd['description'],
                'price'       => $pd['price'],
                'stock'       => $pd['stock'],
            ]);
        }

        // ── Addresses ─────────────────────────────────────
        foreach ($students as $student) {
            Address::create([
                'user_id'     => $student->id,
                'label'       => 'Home',
                'address'     => fake()->streetAddress(),
                'city'        => fake()->city(),
                'province'    => fake()->state(),
                'postal_code' => fake()->postcode(),
            ]);
        }

        // ── Tutor Payments (admin pays tutors) ────────────
        foreach ($tutorUsers as $tu) {
            TutorPayment::create([
                'tutor_id' => $tu['tutor']->id,
                'admin_id' => $admin->id,
                'amount'   => rand(500, 2000) * 1000,
                'period'   => 'Maret 2026',
                'notes'    => 'Pembayaran bulanan',
                'status'   => 'paid',
            ]);
            TutorPayment::create([
                'tutor_id' => $tu['tutor']->id,
                'admin_id' => $admin->id,
                'amount'   => rand(500, 2000) * 1000,
                'period'   => 'April 2026',
                'notes'    => 'Pembayaran bulanan',
                'status'   => 'paid',
            ]);
        }

        $this->command->info('✅ Seeded: 1 admin, 3 tutors, 5 students, 4 courses with modules & quizzes, enrollments, 4 forum threads, 5 products, addresses, tutor payments');
    }
}
