<?php

namespace App\Mail;

use App\Models\Pedidos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VentaComprobante extends Mailable
{
    use Queueable, SerializesModels;

    // Recibimos el pedido en el constructor
    public function __construct(public Pedidos $pedido) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Venta #'.$this->pedido->id,
        );
    }

    public function content(): Content
    {
        // Puedes crear una vista simple para el cuerpo del correo (ej: emails.venta)
        // O usar text() si quieres algo muy básico.
        return new Content(
            view: 'emails.comprobante-venta', // <--- Crea esta vista (Paso 2.1)
        );
    }

    public function attachments(): array
    {
        // Generamos el PDF aquí mismo usando la misma vista que tenías ('pdf.boleta')
        return [
            Attachment::fromData(fn () => Pdf::loadView('pdf.boleta', ['venta' => $this->pedido])->output(), "Comprobante-{$this->pedido->id}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
