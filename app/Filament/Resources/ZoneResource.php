<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZoneResource\Pages;
use App\Filament\Resources\ZoneResource\RelationManagers;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class ZoneResource extends Resource
{
    // The model associated with this resource (Zone model)
    protected static ?string $model = Zone::class;

    // The icon to represent the Zone resource in the navigation
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    // The sorting order for the Zone resource in the navigation
    protected static ?int $navigationSort = 4;

    // The group to which the Zone resource belongs in the navigation
    protected static ?string $navigationGroup = 'Zonas';

    // The singular label for this resource
    protected static ?string $modelLabel = 'Zona';

    // The plural label for this resource
    protected static ?string $pluralModelLabel = 'Zonas';

    // The badge that shows the number of records for the Zone resource
    public static function getNavigationBadge(): ?string
    {
        // Displays the total number of zones
        return static::getModel()::count();
    }

    // Tooltip for the navigation badge
    public static function getNavigationBadgeTooltip(): ?string
    {
        // Tooltip text that explains what the badge represents
        return 'Numero de zonas';  
    }

    // Defines the permission check for viewing a Zone record
    public static function canView($record): bool
    {
        // Only allows view access for Administrators, Editors, and Consultants
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor'
            || auth()->user()->role === 'Consultor';
    }

    // Defines the permission check for creating a Zone record
    public static function canCreate(): bool
    {
        // Only allows create access for Administrators and Editors
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Defines the permission check for editing a Zone record
    public static function canEdit($record): bool
    {
        // Only allows edit access for Administrators and Editors
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Defines the permission check for deleting a Zone record
    public static function canDelete($record): bool
    {
        // Only allows delete access for Administrators
        return auth()->user()->role === 'Administrador';
    }

    // Defines the form schema for creating or editing a Zone record
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // A text input for the 'name' field
                Forms\Components\TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->label("Nombre")
                    ->helperText('Nombre de la zona.')
                    ->required()
                    ->dehydrated(fn ($state) => filled($state))
                    ->validationMessages([
                        'unique' => 'La zona ya está registrada.',
                    ])
                    ->maxLength(255),
            ]);
    }

    // Defines the table configuration for displaying the Zone records
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
                    ->before(function (Tables\Actions\DeleteAction $action, Zone $record) {
                        // Check if there are any properties or lands related to the 'Zone'
                        if ($record->properties()->exists() || $record->lands()->exists()) {
                            // Display a notification if the 'Zone' cannot be deleted
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
            'index' => Pages\ListZones::route('/'),
            'create' => Pages\CreateZone::route('/create'),
            'edit' => Pages\EditZone::route('/{record}/edit'),
        ];
    }
}