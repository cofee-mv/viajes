<?php session_start(); ?>
<script src="https://cdn.tailwindcss.com"></script>
<header class="bg-white shadow absolute w-full top-0 left-0">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex justify-between h-16 items-center">
			<img src="http://localhost/viajes/img/favicon_viajes.png" alt="logo" class="h-10 w-10"/>
			<div class="flex-shrink-0">
				<a href="/viajes/" class="text-lg font-bold text-green-600">Viajes</a>
			</div>
			<nav class="hidden md:flex space-x-4">
				<a href="/viajes/public/index.php?page=login" class="text-gray-600 hover:text-green-600">Iniciar
					Sesión</a>
				<a href="/viajes/public/index.php?page=register"
					class="text-gray-600 hover:text-green-600">Registrarse</a>
				<a href="/viajes/public/index.php?page=reservas" class="text-gray-600 hover:text-green-600">Reservas</a>
				<?php if (!empty($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
					<a href="/viajes/public/index.php?page=admin"
						class="text-purple-600 hover:text-purple-700 font-semibold">Panel Admin</a>
				<?php endif; ?>
				<?php if (!empty($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2): ?>
					<a href="/viajes/public/index.php?page=empleado"
						class="text-blue-600 hover:text-blue-700 font-semibold">Panel Empleado</a>
				<?php endif; ?>
				<a href="/viajes/public/index.php?page=logout" class="text-gray-600 hover:text-red-600">Cerrar
					Sesión</a>
			</nav>
			<div class="flex items-center space-x-4">
				<?php if (!empty($_SESSION['nombre'])): ?>
					<span class="text-sm text-gray-700">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
				<?php endif; ?>
				<button id="mobile-menu-button" class="md:hidden p-2 rounded hover:bg-gray-100">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none"
						viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M4 6h16M4 12h16M4 18h16" />
					</svg>
				</button>
			</div>
		</div>
	</div>

	<!-- Mobile menu, show/hide with JS -->
	<div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
		<a href="/viajes/public/index.php?page=login" class="block py-2 text-gray-600 hover:text-green-600">Login</a>
		<a href="/viajes/public/index.php?page=register"
			class="block py-2 text-gray-600 hover:text-green-600">Register</a>
		<a href="/viajes/public/index.php?page=reservas"
			class="block py-2 text-gray-600 hover:text-green-600">Reservas</a>
		<?php if (!empty($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
			<a href="/viajes/public/index.php?page=admin"
				class="block py-2 text-purple-600 hover:text-purple-700 font-semibold">Panel Admin</a>
		<?php endif; ?>
		<?php if (!empty($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2): ?>
			<a href="/viajes/public/index.php?page=empleado"
				class="block py-2 text-blue-600 hover:text-blue-700 font-semibold">Panel Empleado</a>
		<?php endif; ?>
		<a href="/viajes/public/index.php?page=logout" class="block py-2 text-gray-600 hover:text-red-600">Logout</a>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var btn = document.getElementById('mobile-menu-button');
			var menu = document.getElementById('mobile-menu');
			if (btn) {
				btn.addEventListener('click', function () {
					if (menu.classList.contains('hidden')) {
						menu.classList.remove('hidden');
					} else {
						menu.classList.add('hidden');
					}
				});
			}
		});
	</script>
</header>