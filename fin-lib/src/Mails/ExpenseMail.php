<?php
namespace FinTrack\FinLib\Mails;

use FinTrack\FinLib\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpenseMail extends Mailable
{
    use Queueable, SerializesModels;

    private Expense $expense;
    private string $action;
    private array $extraData;
     public function __construct(
        Expense $expense,
        string $action,
        array $extraData = []
    ) {
        $this->expense =  $expense;
        $this->action = $action;
        $this->extraData = $extraData;
    }

    public function build(): self
    {
        return $this->subject('Expense Alert - ' . config('app.name'))
            ->view('fin-lib::emails.expenses.notification')
            ->with([
                'expense' => $this->expense,
                'action' => $this->action,
                'extraData' => $this->extraData,
            ]);
    }
}