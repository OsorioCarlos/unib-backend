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
            ->greeting('Universidad Iberoamericana del Ecuador - Prácticas Prepofesionales')
            ->line("Hola {$this->nombre}!")
            ->line("Tienes una nueva solicitud de prácticas preprofesionales.")
            ->line("")
            ->line("Estudiante: {$this->estudiante}")
            ->line("Carrera: {$this->carrera}")
            ->line("")
            ->line("")
            ->line('Por favor, revisa su solicitud para que pueda continuar con el proceso, gracias.')
            ->action('Ver Solicitudes', url('http://localhost:4200/app/organization'));

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
