<?php

namespace App\Filament\Resources;

use Filament\Tables\Actions\Action;
use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class PropertyResource extends Resource
{
    // This defines the model associated with this resource
    protected static ?string $model = Property::class;

    // This sets the navigation icon for the resource in the sidebar
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    // This defines the sort order of the resource in the navigation menu (ascending order)
    protected static ?int $navigationSort = 2;

    // This defines the group name for the resource in the navigation menu
    protected static ?string $navigationGroup = 'Propiedades';

    // This sets the singular label for the resource, used in actions like 'Create Property'
    protected static ?string $modelLabel = 'Propiedad';

    // This sets the plural label for the resource, used in lists and grids
    protected static ?string $pluralModelLabel = 'Propiedades';

    // Method to return the badge count for the navigation, showing the number of properties
    public static function getNavigationBadge(): ?string
    {
        // If the logged-in user is a "Consultor", only count properties that are active (status = true)
        if (auth()->user()->role === 'Consultor') {
            return static::getModel()::where('status', true)->count();
        }
        // For other users, return the total count of properties
        return static::getModel()::count();
    }

    // Method to return the tooltip text for the navigation badge
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de propiedades'; // Tooltip text shown when hovering over the badge
    }

    // Method to check if a user can view a property record
    public static function canView($record): bool
    {
        // Allow viewing for Administrators, Editors, and Consultants
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor'
            || auth()->user()->role === 'Consultor';
    }

    // Method to check if a user can create a property record
    public static function canCreate(): bool
    {
        // Only allow creation for Administrators and Editors
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Method to check if a user can edit a property record
    public static function canEdit($record): bool
    {
        // Only allow editing for Administrators and Editors
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Method to check if a user can delete a property record
    public static function canDelete($record): bool
    {
        // Only allow deletion for Administrators
        return auth()->user()->role === 'Administrador';
    }

    // Method to customize the Eloquent query for fetching properties based on the user's role
    public static function getEloquentQuery(): Builder
    {
        // Start with the default query defined in the parent class
        $query = parent::getEloquentQuery();

        // If the logged-in user is a "Consultor", limit the query to active properties (status = true)
        if (auth()->user()->role === 'Consultor') {
            $query->where('status', '=', true);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            // Define the form schema with different sections: required and optional fields
            ->schema([
                // "Campos obligatorios" fieldset for required fields
                Forms\Components\Fieldset::make('Campos obligatorios')
                ->schema([
                    // "Ubicación" field: Select input for choosing the location of the property
                    Forms\Components\Select::make('ubication_id')
                        ->label('Ubicación')
                        ->helperText('Selecciona la ubicación principal del inmueble.')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('ubication', 'name'),

                    // "Zona" field: Select input for choosing the zone within the location
                    Forms\Components\Select::make('zone_id')
                        ->label('Zona')
                        ->helperText('Selecciona la zona específica dentro de la ubicación.')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('zone', 'name'),

                    // "Desarrollo" field: Text input for the development or project name
                    Forms\Components\TextInput::make('development')
                        ->label('Desarrollo')
                        ->helperText('Nombre del desarrollo o proyecto inmobiliario.')
                        ->required()
                        ->maxLength(255),

                    // "Clasificación" field: Select input for property classification (Luxury, Premium, etc.)
                    Forms\Components\Select::make('classification')
                        ->label('Clasificación')
                        ->helperText('Clasificación del inmueble según su nivel de lujo.')
                        ->required()
                        ->options([
                            'Lujo' => 'Lujo',
                            'Premium' => 'Premium',
                            'Gama' => 'Gama Media',
                        ]),

                    // "Tipo" field: Select input for property type (House, Apartment, etc.)
                    Forms\Components\Select::make('type')
                        ->label('Tipo')
                        ->helperText('Tipo de inmueble (casa, departamento, etc.).')
                        ->required()
                        ->options([
                            'Casa' => 'Casa',
                            'Departamento' => 'Departamento',
                            'Oficina' => 'Oficina',
                            'Local' => 'Local',
                            'Hotel' => 'Hotel',
                            'Bodega' => 'Bodega',
                            'Penthouse' => 'Penthouse',
                        ]),

                    // "Descripción" field: Textarea for a detailed property description
                    Forms\Components\Textarea::make('description')
                        ->label('Descripción')
                        ->helperText('Descripción detallada del inmueble.')
                        ->required()
                        ->columnSpanFull(),

                    // "Precio" field: Numeric input for the property's price
                    Forms\Components\TextInput::make('price')
                        ->label('Precio')
                        ->helperText('Precio de venta o renta del inmueble.')
                        ->required()
                        ->numeric()
                        ->prefix('$'),

                    // "Moneda" field: Select input for the currency (MXN or USD)
                    Forms\Components\Select::make('currency')
                        ->label('Moneda')
                        ->helperText('Moneda en la que se expresa el precio.')
                        ->required()
                        ->options([
                            'MDD' => 'MDD',
                            'MDP' => 'MDP',
                        ]),

                    // "Área (m²)" field: Numeric input for the total area of the property
                    Forms\Components\TextInput::make('area_m2')
                        ->label('Área (m²)')
                        ->helperText('Área total del terreno o propiedad en metros cuadrados.')
                        ->required()
                        ->numeric(),

                    // "Construcción (m²)" field: Numeric input for the constructed area
                    Forms\Components\TextInput::make('contruction_m2')
                        ->label('Construcción (m²)')
                        ->helperText('Área construida del inmueble en metros cuadrados.')
                        ->required()
                        ->numeric(),

                    // "Habitaciones" field: Numeric input for the number of rooms in the property
                    Forms\Components\TextInput::make('rooms')
                        ->label('Habitaciones')
                        ->helperText('Número de habitaciones del inmueble.')
                        ->required()
                        ->numeric(),

                    // "Baños" field: Numeric input for the number of bathrooms in the property
                    Forms\Components\TextInput::make('bathrooms')
                        ->label('Baños')
                        ->helperText('Número de baños del inmueble.')
                        ->required()
                        ->numeric(),

                    // "Acepta Mascotas" field: Select input for pet-friendly status
                    Forms\Components\Select::make('pet_friendly')
                        ->label('Acepta Mascotas')
                        ->helperText('Indica si el inmueble acepta mascotas.')
                        ->required()
                        ->options([
                            'Si' => 'Si',
                            'No' => 'No',
                        ]),

                    // "Familiar" field: Select input for family type suitability
                    Forms\Components\Select::make('family')
                        ->label('Familiar')
                        ->helperText('Tipo de familia o persona ideal para el inmueble.')
                        ->required()
                        ->options([
                            'Infantes' => 'Infantes',
                            'Pareja-Mayor' => 'Pareja Mayor',
                            'Pareja-Joven' => 'Pareja Joven',
                            'Familiar' => 'Familiar',
                            'Una-Persona' => 'Una Persona',
                            'Negocio' => 'Negocio',
                        ]),

                    // "Vista" field: Select input for the view type from the property
                    Forms\Components\Select::make('view')
                        ->label('Vista')
                        ->helperText('Tipo de vista que ofrece el inmueble.')
                        ->required()
                        ->options([
                            'Carretera' => 'Carretera',
                            'Mar' => 'Mar',
                            'Selva' => 'Selva',
                            'Ciudad' => 'Ciudad',
                            'Costa' => 'Costa',
                        ]),

                    // "Operación" field: Select input for the type of operation (sale, rent, etc.)
                    Forms\Components\Select::make('operation')
                        ->label('Operación')
                        ->helperText('Tipo de operación (venta, renta, etc.).')
                        ->required()
                        ->options([
                            'Venta' => 'Venta',
                            'Renta' => 'Renta',
                            'Traspaso' => 'Traspaso',
                        ]),

                    // "Tipo de Contacto" field: Select input for the contact type (owner, broker, etc.)
                    Forms\Components\Select::make('contact_type')
                        ->label('Tipo de Contacto')
                        ->helperText('Tipo de contacto (propietario, broker, etc.).')
                        ->required()
                        ->options([
                            'Propietarios' => 'Propietarios',
                            'Broker' => 'Broker',
                        ]),

                    // "Contacto" field: Text input for the name of the contact person or entity
                    Forms\Components\TextInput::make('contact')
                        ->label('Contacto')
                        ->helperText('Nombre de la persona o entidad de contacto.')
                        ->required()
                        ->maxLength(255),

                    // "Datos de Contacto" field: Textarea for detailed contact information (phone, email, etc.)
                    Forms\Components\Textarea::make('contact_data')
                        ->label('Datos de Contacto')
                        ->helperText('Información de contacto (teléfono, correo, etc.).')
                        ->required()
                        ->columnSpanFull(),

                    // "Comisión" field: Numeric input for the commission percentage or amount
                    Forms\Components\TextInput::make('comission')
                        ->label('Comisión')
                        ->helperText('Porcentaje o monto de la comisión.')
                        ->required()
                        ->numeric(),

                    // "Renta Airbnb" field: Select input for indicating if the property is available for Airbnb rental
                    Forms\Components\Select::make('airbnb_rent')
                        ->label('Renta Airbnb')
                        ->helperText('Indica si el inmueble está disponible para renta en Airbnb.')
                        ->required()
                        ->options([
                            'Si' => 'Si',
                            'No' => 'No',
                        ]),
                ]),

                // "Campos opcionales" fieldset for optional fields
                Forms\Components\Fieldset::make('Campos opcionales')
                ->schema([
                    // "Precio por m²" field: Numeric input for price per square meter
                    Forms\Components\TextInput::make('price_m2')
                        ->label('Precio por m²')
                        ->helperText('Precio por metro cuadrado del inmueble.')
                        ->numeric(),

                    // "Amenidades" field: Textarea for additional amenities of the property
                    Forms\Components\Textarea::make('amenities')
                        ->label('Amenidades')
                        ->helperText('Lista de amenidades o características adicionales del inmueble.')
                        ->columnSpanFull(),

                    // "Mapa" field: Textarea for the embed map code of the property location
                    Forms\Components\Textarea::make('maps')
                        ->label('Mapa')
                        ->helperText('Código embed del mapa de ubicación.')
                        ->columnSpanFull(),

                    // "Contenido" field: Textarea for additional content or notes about the property
                    Forms\Components\Textarea::make('content')
                        ->label('Contenido')
                        ->helperText('Contenido adicional o notas sobre el inmueble.')
                        ->columnSpanFull(),

                    // "Documento PDF" field: File upload for adding a PDF document related to the property
                    Forms\Components\FileUpload::make('pdf')
                        ->label('Documento PDF')
                        ->helperText('Agregar PDF con información adicional.')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->visibility('public')
                        ->directory('property-pdf'),
                ]),

                // "Estado" field: Toggle to indicate whether the property is active or inactive
                Forms\Components\Toggle::make('status')
                    ->label('Estado')
                    ->helperText('Indica si el inmueble está activo o inactivo.')
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Define actions for the table header (like export options)
            ->headerActions([
                FilamentExportHeaderAction::make('Exportar')
                    ->disableAdditionalColumns()
                    ->disablePreview()
                    ->color('success'),
            ])
            // Button to toggle the columns visible in the table
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Columnas'),
            )
            // Define the columns for the table
            ->columns([
                // Column for 'Ubicación' with search and sort enabled
                Tables\Columns\TextColumn::make('ubication.name')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for 'Zona' with similar properties
                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zona')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for 'Desarrollo' with search, sort, and toggle visibility
                Tables\Columns\TextColumn::make('development')
                    ->label('Desarrollo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for 'Clasificación' with similar properties
                Tables\Columns\TextColumn::make('classification')
                    ->label('Clasificación')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for 'Tipo' with similar properties
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for 'Precio' formatted as currency with a prefix
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->searchable()
                    ->sortable()
                    ->money('MXN')
                    ->prefix('$')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for 'Moneda' with similar properties
                Tables\Columns\TextColumn::make('currency')
                    ->label('Moneda')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for 'Área (m²)' with search and sort enabled
                Tables\Columns\TextColumn::make('area_m2')
                    ->label('Área (m²)')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for 'Estado' (Status) displayed as boolean icon
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for construction area
                Tables\Columns\TextColumn::make('contruction_m2')
                    ->label('Construcción (m²)')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for price per square meter
                Tables\Columns\TextColumn::make('price_m2')
                    ->label('Precio (m²)')
                    ->searchable()
                    ->sortable()
                    ->money('MXN')
                    ->prefix('$')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for rooms
                Tables\Columns\TextColumn::make('rooms')
                    ->label('Habitaciones')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for bathrooms
                Tables\Columns\TextColumn::make('bathrooms')
                    ->label('Baños')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for pet friendly
                Tables\Columns\TextColumn::make('pet_friendly')
                    ->label('Apto para Mascotas')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for family
                Tables\Columns\TextColumn::make('family')
                    ->label('Familiar')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for view
                Tables\Columns\TextColumn::make('view')
                    ->label('Vista')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for operation
                Tables\Columns\TextColumn::make('operation')
                    ->label('Operación')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for contact name
                Tables\Columns\TextColumn::make('contact')
                    ->label('Contacto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for contact type
                Tables\Columns\TextColumn::make('contact_type')
                    ->label('Tipo de Contacto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for contact data
                Tables\Columns\TextColumn::make('contact_data')
                    ->label('Dato de contacto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for 'Comisión'
                Tables\Columns\TextColumn::make('comission')
                    ->label('Comisión')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for 'Renta Airbnb'
                Tables\Columns\TextColumn::make('airbnb_rent')
                    ->label('Renta Airbnb')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Timestamps for record creation
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Timestamps for record update
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // Define any table filters (none in this case)
            ->filters([
                //
            ])
            // Define actions for individual records (like view, edit, delete)
            ->actions([
                // Action to download PDF if the record has a PDF file
                Action::make('pdf')
                    ->label('')
                    ->tooltip('PDF') 
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => $record->pdf ? Storage::disk('public')->url($record->pdf) : '#')
                    ->openUrlInNewTab()
                    ->color('success')
                    ->hidden(fn ($record) => !$record->pdf),

                // Action to view the record
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->color('warning')
                    ->tooltip('Visualizar'),

                // Action to edit the record
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->color('primary')
                    ->tooltip('Editar'),

                // Action to delete the record
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->color('danger')
                    ->tooltip('Eliminar'),
            ])
            // Define bulk actions for the table (like export for multiple records)
            ->bulkActions([
                // Export action for selected records
                FilamentExportBulkAction::make('Exportar')
                    ->disableAdditionalColumns()
                    ->disablePreview()
                    ->color('success'),
            ]);
    }

    // Define relations for the model (empty for now)
    public static function getRelations(): array
    {
        return [
            // Add relations here
        ];
    }

    // Define pages for creating, editing, and listing the records
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

}
