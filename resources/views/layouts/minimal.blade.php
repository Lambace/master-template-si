<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Minimal Layout</title>
</head>
<body style="padding: 20px; background: white;">
    <h1 style="color: green;">LAYOUT MINIMAL</h1>
    <div style="border: 2px solid green; padding: 20px; margin: 20px 0;">
        @yield('content')
    </div>
    <p style="color: blue;">Konten setelah yield</p>
</body>
</html>
