<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UbicationResource\Pages;
use App\Filament\Resources\UbicationResource\RelationManagers;
use App\Models\Ubication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class UbicationResource extends Resource
{
    // Defines the model associated with this resource (Ubication model in this case)
    protected static ?string $model = Ubication::class;

    // Defines the icon to be used in the navigation menu for this resource
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    // Defines the sorting order in the navigation menu (4 means it's the fourth item)
    protected static ?int $navigationSort = 4;

    // Defines the group in the navigation menu where this resource will appear
    protected static ?string $navigationGroup = 'Ubicaciones';

    // Defines the singular label used for this resource (e.g., 'Ubicación')
    protected static ?string $modelLabel = 'Ubicación';

    // Defines the plural label for this resource (e.g., 'Ubicaciones')
    protected static ?string $pluralModelLabel = 'Ubicaciones';

    // Method to define the badge that will show the count of items for this resource in the navigation
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();  // Returns the count of Ubication records
    }

    // Method to define the tooltip for the navigation badge
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de ubicaciones';  // Tooltip that shows when hovering over the badge
    }

    // Method to determine if a user can view a particular record based on their role
    public static function canView($record): bool
    {
        // Only allows users with the 'Administrador', 'Editor', or 'Consultor' role to view
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor'
            || auth()->user()->role === 'Consultor';
    }

    // Method to determine if a user can create a new record based on their role
    public static function canCreate(): bool
    {
        // Only allows 'Administrador' or 'Editor' to create new records
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Method to determine if a user can edit a particular record based on their role
    public static function canEdit($record): bool
    {
        // Only allows 'Administrador' or 'Editor' to edit records
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Method to determine if a user can delete a particular record based on their role
    public static function canDelete($record): bool
    {
        // Only allows 'Administrador' to delete records
        return auth()->user()->role === 'Administrador';
    }

    // Method to define the form schema for creating or editing a resource
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Text input field for the 'name' attribute
                Forms\Components\TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->label("Nombre")
                    ->helperText('Nombre de la ubicacion.')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state))
                    ->validationMessages([
                        'unique' => 'La ubicación ya está registrada.', 
                    ])
                    ->maxLength(255),
            ]);
    }

    // Defines the table configuration for displaying the 'Ubication' resource
    public static function table(Table $table): Table
    {
        return $table
            // Defines the columns to be displayed in the table
            ->columns([
                // Column for the 'name' attribute
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for the 'created_at' timestamp
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for the 'updated_at' timestamp
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // Defines the filters for the table (none in this case)
            ->filters([
                // Add filters here
            ])
            // Defines the actions available for each row
            ->actions([
                // Edit action for each row
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->color('primary')
                    ->tooltip('Editar'),

                // Delete action for each row
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->color('danger')
                    ->tooltip('Eliminar')
                    // Custom behavior before deleting
                    ->before(function (Tables\Actions\DeleteAction $action, Ubication $record) {
                        // Check if there are any properties or lands related to the 'Ubication'
                        if ($record->properties()->exists() || $record->lands()->exists()) {
                            // Display a notification if the 'Ubication' cannot be deleted
                            Notification::make()
                                ->title('No se puede eliminar la zona')
                                ->body('La zona tiene propiedades o terrenos asignados.')
                                ->danger()
                                ->send();
                            // Cancel the delete action
                            $action->cancel();
                        }
                    }),
            ])
            // Defines the bulk actions (none in this case)
            ->bulkActions([
                // Add bulk actions here
            ]);
    }

    // Method to define the relations for this resource (none in this case)
    public static function getRelations(): array
    {
        return [
            // Add relations here
        ];
    }

    // Method to define the pages for this resource (List, Create, and Edit)
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUbications::route('/'),
            'create' => Pages\CreateUbication::route('/create'),
            'edit' => Pages\EditUbication::route('/{record}/edit'),
        ];
    }
}
