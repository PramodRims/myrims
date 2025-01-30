<?php

namespace App\Filament\Resources\GradesManagementResource\Pages;

use App\Filament\Resources\GradesManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGradesManagement extends EditRecord
{
    protected static string $resource = GradesManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Get the file_url and unset it from the data array
        $files = isset($data['file_url']) ? [$data['file_url']] : [];
        unset($data['file_url']);

        // Create the record without the file_url
        $record->update($data);
        // Initialize the new files array
        $newfiles = [];

        // If there are files, associate them with the record
        if (!empty($files)) {
            // $record->files()->delete();  // Delete existing files
            foreach (array_values($files[0]) as $key => $file) {
                $newfiles[] = ['file_url' => $file];  // Collect all file records
            }
            $record->files()->createMany($newfiles);
        }

        return $record;
    }
}
