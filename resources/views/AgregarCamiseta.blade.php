<x-adminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/agregar.css') }}">
    @endpush
</x-adminHeader>

<main>
    <div class="contenedor-principal">
        <div>
            <h2 class="titulo-nueva-camiseta">Agregar nueva camiseta</h2>
        </div>
        <div>
            @if (session('error'))
                <p class="error-message">
                    {{ session('error') }}
                </p>
            @endif
        </div>

        <div>
            <form action="/admin/agregarCamiseta" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-field">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre de la camiseta"
                        value="{{ old('nombre') }}">
                    @error('nombre')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="descripcion">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" placeholder="Descripción"
                        value="{{ old('descripcion') }}">
                    @error('descripcion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="precio">Precio</label>
                    <input type="text" name="precio" id="precio" placeholder="Precio de la camiseta"
                        value="{{ old('precio') }}">
                    @error('precio')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="imagen">Imagen</label>
                    <input type="file" name="imagen" id="imagen" accept="image/*">
                    @error('imagen')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="imagen_trasera">Imagen trasera</label>
                    <input type="file" name="imagen_trasera" id="imagen_trasera" accept="image/*">
                    @error('imagen_trasera')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado">
                        <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Destacado</option>
                        <option value="2" {{ old('estado') == 2 ? 'selected' : '' }}>Oferta</option>
                    </select>
                    @error('estado')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label>Selecciona los talles disponibles:</label>
                    <div class="talles-container">
                        <input type="checkbox" id="xs" name="talles[]" value="XS" onclick="habilitarInput()">
                        <label for="xs">XS</label>
                        <input type="number" name="cantidadXS" id="cantidadXS" placeholder="Cantidad XS"
                            value="{{ old('cantidadXS') }}" class="input-cantidad">

                        <input type="checkbox" id="s" name="talles[]" value="S" onclick="habilitarInput()">
                        <label for="s">S</label>
                        <input type="number" name="cantidadS" id="cantidadS" placeholder="Cantidad S"
                            value="{{ old('cantidadS') }}" class="input-cantidad">

                        <input type="checkbox" id="m" name="talles[]" value="M" onclick="habilitarInput()">
                        <label for="m">M</label>
                        <input type="number" name="cantidadM" id="cantidadM" placeholder="Cantidad M"
                            value="{{ old('cantidadM') }}" class="input-cantidad">

                        <input type="checkbox" id="l" name="talles[]" value="L" onclick="habilitarInput()">
                        <label for="l">L</label>
                        <input type="number" name="cantidadL" id="cantidadL" placeholder="Cantidad L"
                            value="{{ old('cantidadL') }}" class="input-cantidad">

                        <input type="checkbox" id="xl" name="talles[]" value="XL"
                            onclick="habilitarInput()">
                        <label for="xl">XL</label>
                        <input type="number" name="cantidadXL" id="cantidadXL" placeholder="Cantidad XL"
                            value="{{ old('cantidadXL') }}" class="input-cantidad">

                        <input type="checkbox" id="xxl" name="talles[]" value="XXL"
                            onclick="habilitarInput()">
                        <label for="xxl">XXL</label>
                        <input type="number" name="cantidadXXL" id="cantidadXXL" placeholder="Cantidad XXL"
                            value="{{ old('cantidadXXL') }}" class="input-cantidad">
                    </div>
                </div>

                <div class="contenedor-boton-agregar">
                    <div>
                        <a class="contenedor-btn-volver" href="/admin">Volver</a>
                    </div>
                    <button type="submit">Agregar camiseta</button>
                </div>
            </form>
        </div>
    </div>
</main>

<x-footer>
    @push('scripts')
        <script src="{{ asset('js/agregarCamiseta.js') }}"></script>
    @endpush
</x-footer>