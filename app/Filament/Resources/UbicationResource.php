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
    protected static ?string $model = Ubication::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Ubicaciones';
    protected static ?string $modelLabel = 'Ubicación';
    protected static ?string $pluralModelLabel = 'Ubicaciones';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Numero de ubicaciones';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
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
                    ->tooltip('Eliminar')
                    ->before(function (Tables\Actions\DeleteAction $action, Ubication $record) {
                        if ($record->properties()->exists() || $record->lands()->exists()) {
                            Notification::make()
                                ->title('No se puede eliminar la zona')
                                ->body('La zona tiene propiedades o terrenos asignados.')
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),
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
            'index' => Pages\ListUbications::route('/'),
            'create' => Pages\CreateUbication::route('/create'),
            'edit' => Pages\EditUbication::route('/{record}/edit'),
        ];
    }
}
