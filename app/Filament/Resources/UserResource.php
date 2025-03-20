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
    // The model associated with this resource (User model)
    protected static ?string $model = User::class;

    // The icon to represent the User resource in the navigation
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // The sorting order for the User resource in the navigation
    protected static ?int $navigationSort = 1;

    // The group to which the User resource belongs in the navigation
    protected static ?string $navigationGroup = 'Usuarios';

    // The singular label for this resource
    protected static ?string $modelLabel = 'Usuario';

    // The plural label for this resource
    protected static ?string $pluralModelLabel = 'Usuarios';

    // The badge that shows the number of records for the User resource
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();  // Displays the total number of users
    }

    // Tooltip for the navigation badge
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de usuarios';  // Tooltip text that explains what the badge represents
    }

    // Defines if the current user has access to the User resource (only Administrators)
    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'Administrador';  // Only allows access for Administrators
    }

    // Defines the permission check for viewing a User record
    public static function canView($record): bool
    {
        // Allows viewing for Administrators, Editors, and Consultants
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor'
            || auth()->user()->role === 'Consultor';
    }

    // Defines the permission check for creating a User record
    public static function canCreate(): bool
    {
        // Allows creating users for Administrators and Editors
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Defines the permission check for editing a User record
    public static function canEdit($record): bool
    {
        // Allows editing for Administrators and Editors
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Defines the permission check for deleting a User record
    public static function canDelete($record): bool
    {
        // Prevents deleting the user with the 'Administrador' role
        if ($record->name === 'Administrador') {
            return false;  // Can't delete the administrator user
        }
        // Allows deleting for Administrators only
        return auth()->user()->role === 'Administrador';
    }

    // Custom Eloquent query to filter records based on user roles
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();  // Get the base query

        // If the current user is an Administrator, no restrictions are applied
        if (auth()->user()->name === 'Administrador') {
            return $query;
        }

        // Otherwise, exclude users with the 'Administrador' role
        return $query->where('role', '!=', "Administrador");
    }

    public static function form(Form $form): Form
    {
        // Check if the user is an "Administrador"
        $isAdministrador = $form->getRecord()?->name === 'Administrador';

        // Define an array to hold the form fields
        $formFields = [
            // Field for the user's full name
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->helperText('Nombre completo.')
                ->required()
                ->dehydrated(fn ($state) => filled($state))
                ->maxLength(255),

            // Field for the user's username (email or unique identifier)
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

            // If the user is not an "Administrador", allow selecting a role
            ! $isAdministrador ? Forms\Components\Select::make('role')
                ->label('Rol')
                ->helperText('Rol del usuario.')
                ->required()
                ->dehydrated(fn ($state) => filled($state))
                ->options([
                    'Administrador' => 'Administrador',
                    'Editor' => 'Editor',
                    'Consultor' => 'Consultor',
                ]) : null,

            // Field for selecting the user's avatar (with a selection of images)
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

            // Field for setting a new password (only required when creating a user)
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

            // Field for confirming the new password (only required when creating a user)
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
        ];

        // Return the form schema, removing any null values
        return $form->schema(array_filter($formFields));
    }

    public static function table(Table $table): Table
    {
        // Define the columns for the table
        return $table
            ->columns([
                // Avatar column, showing the user's avatar image
                Tables\Columns\ImageColumn::make('avatar')
                    ->label("Avatar")
                    ->getStateUsing(function ($record) {
                        return $record->getFilamentAvatarUrl();
                    })
                    ->toggleable(),

                // Name column, displaying the user's full name
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // User column, displaying the username
                Tables\Columns\TextColumn::make('user')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Role column, displaying the user's role
                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Created at column, displaying the user's creation timestamp
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Updated at column, displaying the user's last update timestamp
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->searchable() 
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filters here
            ])
            ->actions([
                // Edit action, shown as a button with a primary color and tooltip
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->color('primary')
                    ->tooltip('Editar'),

                // Delete action, shown as a button with a danger color and tooltip
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->color('danger')
                    ->tooltip('Eliminar'),
            ])
            ->bulkActions([
                // Add bulk actions here
            ]);
    }

    public static function getRelations(): array
    {
        // Return any related resources (currently none)
        return [
            // Add relations here
        ];
    }

    public static function getPages(): array
    {
        // Define the pages for this resource
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
