<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Cuenta creada</title>
	<style>
		* {
			box-sizing: border-box;
		}

		html,
		body {
			margin: 0;
			padding: 0;
		}

		body {
			background-color: #f5f5fb;
			font-family: sans-serif;
            padding: 1rem;
		}

		.card {
			background-color: #fff;
			box-shadow: 0 0 1rem rgba(0, 0, 0, 0.3);
			margin: 1rem auto;
			padding: 1rem 2rem;
			max-width: 500px;
			border-radius: .5rem;
		}

		.card h1 {
			font-size: 2rem;
			font-weight: 600;
			margin-bottom: 1rem;
		}

		.card p {
			margin-bottom: 1rem;
		}

		.card ul {
			padding: 1rem;
		}

		.card ul li {
			margin-bottom: .5rem;
		}
	</style>
</head>

<body>
	<div class="card">
		<h1>Cuenta creada</h1>
		<p>
			Hola {{ $user->first_name }}. Tu cuenta ha sido creada exitosamente. Aquí están tus credenciales de acceso:
		</p>
		<ul>
			<li><strong>Usuario:</strong> {{ $user->email }}</li>
			<li><strong>Contraseña:</strong> {{ $password }}</li>
		</ul>
	</div>
</body>

</html>
