<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionCompromisoOrganizacion extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($nombre, $nombre_representante)
    {
        $this->nombre = $nombre;
        $this->nombre_representante = $nombre_representante;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Universidad Iberoamericana del Ecuador - Prácticas Prepofesionales')
            ->line("Felicidades! {$this->nombre}! Ya puedes asistir a tus prácticas preprofesionales.")
            ->line("Tu representante {$this->nombre_representante} te ha enviado el documento de compromiso, donde puedes conoces cuales son tus objetivos, tareas y horarios.")
            ->line("")
            ->line("")
            ->line("Revisa el documento en tu perfil")
            ->action('Ver mis documentos', url('http://localhost:4200/app/student'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
