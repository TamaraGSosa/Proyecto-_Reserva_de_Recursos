<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ConfirmacionUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('reservas@miapp.com', 'Sistema de Reservas'),
            subject: 'Confirmación de Registro de Usuario'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.confirmacion_usuario',
            with: ['user' => $this->user]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}