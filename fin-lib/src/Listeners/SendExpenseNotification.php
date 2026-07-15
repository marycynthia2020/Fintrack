<?php

namespace FinTrack\FinLib\Listeners;

use FinTrack\Core\Models\User;
use FinTrack\FinLib\Events\ExpenseCreated;
use FinTrack\FinLib\Events\ExpenseDeleted;
use FinTrack\FinLib\Events\ExpenseUpdated;
use FinTrack\FinLib\Mails\ExpenseMail;
use FinTrack\FinLib\Notifications\ExpenseNotification;
use Illuminate\Support\Facades\Mail;

class SendExpenseNotification
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $model = $event->model;
        $action = '';
        $user = null;
        $extraData = [];

        if ($event instanceof ExpenseCreated) {
            $action = 'created';
            $user = $model->createdBy ?: User::find($model->created_by);
        } elseif ($event instanceof ExpenseUpdated) {
            $action = 'updated';
            $user = $model->updatedBy ?: User::find($model->updated_by);
            $extraData['original'] = $event->original;
            $extraData['changes'] = $event->changes;
        } elseif ($event instanceof ExpenseDeleted) {
            $action = 'deleted';
            $userId = $model->updated_by ?: $model->created_by;
            $user = User::find($userId);
        }

        if ($user) {
            $user->loadMissing('organization');
            Mail::to($user->email)->send(new ExpenseMail($model, $action, $extraData));
        }
    }
}
