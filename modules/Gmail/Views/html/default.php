<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title><?= $title ?></title>
	</head>

	<body>
		<p>Hello <?= $to ?>,</p>
		<p>This is a <strong>BASIC</strong> HTML email.</p>
		<p>Sincerely,</p>
		<p><?= $fromName ?><br><?= mailto($fromEmail) ?></p>
	</body>
</html>
