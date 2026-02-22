<?php

namespace App\Filament\Resources\CashShifts;

use App\Filament\Resources\CashShifts\Pages\ManageCashShifts;
use App\Filament\Resources\CashShifts\RelationManagers\MovementsRelationManager;
use App\Models\CashMovement;
use App\Models\CashShift;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class CashShiftResource extends Resource
{
    protected static ?string $model = CashShift::class;

    protected static ?string $navigationLabel = 'Control de Caja';

    protected static ?string $modelLabel = 'Turno de Caja';

    protected static ?string $pluralModelLabel = 'Turnos de Caja';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('saldo_inicial')
                    ->label('Efectivo Inicial en Caja (Fondo)')
                    ->required()
                    ->prefix('$')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric(),

                Textarea::make('observaciones')
                    ->label('Observaciones (Opcional)')
                    ->columnSpanFull(),

                Hidden::make('user_id')
                    ->default(auth()->id()),
                Hidden::make('tenant_id')
                    ->default(auth()->user()?->tenant_id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha_apertura')
                    ->label('Apertura')
                    ->dateTime('d/m/Y H:i')
                    ->icon(Heroicon::Clock)
                    ->iconColor('primary'),

                TextColumn::make('user.name')
                    ->label('Cajero')
                    ->icon(Heroicon::User)
                    ->iconColor('primary'),

                TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'success' => 'abierta',
                        'danger' => 'cerrada',
                    ])
                    ->icon(Heroicon::CheckBadge)
                    ->iconColor('primary'),

                TextColumn::make('saldo_esperado')
                    ->label('Efectivo Esperado')
                    ->money('clp')
                    ->weight('bold')
                    ->icon(Heroicon::CurrencyDollar)
                    ->iconColor('primary'),

                TextColumn::make('diferencia')
                    ->label('Descuadre')
                    ->money('clp')
                    ->icon(Heroicon::CurrencyDollar)
                    ->iconColor('primary')
                    ->color(fn ($state) => $state < 0 ? 'danger' : ($state > 0 ? 'warning' : 'success')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('registrar_gasto')
                    ->label('Registrar Gasto')
                    ->icon('heroicon-o-minus-circle')
                    ->color('warning')
                    ->visible(fn (CashShift $record) => $record->estado === 'abierta')
                    ->schema([
                        TextInput::make('monto')
                            ->required()
                            ->minValue(1)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric(),

                        TextInput::make('concepto')
                            ->required()
                            ->placeholder('Ej: Compra de agua, Pago proveedor...'),
                    ])
                    ->action(function (CashShift $record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            CashMovement::create([
                                'cash_shift_id' => $record->id,
                                'tenant_id' => $record->tenant_id,
                                'user_id' => auth()->id(),
                                'tipo' => 'egreso',
                                'metodo_pago' => 'efectivo',
                                'monto' => $data['monto'],
                                'concepto' => $data['concepto'],
                            ]);

                            $record->update([
                                'total_egresos' => $record->total_egresos + $data['monto'],
                                'saldo_esperado' => $record->saldo_esperado - $data['monto'],
                            ]);
                        });
                        Notification::make()->title('Gasto registrado')->success()->send();
                    }),

                Action::make('cerrar_caja')
                    ->label('Cerrar Caja')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->visible(fn (CashShift $record) => $record->estado === 'abierta')
                    ->schema([
                        TextEntry::make('resumen'),
                        TextInput::make('saldo_fisico')
                            ->label('¿Efectivo en caja?')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->action(function (CashShift $record, array $data) {
                        $saldoFisico = $data['saldo_fisico'];
                        $diferencia = $saldoFisico - $record->saldo_esperado;

                        $record->update([
                            'estado' => 'cerrada',
                            'fecha_cierre' => Carbon::now(),
                            'saldo_fisico' => $saldoFisico,
                            'diferencia' => $diferencia,
                        ]);

                        if ($diferencia < 0) {
                            Notification::make()->title('Caja cerrada con faltante de $'.number_format(abs($diferencia), 0, ',', '.'))->danger()->send();
                        } elseif ($diferencia > 0) {
                            Notification::make()->title('Caja cerrada con sobrante de $'.number_format($diferencia, 0, ',', '.'))->warning()->send();
                        } else {
                            Notification::make()->title('Caja cuadrada perfectamente')->success()->send();
                        }
                    }),

                ViewAction::make()
                    ->label('Ver Historial')
                    ->color('info'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MovementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCashShifts::route('/'),
            'view' => Pages\ViewCashShift::route('/{record}'),
        ];
    }
}
