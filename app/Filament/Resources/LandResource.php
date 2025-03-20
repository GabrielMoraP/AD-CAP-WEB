<?php

namespace App\Filament\Resources;

use Filament\Tables\Actions\Action;
use App\Filament\Resources\LandResource\Pages;
use App\Filament\Resources\LandResource\RelationManagers;
use App\Models\Land;
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

class LandResource extends Resource
{
    // The model that this resource is associated with (Land model)
    protected static ?string $model = Land::class;

    // The navigation icon to display in the sidebar for this resource
    protected static ?string $navigationIcon = 'heroicon-o-map';

    // The order in which this resource appears in the sidebar (3 means it will be after other resources with a lower sort value)
    protected static ?int $navigationSort = 3;

    // The group to which this resource belongs in the sidebar
    protected static ?string $navigationGroup = 'Terrenos';

    // The singular label for this resource (used in various places like form titles)
    protected static ?string $modelLabel = 'Terreno';

    // The plural label for this resource (used in lists and other places where multiple records are shown)
    protected static ?string $pluralModelLabel = 'Terrenos';

    // This method returns a badge showing the number of records in the navigation item
    // For a user with role "Consultor", only active records (status = true) will be counted
    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->role === 'Consultor') {
            // Count only active "Terreno" records for "Consultor"
            return static::getModel()::where('status', true)->count();
        }
        // Count all records for other roles
        return static::getModel()::count();
    }

    // Tooltip for the navigation badge that shows the count of records
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de terrenos';
    }

    // Determines if the current user can view the record
    // Users with the roles "Administrador", "Editor", or "Consultor" can view the record
    public static function canView($record): bool
    {
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor'
            || auth()->user()->role === 'Consultor';
    }

    // Determines if the current user can create a new record
    // Only "Administrador" and "Editor" roles can create records
    public static function canCreate(): bool
    {
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Determines if the current user can edit the record
    // Only "Administrador" and "Editor" roles can edit records
    public static function canEdit($record): bool
    {
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    // Determines if the current user can delete the record
    // Only "Administrador" can delete records
    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'Administrador';
    }

    // Customizes the Eloquent query to filter records based on the user's role
    // "Consultor" can only see active records (status = true)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role === 'Consultor') {
            // Filter to show only active records for "Consultor"
            $query->where('status', '=', true);
        }

        return $query;
    }

    // Method to define the form schema for the "Land" resource
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Fieldset for required fields (obligatory fields)
                Forms\Components\Fieldset::make('Campos obligatorios')
                    ->schema([
                        // Dropdown to select the location (ubication) of the land
                        Forms\Components\Select::make('ubication_id')
                            ->label('Ubicación')
                            ->helperText('Selecciona la ubicación principal del terreno.')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('ubication', 'name'),

                        // Dropdown to select the zone within the selected location
                        Forms\Components\Select::make('zone_id')
                            ->label('Zona')
                            ->helperText('Selecciona la zona específica dentro de la ubicación.')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('zone', 'name'),

                        // Dropdown to select the classification of the land
                        Forms\Components\Select::make('classification')
                            ->label("Clasificación")
                            ->helperText('Clasificación del terreno (residencial, comercial, etc.).')
                            ->required()
                            ->options([
                                "Residencial" => "Residencial",
                                "Unifamiliar" => "Unifamiliar",
                                "Industrial" => "Industrial",
                                "Comercial" => "Comercial",
                            ]),

                        // Textarea for the description of the land
                        Forms\Components\Textarea::make('description')
                            ->label("Descripción")
                            ->helperText('Descripción detallada del terreno.')
                            ->required()
                            ->columnSpanFull(),

                        // Text input for the price of the land
                        Forms\Components\TextInput::make('price')
                            ->label('Precio')
                            ->helperText('Precio de venta o renta del inmueble.')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        // Dropdown to select the currency in which the price is expressed
                        Forms\Components\Select::make('currency')
                            ->label("Moneda")
                            ->helperText('Moneda en la que se expresa el precio.')
                            ->required()
                            ->options([
                                "MDD" => "MDD",
                                "MDP" => "MDP",
                            ]),

                        // Text input for the total area of the land in square meters
                        Forms\Components\TextInput::make('area')
                            ->label("Área")
                            ->helperText('Área total del terreno en metros cuadrados.')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal'),

                        // Dropdown for selecting the type of view the land offers
                        Forms\Components\Select::make('view')
                            ->label("Vista")
                            ->helperText('Tipo de vista que ofrece el terreno.')
                            ->required()
                            ->options([
                                "Carretera" => "Carretera",
                                "Mar" => "Mar",
                                "Selva" => "Selva",
                                "Ciudad" => "Ciudad",
                                "Costa" => "Costa",
                            ]),

                        // Dropdown for selecting the operation type (sale, rent, etc.)
                        Forms\Components\Select::make('operation')
                            ->label("Operación")
                            ->helperText('Tipo de operación (venta, renta, etc.).')
                            ->required()
                            ->options([
                                "Venta" => "Venta",
                                "Renta" => "Renta",
                                "Traspaso" => "Traspaso",
                            ]),

                        // Dropdown for selecting the type of contact (owner, broker, etc.)
                        Forms\Components\Select::make('contact_type')
                            ->label("Tipo de contacto")
                            ->helperText('Tipo de contacto (propietario, broker, etc.).')
                            ->required()
                            ->options([
                                "Propietarios" => "Propietarios",
                                "Broker" => "Broker",
                            ]),

                        // Text input for the name of the contact person or entity
                        Forms\Components\TextInput::make('contact')
                            ->label("Contacto")
                            ->helperText('Nombre de la persona o entidad de contacto.')
                            ->required()
                            ->maxLength(255),

                        // Textarea for contact data (phone, email, etc.)
                        Forms\Components\Textarea::make('contact_data')
                            ->label("Datos de contacto")
                            ->helperText('Información de contacto (teléfono, correo, etc.).')
                            ->required()
                            ->columnSpanFull(),

                        // Text input for commission percentage or amount
                        Forms\Components\TextInput::make('comission')
                            ->label("Comisión")
                            ->helperText('Porcentaje o monto de la comisión.')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal'),
                    ]),

                // Fieldset for optional fields
                Forms\Components\Fieldset::make('Campos opcionales')
                    ->schema([
                        // Text input for the front measurement of the land
                        Forms\Components\TextInput::make('front')
                            ->label("Frente")
                            ->helperText('Medida del frente del terreno en metros.')
                            ->numeric()
                            ->inputMode('decimal'),

                        // Text input for the depth (bottom) measurement of the land
                        Forms\Components\TextInput::make('bottom')
                            ->label("Fondo")
                            ->helperText('Medida del fondo del terreno en metros.')
                            ->numeric()
                            ->inputMode('decimal'),

                        // Text input for the density allowed in the land zone
                        Forms\Components\TextInput::make('density')
                            ->label("Densidad")
                            ->helperText('Densidad permitida en la zona del terreno.')
                            ->maxLength(255),

                        // Text input for the land's soil usage
                        Forms\Components\TextInput::make('soil')
                            ->label("Suelo")
                            ->helperText('Uso de suelo del terreno.')
                            ->maxLength(255),

                        // Textarea for embedding the map code of the land's location
                        Forms\Components\Textarea::make('maps')
                            ->label("Mapas")
                            ->helperText('Código embed del mapa de ubicación del terreno.')
                            ->columnSpanFull(),

                        // Textarea for additional notes or content about the land
                        Forms\Components\Textarea::make('content')
                            ->label("Contenido")
                            ->helperText('Contenido adicional o notas sobre el terreno.')
                            ->columnSpanFull(),

                        // File upload for a PDF document with additional information
                        Forms\Components\FileUpload::make('pdf')
                            ->label('Documento PDF')
                            ->helperText('Agregar PDF con información adicional.')
                            ->acceptedFileTypes(['application/pdf']) // Accept only PDF files
                            ->maxSize(10240) // Maximum size of the file in kilobytes
                            ->downloadable() // Allow file to be downloaded
                            ->visibility('public') // Make the file publicly accessible
                            ->directory('land-pdf'), // Store the PDF in the 'land-pdf' directory
                    ]),

                // Toggle switch for the active status of the land
                Forms\Components\Toggle::make('status')
                    ->label("Estado")
                    ->default(1) // Default to active (1)
                    ->helperText('Indica si el terreno está activo o inactivo.')
                    ->required(), // Make this field mandatory
            ]);
    }

    // Method to define the table schema for the "Land" resource
    public static function table(Table $table): Table
    {
        return $table
            // Header actions: Actions available at the top of the table
            ->headerActions([
                // Export button that will allow exporting data
                FilamentExportHeaderAction::make('Exportar')
                    ->disableAdditionalColumns()
                    ->disablePreview()
                    ->color('success'),
            ])
            
            // Action to toggle visibility of table columns
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Columnas'),
            )

            // Define the columns for the table
            ->columns([
                // Column for the "Ubicación" (location) field, displaying its name
                Tables\Columns\TextColumn::make('ubication.name')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                // Column for the "Zona" (zone) field, displaying its name
                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zona')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for the "Clasificación" (classification) of the land
                Tables\Columns\TextColumn::make('classification')
                    ->label('Clasificación')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for displaying the price of the land
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->searchable()
                    ->sortable()
                    ->money('MXN')
                    ->prefix('$')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for the currency in which the price is expressed
                Tables\Columns\TextColumn::make('currency')
                    ->label('Moneda')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for displaying the status (active/inactive) of the land
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Column for the area of the land (in square meters)
                Tables\Columns\TextColumn::make('area')
                    ->label('Área')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for front
                Tables\Columns\TextColumn::make('front')
                    ->label('Frente')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column bottom
                Tables\Columns\TextColumn::make('bottom')
                    ->label('Fondo')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for density
                Tables\Columns\TextColumn::make('density')
                    ->label('Densidad')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Column for soil
                Tables\Columns\TextColumn::make('soil')
                    ->label('Suelo')
                    ->searchable()
                    ->sortable()
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

                // Column for contact data
                Tables\Columns\TextColumn::make('comission')
                    ->label('Comisión')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Columns for displaying creation timestamp
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Columns for displaying update timestamp
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // Define table filters (empty for now)
            ->filters([
                // Add filters here
            ])

            // Define actions available for each record in the table
            ->actions([
                // Action to download PDF if the record has a PDF file
                Action::make('pdf')
                    ->label('') // No label
                    ->tooltip('PDF') // Tooltip text for the action
                    ->icon('heroicon-o-document-text') // PDF icon
                    ->url(fn ($record) => $record->pdf ? Storage::disk('public')->url($record->pdf) : '#') // Link to the PDF file
                    ->openUrlInNewTab() // Open the PDF in a new tab
                    ->color('success') // Set button color to green (success)
                    ->hidden(fn ($record) => !$record->pdf), // Only show if the record has a PDF

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

            // Define bulk actions (actions applied to multiple selected records)
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
            'index' => Pages\ListLands::route('/'),
            'create' => Pages\CreateLand::route('/create'),
            'edit' => Pages\EditLand::route('/{record}/edit'),
        ];
    }
}
