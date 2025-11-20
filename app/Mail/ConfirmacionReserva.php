<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ConfirmacionReserva extends Mailable
{
    use Queueable,SerializesModels;

    public $data;

    public function __construct( array $data) 
    {
        $this-> data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('reservas@example.com', 'Sistema de Reservas'),
            subject: 'Confirmación de Reserva'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.confirmacion_reserva',
            with: ['data' => $this->data]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
