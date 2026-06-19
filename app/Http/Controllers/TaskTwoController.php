<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;

class TaskTwoController extends Controller
{
    public function index()
    {
        $students = Student::with('courses')->get();
        return view('task2', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_class' => 'required|string|max:255',
            'student_phone' => 'required|string|max:20',
            'course_name' => 'required|string|max:255',
        ]);

        $student = Student::firstOrCreate(
            ['phone' => $request->student_phone],
            ['name' => $request->student_name, 'class' => $request->student_class]
        );

        $course = Course::firstOrCreate(['name' => $request->course_name]);

        $student->courses()->syncWithoutDetaching([$course->id]);

        return redirect()->back()->with('success', 'Data saved and linked successfully!');
    }
}
