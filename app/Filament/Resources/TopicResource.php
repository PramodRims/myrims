<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Filament\Resources\TopicResource\RelationManagers;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\F;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\Alignment;
use Livewire\WithFileUploads;

class TopicResource extends Resource

{
    use WithFileUploads;
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Topic Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::pluck('name', 'id'))
                    ->live()
                    ->required(),

                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->options(function (callable $get) {
                        $courseId = $get('course_id');
                        return $courseId ? Subject::where('course_id', $courseId)->pluck('name', 'id') : [];
                    })
                    ->required(),

                Forms\Components\Repeater::make('files')
                    ->label('Files')
                    ->relationship('files')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpan(1),  // This will place it in the first column
                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->columnSpan(1),  // This will place it in the second column
                        Forms\Components\Select::make('file_type')
                            ->options([
                                'video' => 'Video',
                                'document' => 'Document',
                            ])
                            ->required()
                            ->reactive()
                            ->columnSpan(1),  // This will place it in the third column
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->columnSpan(1),  // This will place it in the fourth column
                        Forms\Components\FileUpload::make('file_url')
                            ->label(function (callable $get) {
                                $type = $get('file_type') ?? 'document';
                                return $type === 'video' ? 'Video' : 'Document';
                            })
                            ->directory('files')
                            ->maxSize(20000)
                            ->acceptedFileTypes([
                                'video/mp4',
                                'video/mpeg',
                                'video/avi',
                                'video/webm',
                                'application/pdf',
                                'application/msword'
                            ])
                            ->required()
                            ->columnSpan('full'),  // This will place the file input in one full-width row
                    ])
                    ->columnSpan('full')  // This will make sure the entire repeater is full-width
                    ->addActionLabel('Add File')
                    ->columns(4)  // This defines four columns for the text/select inputs 
                    ])             
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
