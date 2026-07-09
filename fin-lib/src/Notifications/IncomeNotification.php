<?php

namespace FinTrack\FinLib\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use FinTrack\FinLib\Models\Income;

class IncomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Income $income;
    public string $action;
    public array $extraData;

    /**
     * Create a new notification instance.
     */
    public function __construct(Income $income, string $action, array $extraData = [])
    {
        $this->income = $income;
        $this->action = $action;
        $this->extraData = $extraData;
    }

    /**
     * Get the notification's delivery channels.
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
        $orgName = $notifiable->organization?->name ?? 'Organization';

        $mailMessage = (new MailMessage)
            ->subject("Income Record " . ucfirst($this->action))
            ->greeting("Hello " . $notifiable->name . ",");

        if ($this->action === 'created') {
            $mailMessage->line("A new income record has been added to your organization ({$orgName}).")
                ->line("Amount: $" . number_format($this->income->amount, 2))
                ->line("Type: " . $this->income->type)
                ->line("Description: " . ($this->income->description ?? 'N/A'))
                ->line("Recorded By: " . ($this->income->createdBy?->name ?? 'N/A'));
        } elseif ($this->action === 'updated') {
            $mailMessage->line("An income record has been updated in your organization ({$orgName}).")
                ->line("New Amount: $" . number_format($this->income->amount, 2))
                ->line("Type: " . $this->income->type)
                ->line("Description: " . ($this->income->description ?? 'N/A'))
                ->line("Updated By: " . ($this->income->updatedBy?->name ?? 'N/A'));

            if (isset($this->extraData['original']['amount'])) {
                $mailMessage->line("Previous Amount: $" . number_format($this->extraData['original']['amount'], 2));
            }
        } elseif ($this->action === 'deleted') {
            $mailMessage->line("An income record has been deleted from your organization ({$orgName}).")
                ->line("Amount: $" . number_format($this->income->amount, 2))
                ->line("Type: " . $this->income->type)
                ->line("Description: " . ($this->income->description ?? 'N/A'));
        }

        return $mailMessage->line("Thank you for using FinTrack!");
    }
}
