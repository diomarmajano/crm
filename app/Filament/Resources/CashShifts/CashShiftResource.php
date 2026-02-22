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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
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
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),

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
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('user.name')
                    ->label('Cajero'),
                BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'abierta',
                        'danger' => 'cerrada',
                    ]),
                TextColumn::make('saldo_esperado')
                    ->label('Efectivo Esperado')
                    ->money('clp')
                    ->color('success')
                    ->weight('bold'),
                TextColumn::make('diferencia')
                    ->label('Descuadre')
                    ->money('clp')
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
                    // Solo se puede registrar gastos si la caja está abierta
                    ->visible(fn (CashShift $record) => $record->estado === 'abierta')
                    ->form([
                        TextInput::make('monto')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('concepto')
                            ->required()
                            ->placeholder('Ej: Compra de agua, Pago proveedor...'),
                    ])
                    ->action(function (CashShift $record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            // 1. Crear el movimiento
                            CashMovement::create([
                                'cash_shift_id' => $record->id,
                                'tenant_id' => $record->tenant_id,
                                'user_id' => auth()->id(),
                                'tipo' => 'egreso',
                                'metodo_pago' => 'efectivo',
                                'monto' => $data['monto'],
                                'concepto' => $data['concepto'],
                            ]);

                            // 2. Actualizar totales de la caja
                            $record->update([
                                'total_egresos' => $record->total_egresos + $data['monto'],
                                'saldo_esperado' => $record->saldo_esperado - $data['monto'],
                            ]);
                        });
                        Notification::make()->title('Gasto registrado')->success()->send();
                    }),

                // ACCIÓN 2: CERRAR CAJA (EL ARQUEO)
                Action::make('cerrar_caja')
                    ->label('Cerrar Caja')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->visible(fn (CashShift $record) => $record->estado === 'abierta')
                    ->form([
                        Placeholder::make('resumen')
                            ->content(fn (CashShift $record) => 'El sistema espera encontrar: $'.number_format($record->saldo_esperado, 0, ',', '.')),
                        TextInput::make('saldo_fisico')
                            ->label('¿Cuánto efectivo hay realmente en la caja?')
                            ->required()
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
                    ->color('info'), // Para ver el historial (RelationManager)
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
