<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['modules', 'enrollments'])->latest()->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'modules' => 'nullable|array',
            'modules.*.title' => 'required|string|max:255',
            'modules.*.content' => 'required|string',
            'modules.*.video_url' => 'nullable|url',
        ]);

        $data = $request->only(['title', 'description', 'category', 'price', 'is_published']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course = Course::create($data);

        // Create modules if provided
        if ($request->has('modules')) {
            foreach ($request->modules as $order => $moduleData) {
                $module = Module::create([
                    'course_id' => $course->id,
                    'title' => $moduleData['title'],
                    'content' => $moduleData['content'],
                    'video_url' => $moduleData['video_url'] ?? null,
                    'order' => $order + 1,
                ]);

                // Auto-generate quiz for each module
                $this->generateQuizForModule($module);
            }
        }

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil dibuat!');
    }

    public function edit(Course $course)
    {
        $course->load('modules.quiz.questions');
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'category', 'price']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil diupdate!');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil dihapus!');
    }

    public function modules(Course $course)
    {
        $course->load('modules.quiz.questions');
        return view('admin.courses.modules', compact('course'));
    }

    public function storeModule(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
        ]);

        $maxOrder = $course->modules()->max('order') ?? 0;

        $module = Module::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'order' => $maxOrder + 1,
        ]);

        // Auto-generate quiz
        $this->generateQuizForModule($module);

        return redirect()->route('admin.courses.modules', $course)->with('success', 'Modul berhasil ditambahkan!');
    }

    public function updateModule(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
        ]);

        $module->update($request->only(['title', 'content', 'video_url']));

        return redirect()->route('admin.courses.modules', $module->course)->with('success', 'Modul berhasil diupdate!');
    }

    public function destroyModule(Module $module)
    {
        $course = $module->course;
        $module->delete();
        return redirect()->route('admin.courses.modules', $course)->with('success', 'Modul berhasil dihapus!');
    }

    public function regenerateQuiz(Module $module)
    {
        // Delete existing quiz
        if ($module->quiz) {
            $module->quiz->questions()->delete();
            $module->quiz->delete();
        }

        $this->generateQuizForModule($module);

        return redirect()->route('admin.courses.modules', $module->course)->with('success', 'Quiz berhasil di-regenerate!');
    }

    private function generateQuizForModule(Module $module): void
    {
        $content = $module->content;
        $title = $module->title;

        $quiz = Quiz::create([
            'module_id' => $module->id,
            'title' => 'Quiz: ' . $title,
            'passing_score' => 60,
        ]);

        // Extract key sentences from content to generate questions
        $sentences = preg_split('/[.\n]+/', $content);
        $sentences = array_filter($sentences, fn($s) => strlen(trim($s)) > 20);
        $sentences = array_values($sentences);

        $questions = [];

        // Question 1: About the main topic
        $questions[] = [
            'quiz_id' => $quiz->id,
            'question' => "Apa topik utama yang dibahas dalam modul '{$title}'?",
            'options' => json_encode([
                $title,
                'Cara memasak nasi goreng',
                'Sejarah Indonesia',
                'Teknik olahraga renang',
            ]),
            'correct_answer' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Question 2: Based on content keyword
        if (count($sentences) >= 2) {
            $keySentence = trim($sentences[1] ?? $sentences[0]);
            $questions[] = [
                'quiz_id' => $quiz->id,
                'question' => "Manakah pernyataan yang benar berdasarkan materi modul ini?",
                'options' => json_encode([
                    Str::limit($keySentence, 100),
                    'Pernyataan yang tidak berhubungan dengan materi',
                    'Materi ini tidak membahas topik apapun',
                    'Semua jawaban salah',
                ]),
                'correct_answer' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Question 3: Comprehension
        $questions[] = [
            'quiz_id' => $quiz->id,
            'question' => "Apa yang sebaiknya dilakukan setelah mempelajari modul '{$title}'?",
            'options' => json_encode([
                'Mempraktikkan materi yang dipelajari',
                'Mengabaikan semua materi',
                'Tidak perlu latihan',
                'Langsung tidur',
            ]),
            'correct_answer' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Question 4: If enough content
        if (count($sentences) >= 3) {
            $questions[] = [
                'quiz_id' => $quiz->id,
                'question' => "Konsep apa yang paling penting dari modul ini?",
                'options' => json_encode([
                    'Pemahaman mendalam tentang ' . Str::limit($title, 50),
                    'Tidak ada konsep penting',
                    'Hanya teori tanpa praktek',
                    'Materi yang sudah kadaluarsa',
                ]),
                'correct_answer' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Question 5
        $questions[] = [
            'quiz_id' => $quiz->id,
            'question' => "Modul '{$title}' termasuk dalam kategori pembelajaran apa?",
            'options' => json_encode([
                $module->course->category ?? 'Teknologi',
                'Seni Rupa',
                'Pertanian',
                'Kedokteran',
            ]),
            'correct_answer' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        QuizQuestion::insert($questions);
    }
}
