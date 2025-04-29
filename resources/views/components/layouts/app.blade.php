<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    @livewireStyles
</head>
<body class="bg-gray-100">

    <!-- Header or Navigation -->
    <nav class="bg-blue-600 p-4 text-white">
        <a href="/" class="font-bold">Home</a>
    </nav>

    <div class="container mx-auto py-6">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
