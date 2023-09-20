<?php

namespace App\Notifications;

use App\Models\Classwork;
use App\Notifications\Channels\HadaraSmsChannel;
use App\Services\HadaraSms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;

class NewClassworkNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Classwork $classwork)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $via = [
            'database',
            HadaraSmsChannel::class,
            'mail',
            'broadcast',
            // 'vonage',
        ];
        // if($notifiable->receive_mail_notifications){
        //     $via[] = 'mail';
        // }

        // if($notifiable->receive_push_notifications){
        //     $via[] = 'broadcast';
        // }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage($this->createMessage());
    }

    public function toMail(object $notifiable): MailMessage
    {

        $content = __(':name posted a new :type :title', [
            'name' => $this->classwork->user->name,
            'type' => __($this->classwork->type),
            'title' => $this->classwork->title,
        ]);

        return (new MailMessage)
            ->subject(__('New :type', ['type' => $this->classwork->type]))
            ->greeting(__('Hi :name', ['name' => $notifiable->name]))
            ->line($content)
            ->action(__('Go To Classwork'), route('classrooms.classworks.show', [$this->classwork->classroom_id, $this->classwork->id]))
            ->line('Thank you for using our application!');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->createMessage());
    }

    protected function createMessage(): array
    {
        $content = __(':name posted a new :type :title', [
            'name' => $this->classwork->user->name,
            'type' => __($this->classwork->type),
            'title' => $this->classwork->title,
        ]);

        return [
            'title' => __('New :type', ['type' => $this->classwork->type]),
            'body' => $content,
            'image' => '',
            'link' => route('classrooms.classworks.show', [$this->classwork->classroom_id, $this->classwork->id]),
            'classwork_id' => $this->classwork->id,
        ];
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(__('A new Classowrk Created!'));
    }

    public function toHadara(object $notifiable): string
    {
        return __('A new Classowrk Created!');
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
