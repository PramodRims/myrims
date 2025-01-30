<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Imports\CourseImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Actions\Action as FormAction;
use Illuminate\Support\HtmlString;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('import')->label('Add Bulk Students')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload CSV File')
                        ->hintColor('danger') // 'danger' applies red color
                        ->required()
                        ->hintActions([
                            FormAction::make('Download Sample')
                                ->icon('heroicon-o-document-arrow-down')
                                ->color('success')
                                ->action(function () {
                                    $courseId  = $this->record->id;
                                    // Set headers for CSV response
                                    $headers = [
                                        'Content-Type' => 'text/csv',
                                        'Content-Disposition' => 'attachment; filename="course_students.csv"',
                                    ];
                                    $user = auth()->user();

                                    $user->notify(
                                        Notification::make()
                                            ->title('Success')
                                            ->body('Sample CSV file for student course downloaded successfully')
                                            ->toDatabase(),
                                    );
                                    // Return the response with CSV data
                                    return response()->stream(function () use ($courseId) {
                                        $handle = fopen('php://output', 'w');
                                        fputcsv($handle, ['student_id', 'course_id']);

                                        // Write sample data for 30 students
                                        foreach (range(1, 2) as $studentId) {
                                            fputcsv($handle, ['', $courseId]);
                                        }

                                        fclose($handle);
                                    }, 200, $headers);
                                })
                        ]),

                    Placeholder::make('description')->label('')
                        ->content(new HtmlString('<span style="color: #afafaf;">Only courses_id and student_id  will be imported here *</span>')),

                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);
                    Excel::import(new CourseImport, $file);

                    //dd($file);
                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Course imported successfully')
                        ->send();
                }),
        ];
    }

    public static function courseid(Request $request)
    {
        // Check if course parameter exists in the route
        if ($request->route('course')) {
            $courseId = $request->route('course');
        } else {
            // If course is not found, log it for debugging
            logger('Course ID not found in the route.');
            $courseId = null;
        }

        // Return the course ID
        return $courseId;
    }
}
