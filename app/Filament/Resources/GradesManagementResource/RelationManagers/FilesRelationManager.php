<?php

namespace App\Filament\Resources\GradesManagementResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file_url')
                    ->multiple()
                    ->panelLayout('stack', 3)
                    // ->panelAspectRatio('4:2')
                    ->previewable(false)
                    ->downloadable()
                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxFiles(5)
                    ->directory('files')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_url')
            ->columns([
                Tables\Columns\TextColumn::make('file_url')->label('File')
                    ->formatStateUsing(function ($state) {
                        //check file extension
                        $extension = pathinfo($state, PATHINFO_EXTENSION);
                        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG') {
                            $file = asset('storage/'.$state);
                        } else {
                            $file = asset('images/file_icon.png');
                        }

                        return new HtmlString('<a href="' . asset('storage/'.$state) . '" target="_blank"><img src="' . $file . '" width="100" height="100"></a>');
                    })->description(function ($state) {
                        return $state;
                    }),
                    Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
