<?php

namespace App\Imports;

use App\Models\GradesManagement;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class GradesManagementImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $gradesmanagement = new GradesManagement([
            'name'  => $row['name'],
            'grade_percentage'   => $row['grade_percentage'],
            'course_id' => $row['course_id'],
            'student_id'    => $row['student_id'],
            'category_id' => $row['category_id'],
            'grade_date' => $row['grade_date'],
        ]);


        $gradesmanagement->save();
        $files = $row['files_url'];
        $filesdata = explode('|', $files);
        foreach ($filesdata as $file) {
            $filename = 'grades-management/' . basename($file);
            $gradesmanagement->files()->create([
                'file_url' => $filename,
            ]);
        }
        return $gradesmanagement;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
