<?php
namespace App\Imports;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Maatwebsite\Excel\Concerns\ToModel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Tables\Actions\ImportAction;

class StudentsImport implements ToModel
{
    /**
     * Define the columns for the import.
     */
    public static function getColumns(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'phone',
        ];
    }

    /**
     * Transform the row into a User model.
     */
    public function model(array $row)
    {
        return new User([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
        ]);
    }

    /**
     * Define the form components for the import options.
     */
    public static function getOptionsFormComponents(): array
    {
        return [
            TextInput::make('delimiter')
                ->label('Delimiter')
                ->default(',')
                ->helperText('Specify the delimiter if your file uses something other than a comma.')
                ->required(),

            Select::make('column_mapping')
                ->label('Column Mapping')
                ->options([
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                ])
                ->default('first_name')
                ->helperText('Choose the column mapping option for this import.')
        ];
    }

    /**
     * Configure the import action with correct column mappings.
     */
    public function configureImportAction(): ImportAction
    {
        return ImportAction::make()
            ->columns([
                'first_name' => ImportColumn::make('first_name'),
                'last_name' => ImportColumn::make('last_name'),
                'email' => ImportColumn::make('email'),
                'phone' => ImportColumn::make('phone'),
            ]);
    }
}
