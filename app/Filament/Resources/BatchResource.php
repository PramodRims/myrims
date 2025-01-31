<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\StudentsRelationManager;
use App\Models\Batch;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Batch')
                    ->schema([
                        Grid::make(4) // 4 columns in a row
                            ->schema([
                                Forms\Components\TextInput::make('name')->visibleOn('edit'),
                                Forms\Components\Select::make('course_id')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('course', 'name'),

                                Forms\Components\Select::make('instructor_id')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->relationship('instructor', 'first_name'),
                                DatePicker::make('start_date'),
                                DatePicker::make('end_date'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('instructor.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->formatStateUsing(function ($state) {
                        return $state ? Carbon::parse($state)->format('d-M-Y') : $state;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->formatStateUsing(function ($state) {
                        return $state ? Carbon::parse($state)->format('d-M-Y') : $state;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
