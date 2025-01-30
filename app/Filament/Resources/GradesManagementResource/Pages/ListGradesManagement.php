<?php

namespace App\Filament\Resources\GradesManagementResource\Pages;

use App\Filament\Resources\GradesManagementResource;
use App\Imports\GradesFilesImport;
use App\Imports\GradesManagementImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\GradesManagement;

class ListGradesManagement extends ListRecords
{
    protected static string $resource = GradesManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import')->label('Import Grades Data')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload CSV File')
                        ->required()
                        ->hintActions([
                            FormAction::make('Download Sample')
                                ->icon('heroicon-o-document-arrow-down')
                                ->color('success')
                                ->action(function () {
                                    // Set headers for CSV response
                                    $headers = [
                                        'Content-Type' => 'text/csv',
                                        'Content-Disposition' => 'attachment; filename="sample_students_grades.csv"',
                                    ];
                                    $user = auth()->user();

                                    $user->notify(
                                        Notification::make()
                                            ->title('Success')
                                            ->body('Sample CSV file for students grades downloaded successfully')
                                            ->toDatabase(),
                                    );
                                    // Return the response with CSV data
                                    return response()->stream(function () {
                                        $handle = fopen('php://output', 'w');

                                        // Define the header row
                                        fputcsv($handle, ['name', 'grade_percentage', 'course_id', 'student_id', 'category_id', 'grade_date', 'files_url']);

                                        // Add sample data (optional)
                                        fputcsv($handle, ['test', '45', '1', '4', '1', '1999-06-25', 'https://example.com/image.jpg|https://example.com/image2.jpg']);

                                        fclose($handle);
                                    }, 200, $headers);
                                })
                        ]),

                    Placeholder::make('description')->label('')
                        ->content(new HtmlString('<span style="color: #afafaf;">Only students grades will be imported here *</span>')),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);
                    Excel::import(new GradesManagementImport, $file);

                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Grades imported successfully')
                        ->send();
                }),

            // Upload Marksheet/Certificate action
            Action::make('upload_files')
                ->label('Upload Files')
                ->icon('heroicon-o-document-arrow-up')
                ->modalHeading('Upload Files')
                ->form([
                    Select::make('id')
                        ->label('Select Grade Name')
                        ->options(GradesManagement::pluck('name', 'id')) // Populate the dropdown with students
                        ->required()
                        ->searchable()
                        ->preload(),
                    FileUpload::make('files')
                        ->multiple()
                        ->directory('grades-management')
                        ->label('Choose Files')
                        ->required()  // Ensure the file is uploaded
                        ->downloadable()
                        ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->maxFiles(5)
                ])
                ->action(function ($record, array $data) {
              
                    // Check if files are uploaded and a student is selected
                    if (isset($data['files']) && isset($data['id'])) {
                        $grid_id = $data['id'];
                        $files = $data['files'];
                        $gradesManagement = GradesManagement::find($grid_id);

                        foreach ($files as $key => $file) {
                            $newfiles[] = ['file_url' => $file];  // Collect all file records
                        }
                        $gradesManagement->files()->createMany($newfiles);
                        Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Grade documents uploaded successfully')
                        ->send();

                    }
                })
        ];
    }
}
