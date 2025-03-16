<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de usuarios';
    }

    public static function form(Form $form): Form
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
                
                Forms\Components\Select::make('role')
                    ->label('Rol')
                    ->helperText('Rol del usuario.')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state))
                    ->options([
                        'Administrador' => 'Administrador',
                        'Editor' => 'Editor',
                        'Consultor' => 'Consultor',
                    ]),

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

                Forms\Components\TextInput::make('password')
                    ->label('Nueva contraseña')
                    ->helperText('Ingrese una nueva contraseña.')
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->dehydrated(fn ($state) => filled($state))
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->maxLength(255)
                    ->autocomplete('new-password')
                    ->same('password_confirmation')
                    ->validationMessages([
                        'same' => 'Las contraseñas no coinciden.',
                    ]),
                
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmar contraseña')
                    ->helperText('Confirme su contraseña.')
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->dehydrated(false)
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->maxLength(255)
                    ->autocomplete('new-password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label("Avatar")
                    ->getStateUsing(function ($record) {
                        return $record->getFilamentAvatarUrl();
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('user')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->color('primary')
                    ->tooltip('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->color('danger')
                    ->tooltip('Eliminar'),
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
