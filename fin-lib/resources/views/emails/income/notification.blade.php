<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Income Alert</title>
</head>

<body>

    <div>
        <h1>Income Alert</h1>
        <p>An income has been {{ $action }}</p>
        <p><strong>Amount:</strong> {{ app('fin-lib')->formatAmount((float) $income->amount) }}</p>
        @if(isset($extraData['original']['amount']))
        <p>
            <strong>Previous Amount:</strong>
            {{ app('fin-lib')->formatAmount((float) $extraData['original']['amount']) }}
        </p>
        @endif

        <p><strong>Category:</strong> {{ $income->type }}</p>
        <p><strong>Description:</strong> {{ $income->description }}</p>
        <p><strong>Created By:</strong> {{ $income->createdBy->name ?? 'Unknown' }}</p>
        <p><strong>Date:</strong> {{ $income->created_at->format('Y-m-d') }}</p>
        <p>Thank you for using our service!</p>
        <p>Best regards,<br>FinTrack Team</p>
        <p style="font-size: 0.8em; color: gray;">This is an automated message. Please do not reply.</p>
    </div>

</body>

</html>
