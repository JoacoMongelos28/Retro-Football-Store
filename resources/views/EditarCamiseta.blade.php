<x-adminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/editar.css') }}">
    @endpush
</x-adminHeader>

<main>
    <div class="contenedor-principal">
        <section>
            <article>
                <form action="/admin/editarCamiseta/{{ $camiseta->id }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre de la camiseta"
                            value="{{ old('nombre', $camiseta->nombre) }}">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcion">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" placeholder="Descripción"
                            value="{{ old('descripcion', $camiseta->descripcion) }}">
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" placeholder="Precio de la camiseta"
                            value="{{ old('precio', $camiseta->precio) }}">
                        @error('precio')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="imagen">Seleccione una nueva imagen:</label>
                        <input type="file" name="imagen" id="imagen" accept="image/*">
                        @error('imagen')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado">
                            <option value="1" {{ old('estado', $camiseta->estado) == 1 ? 'selected' : '' }}>
                                Destacado</option>
                            <option value="2" {{ old('estado', $camiseta->estado) == 2 ? 'selected' : '' }}>Oferta
                            </option>
                        </select>
                        @error('estado')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <label>Selecciona los talles disponibles:</label>
                    <div>
                        <input type="checkbox" id="xs" name="talles[]" value="XS" onclick="habilitarInput()">
                        <label for="xs">XS</label>
                        <input type="number" name="cantidadXS" id="cantidadXS" placeholder="Cantidad XS"
                            value="{{ old('cantidadXS') }}" style="display: none;">

                        <input type="checkbox" id="s" name="talles[]" value="S" onclick="habilitarInput()">
                        <label for="s">S</label>
                        <input type="number" name="cantidadS" id="cantidadS" placeholder="Cantidad S"
                            value="{{ old('cantidadS') }}" style="display: none;">

                        <input type="checkbox" id="m" name="talles[]" value="M" onclick="habilitarInput()">
                        <label for="m">M</label>
                        <input type="number" name="cantidadM" id="cantidadM" placeholder="Cantidad M"
                            value="{{ old('cantidadM') }}" style="display: none;">

                        <input type="checkbox" id="l" name="talles[]" value="L" onclick="habilitarInput()">
                        <label for="l">L</label>
                        <input type="number" name="cantidadL" id="cantidadL" placeholder="Cantidad L"
                            value="{{ old('cantidadL') }}" style="display: none;">

                        <input type="checkbox" id="xl" name="talles[]" value="XL" onclick="habilitarInput()">
                        <label for="xl">XL</label>
                        <input type="number" name="cantidadXL" id="cantidadXL" placeholder="Cantidad XL"
                            value="{{ old('cantidadXL') }}" style="display: none;">

                        <input type="checkbox" id="xxl" name="talles[]" value="XXL"
                            onclick="habilitarInput()">
                        <label for="xxl">XXL</label>
                        <input type="number" name="cantidadXXL" id="cantidadXXL" placeholder="Cantidad XXL"
                            value="{{ old('cantidadXXL') }}" style="display: none;">
                    </div>

                    <button type="submit">Editar camiseta</button>
                </form>
            </article>
        </section>
    </div>
</main>

<script>
    function habilitarInput() {
        ['xs', 's', 'm', 'l', 'xl', 'xxl'].forEach(talle => {
            let checkbox = document.getElementById(talle);
            let cantidadInput = document.getElementById(`cantidad${talle.toUpperCase()}`);

            if (checkbox.checked) {
                cantidadInput.style.display = 'block';
            } else {
                cantidadInput.style.display = 'none';
                cantidadInput.value = '';
            }
        });
    }
</script>

<x-footer></x-footer>
