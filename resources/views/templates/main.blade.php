<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- script app -->
        <script src="/assets/js/script.js" defer></script>
        <!-- font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <!-- ionic font -->
        <script  type="module"  src ="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" defer></script> 
        <script  nomodule src="https://unpkg .com/ionicons@5.5.2/dist/ionicons/ionicons.js" defer></script>
        <!-- Styles app -->
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbar">
                    <a href="/" class="navbar-brand">
                        <h1 class="logo"><ion-icon name="earth-outline"></ion-icon></h1>
                    </a>
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="/" class="nav-link">Eventos</a></li>
                        <li class="nav-item"><a href="/events/create" class="nav-link">Criar Eventos</a></li>
                        @auth
                        <li class="nav-item"><a href="/dashboard" class="nav-link">Meus Eventos</a></li>
                        <li class="nav-item">
                            <form action="/logout" method="POST">
                               @csrf 
                               <a href="/logout" class="nav-link" onclick="event.preventDefault();this.closest('form').submit();">Sair</a>
                            </form>
                        </li>
                        @endauth
                        @guest
                        <li class="nav-item"><a href="/login" class="nav-link">Entrar</a></li>
                        <li class="nav-item"><a href="/register" class="nav-link">Cadastrar</a></li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </header>
       <main>
            <div class="row">
                @if(session('msg'))
                    <p class="msg">{{ session('msg') }}</p>
                @endif
                @yield('content') 
            </div>
       </main>
    <footer>
        <p>Felipe Reis &copy;2022</p>
    </footer>
    </body>
</html>