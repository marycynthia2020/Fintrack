<?php

namespace FinTrack\FinLib\Listeners;

use FinTrack\Core\Models\User;
use FinTrack\FinLib\Events\IncomeCreated;
use FinTrack\FinLib\Events\IncomeDeleted;
use FinTrack\FinLib\Events\IncomeUpdated;
use FinTrack\FinLib\Mails\IncomeMail;
use FinTrack\FinLib\Notifications\IncomeNotification;
use Illuminate\Support\Facades\Mail;

class SendIncomeNotification
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

        if ($event instanceof IncomeCreated) {
            $action = 'created';
            $user = $model->createdBy ?: User::find($model->created_by);
        } elseif ($event instanceof IncomeUpdated) {
            $action = 'updated';
            $user = $model->updatedBy ?: User::find($model->updated_by);
            $extraData['original'] = $event->original;
            $extraData['changes'] = $event->changes;
        } elseif ($event instanceof IncomeDeleted) {
            $action = 'deleted';
            $userId = $model->updated_by ?: $model->created_by;
            $user = User::find($userId);
        }

        if ($user) {
            $user->loadMissing('organization');
            Mail::to($user->email)->send(new IncomeMail($model, $action, $extraData));
        }
    }
}
