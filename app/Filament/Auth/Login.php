<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;

class Login extends BaseAuth
{
    /**
     * Define the form schema for the login page.
     *
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // User input field
                TextInput::make('user')
                    ->label('Usuario')  // The label for the input field
                    ->required()        // Make the field required
                    ->autocomplete()     // Enable autocomplete for the field
                    ->autofocus()        // Focus the field when the page loads
                    ->extraInputAttributes(['tabindex' => 1]),  // Additional HTML attributes like tabindex

                // Password input field (from the base class method)
                $this->getPasswordFormComponent(),

                // Remember me checkbox (from the base class method)
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');  // Path where the form's data will be stored
    }

    /**
     * Get the credentials from the form data.
     *
     * @param array $data
     * @return array
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'user' => $data['user'],  // Return 'user' data from the form
            'password' => $data['password'],  // Return 'password' data from the form
        ];
    }

    /**
     * Throw a validation exception when login fails.
     *
     * @return never
     */
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.user' => __('filament-panels::pages/auth/login.messages.failed'),  // Error message for failed login
        ]);
    }
}