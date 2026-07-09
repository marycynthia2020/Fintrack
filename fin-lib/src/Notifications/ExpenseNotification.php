<?php

namespace FinTrack\FinLib\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use FinTrack\FinLib\Models\Expense;

class ExpenseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Expense $expense;
    public string $action;
    public array $extraData;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense, string $action, array $extraData = [])
    {
        $this->expense = $expense;
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
            ->subject("Expense Record " . ucfirst($this->action))
            ->greeting("Hello " . $notifiable->name . ",");

        if ($this->action === 'created') {
            $mailMessage->line("A new expense record has been added to your organization ({$orgName}).")
                ->line("Amount:" . app('fin-lib')->formatAmount((float)$this->expense->amount))
                ->line("Type: " . $this->expense->type)
                ->line("Description: " . ($this->expense->description ?? 'N/A'))
                ->line("Recorded By: " . ($this->expense->createdBy?->name ?? 'N/A'));
        } elseif ($this->action === 'updated') {
            $mailMessage->line("An expense record has been updated in your organization ({$orgName}).")
                ->line("New Amount:" . app('fin-lib')->formatAmount((float)$this->expense->amount))
                ->line("Type: " . $this->expense->type)
                ->line("Description: " . ($this->expense->description ?? 'N/A'))
                ->line("Updated By: " . ($this->expense->updatedBy?->name ?? 'N/A'));

            if (isset($this->extraData['original']['amount'])) {
                $mailMessage->line("Previous Amount:" . app('fin-lib')->formatAmount((float)$this->extraData['original']['amount']));
            }
        } elseif ($this->action === 'deleted') {
            $mailMessage->line("An expense record has been deleted from your organization ({$orgName}).")
                ->line("Amount:" . app('fin-lib')->formatAmount((float)$this->expense->amount))
                ->line("Type: " . $this->expense->type)
                ->line("Description: " . ($this->expense->description ?? 'N/A'));
        }

        return $mailMessage->line("Thank you for using FinTrack!");
    }
}
