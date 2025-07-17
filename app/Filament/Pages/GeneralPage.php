<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\General;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class GeneralPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'General';
    protected static ?string $navigationGroup = 'Setting';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.general-page';

    protected static ?string $title = 'General Setting';

    public ?array $data = [];

    public function mount(): void
    {
        $general = General::first();
        $this->form->fill(
            $general ? $general->toArray() : []
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Site Setting')->schema(
                    [
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('subtitle')
                            ->required(),
                        Textarea::make('meta_description')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        FileUpload::make('logo')->image()->directory('general/logo')->required()->columnSpanFull(),
                        TextInput::make('donation_url')
                            ->required()
                            ->columnSpanFull(),
                    ]
                )->columns(2),
                Section::make('About')->schema(
                    [
                        TextInput::make('about_name')
                            ->required(),
                        TextInput::make('about_description')
                            ->required(),
                        FileUpload::make('about_photo')->image()->directory('general/photo')->required()->columnSpanFull(),
                        RichEditor::make('welcome_speech')
                            ->required()
                            ->fileAttachmentsDirectory('general/about')->columnSpanFull(),
                        RichEditor::make('vision_mission')
                            ->required()
                            ->fileAttachmentsDirectory('general/about')->columnSpanFull(),
                        RichEditor::make('goals')
                            ->required()
                            ->fileAttachmentsDirectory('general/about')->columnSpanFull(),
                    ]
                )->columns(2),

                Section::make('Information')->schema(
                    [
                        Textarea::make('address')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->required(),
                        TextInput::make('hotline')
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->email(),
                        TextInput::make('maps_url')
                            ->required()
                            ->url(),
                        TextInput::make('social_instagram')
                            ->required()
                            ->url(),
                        TextInput::make('social_youtube')
                            ->required()
                            ->url(),
                        TextInput::make('social_facebook')
                            ->required()
                            ->url(),
                        TextInput::make('social_twitter')
                            ->required()
                            ->url(),
                    ]
                )->columns(1),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();

        $general = General::first();
        if ($general) {
            $general->update($data);
        } else {
            General::create($data);
        }

        \Filament\Notifications\Notification::make()
            ->title('General settings updated!')
            ->success()
        ->send();
}

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->action('save'),
        ];
    }
}
