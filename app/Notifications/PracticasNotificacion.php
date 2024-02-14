<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PracticasNotificacion extends Notification
{
    use Queueable;

    protected $nombre;
    protected $estudiante;
    protected $carrera;

    /**
     * Create a new notification instance.
     * @param $nombre
     */
    public function __construct($nombre, $estudiante, $carrera)
    {
        $this->nombre = $nombre;
        $this->estudiante = $estudiante;
        $this->carrera = $carrera;
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
            ->greeting('UNIVERSIDAD IBEROAMERICANA DEL ECUADOR')
            ->line($this->nombre)
            ->line("El estudiante {$this->estudiante} de la carrera de {$this->carrera} ha enviado la solicitud de  prácticas pre profesionales")
            ->line('Por favor revisa en tu panel de representante para aprobar o rechazar la solicitud')
            ->action('Ver solicitud', url('/'))
            ->line('Agradecemos tu respuesta!');
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
