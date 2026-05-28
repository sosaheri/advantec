<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecosistema de Órdenes - Dashboard</title>
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #f7fafc; color: #2d3748; margin: 0; padding: 40px; }
        .container { max-width: 1100px; margin: 0 auto; }
        header { margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
    </style>
    @livewireStyles
</head>
<body>
    <div class="container">
        <header>
            <h1 style="margin: 0; font-size: 28px; color: #2d3748;">⚡ Ecosistema Modular de Órdenes</h1>
            <p style="margin: 5px 0 0 0; color: #4a5568;">Arquitectura en Microservicios: Laravel 12 API + Worker + Node.js Satélite</p>
        </header>

        <main>
            @livewire('order-monitor')
        </main>
    </div>

    @livewireScripts
</body>
</html>