<?php

namespace App\Filament\Pages;

use App\Models\Tenant;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Support\Icons\Heroicon;

class ConfiguracionTenant extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingCart;

    protected static ?string $navigationLabel = 'Ajustes del Sistema';

    protected static ?string $title = 'Configuraciones Generales';

    protected string $view = 'filament.pages.configuracion-tenant';

    public ?array $data = [];

    public function mount(): void
    {
        // Aquí obtienes el tenant del usuario autenticado.
        // Ajústalo según cómo relaciones a tus usuarios con el tenant.
        $tenant = auth()->user()->tenant;

        // Llenamos el formulario con los datos actuales del tenant,
        // incluyendo la columna JSON 'settings'
        $this->form->fill($tenant->toArray());
    }

    public function form($form)
    {
        return $form
            ->schema([
                ComponentsSection::make('Alertas y Notificaciones')
                    ->description('Personaliza cómo el sistema te avisa de eventos importantes.')
                    ->schema([
                        TextInput::make('settings.dias_alerta_vencimiento')
                            ->label('Días de alerta antes de vencer')
                            ->numeric()
                            ->default(30)
                            ->required()
                            ->helperText('¿Cuántos días antes del vencimiento deseas ver la alerta?'),

                        // Puedes agregar más configuraciones aquí en el futuro
                        // TextInput::make('settings.prefijo_boleta')...
                    ]),
            ])
            ->statePath('data'); // IMPORTANTE: le decimos a Filament que guarde el estado en el array $data
    }

    public function guardar(): void
    {
        // Validamos y obtenemos el estado del formulario
        $datosFormulario = $this->form->getState();

        // Obtenemos el tenant actual
        $tenant = auth()->user()->tenant;

        // Actualizamos el registro en la base de datos
        $tenant->update($datosFormulario);

        // Mostramos una notificación de éxito nativa de Filament
        Notification::make()
            ->success()
            ->title('Configuraciones guardadas')
            ->send();
    }
}
