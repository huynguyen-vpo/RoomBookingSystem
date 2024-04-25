<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <p>Dear {{ $name }} </p>

		<p> Thank you for choosing our hotel! Your reservation is received. We're thrilled to host you for a memorable stay.
	    Please review your booking details below. If you have any questions or special requests, feel free to reach out.
		We look forward to providing you with an exceptional experience. Safe travels! </p>

		Warm regards,
		Hotel </pre>
		<img src="{{ $message->embed(public_path() . '/images/logo.png') }}" />
    </div>
</body>
</html>
    </div>
</body>
</html>