<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta name="csrf-token" content="{{ csrf_token() }}">  
    <title>@yield('title', 'SiMagang JTI') — Polinema</title>  
  
    <link rel="preconnect" href="https://fonts.googleapis.com">  
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>  
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">  
  
    <script src="https://cdn.tailwindcss.com"></script>  
  
    <style>  
        :root {  
            --dd-primary:    #003B7A;  
            --dd-secondary:  #002856;  
            --dd-accent:     #F5A623;  
            --dd-background: #F8FAFC;  
            --dd-surface:    #FFFFFF;  
            --dd-text:       #0F172A;  
        }  
        * { box-sizing: border-box; }  
        body { font-family: 'Inter', sans-serif; color: #0F172A; margin: 0; }  
        .pjs { font-family: 'Plus Jakarta Sans', sans-serif; }  
        .nav-link {  
            font-family: 'Plus Jakarta Sans', sans-serif;  
            font-weight: 600;  
            color: #374151;  
            text-decoration: none;  
            transition: color 0.2s;  
        }  
        .nav-link:hover { color: #003B7A; }  
    </style>  
  
    @stack('styles')  
</head>  
<body class="min-h-screen" style="background-color: #F8FAFC;">  
    @yield('content')  
    @stack('scripts')  
</body>  
</html>  