<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retro Football Store</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <main>
        <div>
            <div>
                <h1>INICIAR SESIÓN</h1>
            </div>

            <div>
                @if (session('exitoso'))
                    <p class="exitoso">
                        {{ session('exitoso') }}
                    </p>
                @endif
            </div>
            <form action="validar" method="post">
                @csrf
                <input type="hidden" name="redirect" id="redirect">
                <div>
                    <label for="usuario">Nombre de usuario</label>
                    <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario"
                        value="{{ old('usuario') }}">
                    @error('usuario')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="contraseña">Contraseña</label>
                    <input type="password" name="contraseña" id="contraseña" placeholder="Contraseña">
                    @error('contraseña')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <input type="submit" value="Ingresar" name="login">
                </div>
                <div>
                    @if (session('error'))
                        <p class="text-danger">
                            {{ session('error') }}
                        </p>
                    @endif
                </div>
                <div class="contenedor-opciones">
                    <a href="/registro">Registrarse</a>
                    <a href="#">Olvidé mi contraseña</a>
                </div>
            </form>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    
</body>

</html>