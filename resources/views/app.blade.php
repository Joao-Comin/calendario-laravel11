<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css'])
    
</head>
<body>
    

    <main>
        @yield('content')
    </main>
 <script>
    const btn = document.querySelector('.return');
    btn.addEventListener('click', () => {
        window.location.href = '/';
    });
 </script>
</body>
</html>