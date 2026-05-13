<?php

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Notification\Enums\NotificationTypeEnum;

class DynamicSystemNotification extends Notification
{
    public function __construct(
        protected NotificationTypeEnum $type,
        protected array $data,
        protected array $activeChannels
    ) {}

    public function via($notifiable): array { return $this->activeChannels; }

    public function toMail($notifiable): MailMessage
    {
        $content = $this->resolveMessage();
        return (new MailMessage)
            ->subject('Alerte LiveGood : ' . ucfirst(str_replace('_', ' ', $this->type->value)))
            ->line($content)
            ->action('Voir mon compte', url('/dashboard'))
            ->line('Merci de faire partie de notre équipe !');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => $this->type->value,
            'message' => $this->resolveMessage(),
            'payload' => $this->data,
        ];
    }

    private function resolveMessage(): string
    {
        $template = config("notification.templates.{$this->type->value}", "Nouvelle notification système.");
        foreach ($this->data as $key => $value) {
            $template = str_replace(":{$key}", $value, $template);
        }
        return $template;
    }
}
