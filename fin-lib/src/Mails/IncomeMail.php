<?php
namespace FinTrack\FinLib\Mails;

use FinTrack\FinLib\Models\Income;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IncomeMail extends Mailable
{
    use Queueable, SerializesModels;

    private Income $income;
     public function __construct(
        Income $income
    ) {
        $this->income =  $income;
    }

    public function build(): self
    {
        return $this->subject('Income Alert - ' . config('app.name'))
            ->view('fin-lib::emails.income.notification')
            ->with([
                'income' => $this->income,
            ]);
    }
}