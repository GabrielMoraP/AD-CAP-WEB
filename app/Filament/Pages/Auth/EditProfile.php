<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    /**
     * Define the form schema for the edit profile page.
     *
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Name input field
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')  // Label for the field
                    ->helperText('Nombre completo.')  // Helper text for the field
                    ->required()  // Make the field required
                    ->dehydrated(fn ($state) => filled($state))  // Ensure the field is included when filled
                    ->maxLength(255),  // Set maximum length for the input

                // Username input field
                Forms\Components\TextInput::make('user')
                    ->unique(ignoreRecord: true)  // Ensure the username is unique, ignoring the current record
                    ->label('Usuario')  // Label for the field
                    ->helperText('Nombre de usuario.')  // Helper text for the field
                    ->required()  // Make the field required
                    ->dehydrated(fn ($state) => filled($state))  // Ensure the field is included when filled
                    ->validationMessages([  // Custom validation messages
                        'unique' => 'El correo ya esta registrado por otro usuario.',
                    ])
                    ->maxLength(255),  // Set maximum length for the input

                // Avatar select field
                Forms\Components\Select::make('avatar')
                    ->label("Avatar")  // Label for the field
                    ->helperText('Avatar del usuario.')  // Helper text for the field
                    ->required()  // Make the field required
                    ->dehydrated(fn ($state) => filled($state))  // Ensure the field is included when filled
                    ->searchable()  // Make the select input searchable
                    ->allowHtml()  // Allow HTML content in the options
                    ->options([  // Define the avatar options with image previews
                        1 => '<img src="' . env('APP_URL') . '/storage/avatars/1.png" width="50" height="50">',
                        2 => '<img src="' . env('APP_URL') . '/storage/avatars/2.png" width="50" height="50">',
                        3 => '<img src="' . env('APP_URL') . '/storage/avatars/3.png" width="50" height="50">',
                        4 => '<img src="' . env('APP_URL') . '/storage/avatars/4.png" width="50" height="50">',
                        5 => '<img src="' . env('APP_URL') . '/storage/avatars/5.png" width="50" height="50">',
                        6 => '<img src="' . env('APP_URL') . '/storage/avatars/6.png" width="50" height="50">',
                    ])
                    ->validationMessages([  // Custom validation message for avatar field
                        'required' => 'El campo de avatar es obligatorio.',
                    ]), 

                // Password field (inherited from the base class method)
                $this->getPasswordFormComponent(),

                // Password confirmation field (inherited from the base class method)
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    /**
     * Get the URL to redirect to after the profile is updated.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return "/";  // Redirect to the homepage or dashboard after profile update
    }
}