<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Page;
use Filament\Tables;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PageResource\Pages;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PageResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class PageResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationLabel = 'Custom Page';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Navigation';

    protected static ?int $navigationSort = 3;

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
                        FileUpload::make('image')->image()->directory('pages/thumbnails'),
                        TextInput::make('title')
                            ->live()
                            ->required()->minLength(1)->maxLength(150)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'edit') {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('route')->required()->minLength(1)->default('page')->maxLength(150),
                        TextInput::make('slug')->required()->minLength(1)->unique(ignoreRecord: true)->maxLength(150),
                        Textarea::make('description')
                        ->maxLength(255)
                        ->columnSpanFull(),
                        TinyEditor::make('content')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('pages/images')
                            ->profile('default|simple|full|minimal|none|custom')
                            ->columnSpan('full')
                            ->required(),
                        Select::make('user_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]
                )->columns(1),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Thumbnail'),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('slug')
                    ->label('URL Slug')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) => '/' . $record->route . '/' . $state),
                TextColumn::make('description')->sortable()->searchable(),
                TextColumn::make('author.name')->sortable()->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
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
