<?php
namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles'; // Relationship name should be correct

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('role_id') // We need to use 'role_id' here for the relationship field
                    ->label('Role')
                    ->required()
                    ->searchable()
                    ->options(Role::all()->pluck('name', 'id')) // Using 'id' as key and 'name' as value
                    ->placeholder('Select Role'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Role Name'), // Optional, for better clarity
            ])
            ->filters([
                // Add any filters here if needed
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->label('Attach Role')
                ->recordSelect(function (Select $select) {
                    $select->options(function () {
                        return Role::whereNotIn('name', ['superadmin'])->pluck('name', 'id');
                        // return Role::where('name', '!=', 'Helpdesk Admin')->pluck('name', 'id');
                    });
                    $select->label('Roles');
                    $select->placeholder('Select Roles');
                    $select->searchable();

                    return $select;
                })->beforeFormValidated(function($livewire){
                    $userId = $livewire->ownerRecord->id;
                    $user = User::find($userId);
                    if($user && count($user->roles) ==0){
                        //
                    }else{
                        $user->roles()->detach();
                    }

                })
                , // Label for the action
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // To edit the relationship
                Tables\Actions\DeleteAction::make(), // To remove a role from user
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete') // Custom bulk action to delete
                    ->label('Delete Selected Roles')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->delete();
                        }
                    }),
            ]);
    }
}
