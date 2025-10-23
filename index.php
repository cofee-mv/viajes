<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencia de viajes - Destinos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php require "app/views/layout/header.php"; ?>

    <main class="max-w-6xl mx-auto p-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Descubre nuestros destinos</h1>
        <p class="text-gray-600 mb-6">Haz clic en un destino para continuar (serás redirigido a la página de inicio de sesión).</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php
            $destinos = [
                ['nombre' => 'Bogotá', 'pais' => 'Colombia'],
                ['nombre' => 'Cartagena', 'pais' => 'Colombia'],
                ['nombre' => 'Cali', 'pais' => 'Colombia'],
                ['nombre' => 'Barranquilla', 'pais' => 'Colombia'],
            ];

            foreach ($destinos as $dest):
                $url = 'http://localhost/viajes/public/index.php?page=login';
            ?>
                <a href="<?= $url ?>" class="block bg-white rounded-xl shadow hover:shadow-lg transition p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($dest['nombre']) ?></h2>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($dest['pais']) ?></p>
                        </div>
                        <div class="text-3xl">✈️</div>
                    </div>
                    <div class="mt-3 text-sm text-gray-500">Ver detalles y reservar (se requiere inicio de sesión).</div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="text-center text-sm text-gray-500 py-6">
        © <?= date('Y') ?> Agencia de viajes
    </footer>
</body>

</html>