<main>
    <div>
        <div>
            <h1>Registrarse</h1>
        </div>

        <div>
            <form action="registro/registrarUsuario" method="POST">
                @csrf
                <div>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
            
                <div>
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
            
                <div>
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            
                <div>
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" required>
                </div>
            
                <button type="submit">Registrarse</button>
            </form>          
        </div>
    </div>
</main>