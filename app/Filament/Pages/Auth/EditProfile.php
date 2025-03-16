<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->helperText('Nombre completo.')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),

                Forms\Components\TextInput::make('user')
                    ->unique(ignoreRecord: true)
                    ->label('Usuario')
                    ->helperText('Nombre de usuario.')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state))
                    ->validationMessages([
                        'unique' => 'El correo ya esta registrado por otro usuario.',
                    ])
                    ->maxLength(255),

                Forms\Components\Select::make('avatar')
                    ->label("Avatar")
                    ->helperText('Avatar del usuario.')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state))
                    ->searchable()
                    ->allowHtml()
                    ->options([
                        1 => '<img src="' . env('APP_URL') . '/storage/avatars/1.png" width="50" height="50">',
                        2 => '<img src="' . env('APP_URL') . '/storage/avatars/2.png" width="50" height="50">',
                        3 => '<img src="' . env('APP_URL') . '/storage/avatars/3.png" width="50" height="50">',
                        4 => '<img src="' . env('APP_URL') . '/storage/avatars/4.png" width="50" height="50">',
                        5 => '<img src="' . env('APP_URL') . '/storage/avatars/5.png" width="50" height="50">',
                        6 => '<img src="' . env('APP_URL') . '/storage/avatars/6.png" width="50" height="50">',
                    ])
                    ->validationMessages([
                        'required' => 'El campo de avatar es obligatorio.',
                    ]),                

                $this->getPasswordFormComponent(),
                
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getRedirectUrl(): string
    {
        return "/";
    }
}
