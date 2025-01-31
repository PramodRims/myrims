<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CourseImport;
use App\Models\Course;
use App\Models\Batch;

class ListBatches extends ListRecords
{
    protected static string $resource = BatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // Import Action
            Action::make('import')
                ->label('Add Bulk Students, Course, and Batch ID')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload CSV File')
                        ->hintColor('danger')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);
                    Excel::import(new CourseImport, $file);

                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Course imported successfully')
                        ->send();
                }),

            // Export Sample CSV Action
            Action::make('export')
                ->label('Generate Sample')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    Select::make('course_id')
                        ->label('Course')
                        ->options(Course::all()->pluck('name', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $batches = Batch::where('course_id', $state)->pluck('name', 'id');
                            $set('batch_id', null); // Reset batch when course changes
                            $set('batch_options', $batches); // Update batch options dynamically
                            $set('batch_id_disabled', false); // Enable batch field when course is selected
                        }),

                    Select::make('batch_id')
                        ->label('Batch')
                        ->options(fn($get) => $get('batch_options') ?? [])
                        ->disabled(fn($get) => $get('batch_id_disabled') ?? true), // Disable batch select until course is selected
                ])
                ->action(function (array $data) {
                    $courseId = $data['course_id'] ?? null;
                    $batchId = $data['batch_id'] ?? null;

                    // Notify User
                    auth()->user()->notify(
                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body('Sample CSV file for student course and batch downloaded successfully')
                            ->toDatabase()
                    );

                    // Set headers for CSV response
                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="course_batch_students.csv"',
                    ];

                    // Return CSV response
                    return response()->stream(function () use ($courseId, $batchId) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['student_id', 'course_id', 'batch_id']);

                        //foreach (range(1, 2) as $studentId) {
                            fputcsv($handle, ['', $courseId, $batchId]);
                        //}

                        fclose($handle);
                    }, 200, $headers);
                }),
        ];
    }
}
