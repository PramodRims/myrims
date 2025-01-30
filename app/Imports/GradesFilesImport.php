<?php
namespace App\Imports;

use App\Models\GradesManagement;
use Maatwebsite\Excel\Concerns\ToModel;

class GradesFilesImport implements ToModel
{
    protected $grid_id;
    protected $files;

    // Constructor to accept studentId and files
    public function __construct($grid_id, $files)
    {
        $this->grid_id = $grid_id;
        $this->files = $files;
    }

    public function model(array $row)
    {
        // Create or find the GradesManagement record associated with the student
        $gradesManagement = GradesManagement::firstOrCreate([
            'id' => $this->grid_id, // Link to the student
        ]);

        // Loop through files and associate them with the GradesManagement record
        foreach ($this->files as $file) {
            $gradesManagement->files()->create([
                'file_url' => $file->store('grades-management', 'public'), // Store the file
            ]);
        }

        return $gradesManagement;
    }
}
