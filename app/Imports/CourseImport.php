<?php

namespace App\Imports;

use App\Models\CourseHasStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $studentExists = \App\Models\User::where('id', $row['student_id'])->exists();

        if (!$studentExists) {
            logger("Student ID {$row['student_id']} does not exist.");
            return null;  // Skip the row if the student doesn't exist
        }

        return new CourseHasStudent([
            'course_id' => $row['course_id'],
            'student_id' => $row['student_id'],
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
