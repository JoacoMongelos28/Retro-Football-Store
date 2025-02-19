<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retro Football Store</title>
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
</head>

<body>
    <main>
        <div>
            <div>
                <h1>Registrarse</h1>
            </div>

            <div>
                <form action="registro/registrarUsuario" method="POST">
                    @csrf
                    <input type="hidden" name="redirect" id="redirect">
                    <div>
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre y Apellido"
                        value="{{ old('nombre') }}">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="usuario">Usuario:</label>
                        <input type="text" id="usuario" name="usuario" placeholder="Nombre de usuario"
                        value="{{ old('usuario') }}">
                        @error('usuario')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email"
                        value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="contraseña">Contraseña:</label>
                        <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña"
                        value="{{ old('contraseña') }}">
                        @error('contraseña')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit">Registrarse</button>
                </form>

                <div class="contenedor-btn-iniciar-sesion">                
                    <a class="iniciar-sesion" href="/login">¿Tienes cuenta? Inicia Sesión</a>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/registro.js') }}"></script>

</body>

</html>