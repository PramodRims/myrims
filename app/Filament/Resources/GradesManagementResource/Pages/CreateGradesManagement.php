<?php

namespace App\Filament\Resources\GradesManagementResource\Pages;

use App\Filament\Resources\GradesManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateGradesManagement extends CreateRecord
{
    protected static string $resource = GradesManagementResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $files = isset($data['file_url']) ? [$data['file_url']] : [];
        unset($data['file_url']);
        $record = new ($this->getModel())($data);
        $record->save();
        $newfiles = [];
        if (!empty($files)) {
            foreach (array_values($files[0]) as $key => $file) {
                $newfiles[] = ['file_url' => $file];  // Collect all file records
            }
            $record->files()->createMany($newfiles);
        }

        return $record;
    }
}
