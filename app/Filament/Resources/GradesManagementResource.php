<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradesManagementResource\Pages;
use App\Filament\Resources\GradesManagementResource\RelationManagers\FilesRelationManager;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseHasStudent;
use App\Models\GradesManagement;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class GradesManagementResource extends Resource
{
    use WithFileUploads;
    protected static ?string $model = GradesManagement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Grade';
    // protected static ?string $navigationLabel = 'Grades Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 60% section with two inputs per row
                Forms\Components\Section::make('Grade Details')
                    ->schema([
                        Forms\Components\Grid::make(2) // Ensures two inputs per row
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique()
                                    ->columnSpan(1), // Each input takes half of the grid (1 out of 2)

                                Forms\Components\TextInput::make('grade_percentage')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1), // Completes first row
                                Forms\Components\Select::make('course_id')
                                    ->label('Course')
                                    ->options(Course::pluck('name', 'id'))
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1), // Completes second row


                                Forms\Components\Select::make('student_id')
                                    ->label('Student')
                                    ->options(function (callable $get) {
                                        $courseId = $get('course_id'); // Get the selected course_id

                                        if ($courseId) {
                                            return CourseHasStudent::where('course_id', $courseId)
                                                ->with('student:id,first_name,last_name') // Eager load student with only necessary fields
                                                ->get()
                                                ->mapWithKeys(function ($item) {
                                                    // Concatenate first and last names
                                                    $fullName = $item->student->first_name . ' ' . $item->student->last_name;
                                                    return [$item->student->id => $fullName]; // Map student ID to full name
                                                });
                                        }

                                        return []; // Return an empty array if no course is selected
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->options(Category::pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),

                                Forms\Components\DatePicker::make('grade_date')
                                    ->required()
                                    ->columnSpan(1), // Completes third row
                                Placeholder::make('start_date')->content(fn($get) => $get('course_id')
                                    ? Carbon::parse(Course::find($get('course_id'))->start_date)->format('d-M-Y')
                                    : ''),

                                Placeholder::make('end_date')->content(fn($get) => $get('course_id')
                                    ? Carbon::parse(Course::find($get('course_id'))->end_date)->format('d-M-Y')
                                    : ''),

                            ])
                    ])
                    ->columnSpan(7), // 60% width

                // 40% section for file upload
                Forms\Components\Section::make('File Upload')
                    ->schema([
                        Forms\Components\FileUpload::make('file_url')
                            ->multiple()

                            ->panelLayout('stack', 3)
                            // ->panelAspectRatio('4:2')
                            ->previewable(false)
                            ->downloadable()
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxFiles(5)
                            ->directory('grades-management')
                            ->columnSpanFull(), // Ensures it spans the full 40% width
                    ])
                    ->columnSpan(5), // 40% width for file upload
            ])
            ->columns(12); // Ensures proper layout


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('course.name'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('student_id')
                ->relationship('student', 'registration_number', function ($query) {
                    $query->whereHas('roles', function ($q) {
                        $q->where('name', 'student');
                    })->select('id', 'registration_number', 'first_name');
                })
                ->label('Student')
                ->preload()
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->registration_number} - {$record->first_name}")
                ->default(null), // Ensures no default selection
                SelectFilter::make('course_id')
                    ->relationship('course', 'name')
                    ->label('Course')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload()
                    ->searchable(),
            ], layout: FiltersLayout::AboveContentCollapsible)
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
            FilesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGradesManagement::route('/'),
            'create' => Pages\CreateGradesManagement::route('/create'),
            'edit' => Pages\EditGradesManagement::route('/{record}/edit'),
        ];
    }
}
