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
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Propiedades';
    protected static ?string $modelLabel = 'Propiedad';
    protected static ?string $pluralModelLabel = 'Propiedades';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de propiedades';
    }

    public static function canView($record): bool
    {
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor'
            || auth()->user()->role === 'Consultor';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->role === 'Administrador' 
            || auth()->user()->role === 'Editor';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'Administrador';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role === 'Consultor') {
            $query->where('status', '=', true);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Campos obligatorios')
                ->schema([
                    Forms\Components\Select::make('ubication_id')
                        ->label('Ubicación')
                        ->helperText('Selecciona la ubicación principal del inmueble.')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('ubication', 'name'),
                    Forms\Components\Select::make('zone_id')
                        ->label('Zona')
                        ->helperText('Selecciona la zona específica dentro de la ubicación.')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('zone', 'name'),
                    Forms\Components\TextInput::make('development')
                        ->label('Desarrollo')
                        ->helperText('Nombre del desarrollo o proyecto inmobiliario.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('classification')
                        ->label('Clasificación')
                        ->helperText('Clasificación del inmueble según su nivel de lujo.')
                        ->required()
                        ->options([
                            'Lujo' => 'Lujo',
                            'Premium' => 'Premium',
                            'Gama' => 'Gama Media',
                        ]),
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
                    Forms\Components\Textarea::make('description')
                        ->label('Descripción')
                        ->helperText('Descripción detallada del inmueble.')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('price')
                        ->label('Precio')
                        ->helperText('Precio de venta o renta del inmueble.')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                    Forms\Components\Select::make('currency')
                        ->label('Moneda')
                        ->helperText('Moneda en la que se expresa el precio.')
                        ->required()
                        ->options([
                            'MDD' => 'MDD',
                            'MDP' => 'MDP',
                        ]),
                    Forms\Components\TextInput::make('area_m2')
                        ->label('Área (m²)')
                        ->helperText('Área total del terreno o propiedad en metros cuadrados.')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('contruction_m2')
                        ->label('Construcción (m²)')
                        ->helperText('Área construida del inmueble en metros cuadrados.')
                        ->required()
                        ->numeric(),
                        Forms\Components\TextInput::make('rooms')
                        ->label('Habitaciones')
                        ->helperText('Número de habitaciones del inmueble.')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('bathrooms')
                        ->label('Baños')
                        ->helperText('Número de baños del inmueble.')
                        ->required()
                        ->numeric(),
                        Forms\Components\Select::make('pet_friendly')
                        ->label('Acepta Mascotas')
                        ->helperText('Indica si el inmueble acepta mascotas.')
                        ->required()
                        ->options([
                            'Si' => 'Si',
                            'No' => 'No',
                        ]),
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
                    Forms\Components\Select::make('operation')
                        ->label('Operación')
                        ->helperText('Tipo de operación (venta, renta, etc.).')
                        ->required()
                        ->options([
                            'Venta' => 'Venta',
                            'Renta' => 'Renta',
                            'Traspaso' => 'Traspaso',
                        ]),
                    Forms\Components\Select::make('contact_type')
                        ->label('Tipo de Contacto')
                        ->helperText('Tipo de contacto (propietario, broker, etc.).')
                        ->required()
                        ->options([
                            'Propietarios' => 'Propietarios',
                            'Broker' => 'Broker',
                        ]),
                    Forms\Components\TextInput::make('contact')
                        ->label('Contacto')
                        ->helperText('Nombre de la persona o entidad de contacto.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('contact_data')
                        ->label('Datos de Contacto')
                        ->helperText('Información de contacto (teléfono, correo, etc.).')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('comission')
                        ->label('Comisión')
                        ->helperText('Porcentaje o monto de la comisión.')
                        ->required()
                        ->numeric(),
                    Forms\Components\Select::make('airbnb_rent')
                        ->label('Renta Airbnb')
                        ->helperText('Indica si el inmueble está disponible para renta en Airbnb.')
                        ->required()
                        ->options([
                            'Si' => 'Si',
                            'No' => 'No',
                        ]),
                ]),
                Forms\Components\Fieldset::make('Campos opcionales')
                ->schema([
                    Forms\Components\TextInput::make('price_m2')
                        ->label('Precio por m²')
                        ->helperText('Precio por metro cuadrado del inmueble.')
                        ->numeric(),
                    Forms\Components\Textarea::make('amenities')
                        ->label('Amenidades')
                        ->helperText('Lista de amenidades o características adicionales del inmueble.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('maps')
                        ->label('Mapa')
                        ->helperText('Código embed del mapa de ubicación.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('content')
                        ->label('Contenido')
                        ->helperText('Contenido adicional o notas sobre el inmueble.')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('pdf')
                        ->label('Documento PDF')
                        ->helperText('Agregar PDF con información adicional.')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->visibility('public')
                        ->directory('property-pdf'),
                ]),
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
            ->headerActions([
                FilamentExportHeaderAction::make('Exportar')
                    ->disableAdditionalColumns()
                    ->disablePreview()
                    ->color('success')
            ])
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Columnas'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('ubication.name')
                    ->label('Ubicación')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zona')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('development')
                    ->label('Desarrollo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('classification')
                    ->label('Clasificación')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->searchable()
                    ->sortable()
                    ->money('MXN')
                    ->prefix('$')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Moneda')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('area_m2')
                    ->label('Área (m²)')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('contruction_m2')
                    ->label('Construcción (m²)')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price_m2')
                    ->label('Precio (m²)')
                    ->searchable()
                    ->sortable()
                    ->money('MXN')
                    ->prefix('$')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rooms')
                    ->label('Habitaciones')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bathrooms')
                    ->label('Baños')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pet_friendly')
                    ->label('Apto para Mascotas')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('family')
                    ->label('Familiar')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('view')
                    ->label('Vista')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('operation')
                    ->label('Operación')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('contact')
                    ->label('Contacto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('contact_type')
                    ->label('Tipo de Contacto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('comission')
                    ->label('Comisión')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('airbnb_rent')
                    ->label('Renta Airbnb')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('pdf')
                    ->label('')
                    ->tooltip('PDF')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => $record->pdf ? Storage::disk('public')->url($record->pdf) : '#')
                    ->openUrlInNewTab()
                    ->color('success')
                    ->hidden(fn ($record) => !$record->pdf),
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->color('warning')
                    ->tooltip('Visualizar'),
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
                FilamentExportBulkAction::make('Exportar')
                    ->disableAdditionalColumns()
                    ->disablePreview()
                    ->color('success'),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
