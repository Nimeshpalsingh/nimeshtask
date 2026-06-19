<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StoreStudentJob implements ShouldQueue
{
    use Queueable;

    protected $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function handle(): void
    {
        Student::create([
            'name' => $this->student['name'],
            'class' => $this->student['class'],
            'phone' => $this->student['phone'],
        ]);
    }
}