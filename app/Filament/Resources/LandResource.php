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
    protected static ?string $model = Land::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Terrenos';
    protected static ?string $modelLabel = 'Terreno';
    protected static ?string $pluralModelLabel = 'Terrenos';

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->role === 'Consultor') {
            return static::getModel()::where('status', true)->count();
        }
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de terrenos';
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
                        ->helperText('Selecciona la ubicación principal del terreno.')
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
                    Forms\Components\Textarea::make('description')
                        ->label("Descripción")
                        ->helperText('Descripción detallada del terreno.')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('price')
                        ->label('Precio')
                        ->helperText('Precio de venta o renta del inmueble.')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                    Forms\Components\Select::make('currency')
                        ->label("Moneda")
                        ->helperText('Moneda en la que se expresa el precio.')
                        ->required()
                        ->options([
                            "MDD" => "MDD",
                            "MDP" => "MDP",
                        ]),
                    Forms\Components\TextInput::make('area')
                        ->label("Área")
                        ->helperText('Área total del terreno en metros cuadrados.')
                        ->required()
                        ->numeric()
                        ->inputMode('decimal'),
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
                    Forms\Components\Select::make('operation')
                        ->label("Operación")
                        ->helperText('Tipo de operación (venta, renta, etc.).')
                        ->required()
                        ->options([
                            "Venta" => "Venta",
                            "Renta" => "Renta",
                            "Traspaso" => "Traspaso",
                        ]),
                    Forms\Components\Select::make('contact_type')
                        ->label("Tipo de contacto")
                        ->helperText('Tipo de contacto (propietario, broker, etc.).')
                        ->required()
                        ->options([
                            "Propietarios" => "Propietarios",
                            "Broker" => "Broker",
                        ]),
                    Forms\Components\TextInput::make('contact')
                        ->label("Contacto")
                        ->helperText('Nombre de la persona o entidad de contacto.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('contact_data')
                        ->label("Datos de contacto")
                        ->helperText('Información de contacto (teléfono, correo, etc.).')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('comission')
                        ->label("Comisión")
                        ->helperText('Porcentaje o monto de la comisión.')
                        ->required()
                        ->numeric()
                        ->inputMode('decimal'),
                ]),
                Forms\Components\Fieldset::make('Campos opcionales')
                    ->schema([
                        Forms\Components\TextInput::make('front')
                        ->label("Frente")
                        ->helperText('Medida del frente del terreno en metros.')
                        ->numeric()
                        ->inputMode('decimal'),
                    Forms\Components\TextInput::make('bottom')
                        ->label("Fondo")
                        ->helperText('Medida del fondo del terreno en metros.')
                        ->numeric()
                        ->inputMode('decimal'),
                    Forms\Components\TextInput::make('density')
                        ->label("Densidad")
                        ->helperText('Densidad permitida en la zona del terreno.')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('soil')
                        ->label("Suelo")
                        ->helperText('Uso de suelo del terreno.')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('maps')
                        ->label("Mapas")
                        ->helperText('Código embed del mapa de ubicación del terreno.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('content')
                        ->label("Contenido")
                        ->helperText('Contenido adicional o notas sobre el terreno.')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('pdf')
                        ->label('Documento PDF')
                        ->helperText('Agregar PDF con información adicional.')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->visibility('public')
                        ->directory('land-pdf'),
                ]),
                Forms\Components\Toggle::make('status')
                    ->label("Estado")
                    ->default(1)
                    ->helperText('Indica si el terreno está activo o inactivo.')
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
                Tables\Columns\TextColumn::make('classification')
                    ->label('Clasificación')
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
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('area')
                    ->label('Área')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('front')
                    ->label('Frente')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bottom')
                    ->label('Fondo')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('density')
                    ->label('Densidad')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('soil')
                    ->label('Suelo')
                    ->searchable()
                    ->sortable()
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
            'index' => Pages\ListLands::route('/'),
            'create' => Pages\CreateLand::route('/create'),
            'edit' => Pages\EditLand::route('/{record}/edit'),
        ];
    }
}
