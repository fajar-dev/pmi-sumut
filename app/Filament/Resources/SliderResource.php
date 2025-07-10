<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Slider;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\SliderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Filament\Resources\SliderResource\Pages\ManageSliders;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class SliderResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationLabel = 'Slider';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Setting';

    protected static ?int $navigationSort = 2;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
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
                FileUpload::make('image')->image()->directory('sliders')->label('Image (1920x800)')->required()->columnSpanFull(),
                TextInput::make('title')
                        ->required()
                        ->maxLength(255)->columnSpanFull(),
                Textarea::make('description')
                        ->required()
                        ->maxLength(255)->columnSpanFull(),
                TextInput::make('sort')
                        ->numeric()
                        ->default(1)
                        ->required()->columnSpanFull(),
                Checkbox::make('featured')->default(true)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('description')->sortable()->searchable(),
                TextInputColumn::make('sort')
                    ->sortable()
                    ->rules(['numeric', 'min:1']),
                CheckboxColumn::make('featured'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSliders::route('/'),
        ];
    }
}
