<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agenda;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AgendaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgendaResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class AgendaResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Agenda::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Main Content')->schema(
                    [
                        FileUpload::make('image')->image()->directory('agendas/thumbnails')
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->live()
                            ->required()->minLength(1)->maxLength(150)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'edit') {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')->required()->minLength(1)->unique(ignoreRecord: true)->maxLength(150),
                        DatePicker::make('start_date')
                            ->required()
                            ->displayFormat('d F Y H:i'),
                        DatePicker::make('end_date')
                            ->displayFormat('d F Y H:i'),
                        Textarea::make('address')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('map_url')
                            ->url()
                            ->maxLength(255)
                            ->label('Google Map Link')
                            ->columnSpanFull(),
                        Select::make('user_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->required()
                            ->fileAttachmentsDirectory('agendas/images')->columnSpanFull(),
                    ]
                )->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Thumbail'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('author.name')->sortable()->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                    Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                        Tables\Actions\ForceDeleteBulkAction::make(),
                        Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListAgendas::route('/'),
            'create' => Pages\CreateAgenda::route('/create'),
            'edit' => Pages\EditAgenda::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
