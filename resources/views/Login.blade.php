<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retro Football Store</title>
</head>
<body>
    <main>
        <div>
            <div>
                <h1>INICIAR SESIÓN</h1>
            </div>

            <div>
                @if (session('exitoso'))
                    <p>
                        {{ session('exitoso') }}
                    </p>
                @endif            
            </div>
            <form action="validar" method="post">
                @csrf
                <div>
                    <label for="usuario">Nombre de usuario</label>
                    <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario" value="{{ old('usuario') }}">
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
                        <p>
                            {{ session('error') }}
                        </p>
                    @endif            
                </div>
                <div>
                    <a href="/registro">Registrarse</a>
                </div>
                <div>
                    <a href="#">Olvidé mi contraseña</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>