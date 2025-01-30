<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\StudentsImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Actions\Action as FormAction;
use Illuminate\Support\HtmlString;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import')->label('Import Students')
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
                                        'Content-Disposition' => 'attachment; filename="sample_students.csv"',
                                    ];
                                    $user = auth()->user();

                                    $user->notify(
                                        Notification::make()
                                            ->title('Success')
                                            ->body('Sample CSV file  for student downloaded successfully')
                                            ->toDatabase(),
                                    );
                                    // Return the response with CSV data
                                    return response()->stream(function () {
                                        $handle = fopen('php://output', 'w');

                                        // Define the header row
                                        fputcsv($handle, ['first_name', 'last_name', 'email', 'phone']);

                                        // Add sample data (optional)
                                        fputcsv($handle, ['John', 'Doe', 'john@example.com', '1234567890']);
                                        fputcsv($handle, ['Jane', 'Smith', 'jane@example.com', '0987654321']);

                                        fclose($handle);
                                    }, 200, $headers);
                                })
                        ]),

                    Placeholder::make('description')->label('')
                        ->content(new HtmlString('<span style="color: #afafaf;">Only students will be imported here *</span>')),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);
                    Excel::import(new StudentsImport, $file);

                    //dd($file);
                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Students imported successfully')
                        ->send();
                }),

        ];
    }
}
