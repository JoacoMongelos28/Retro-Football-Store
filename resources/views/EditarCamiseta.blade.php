<x-adminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/editar.css') }}">
    @endpush
</x-adminHeader>

<main>
    <div class="contenedor-principal">
        <section>
            <article>
                <form action="/admin/editarCamiseta/{{$camiseta->id}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre de la camiseta" value="{{ old('nombre', $camiseta->nombre) }}">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
                    <div>
                        <label for="descripcion">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" placeholder="Descripción" value="{{ old('descripcion', $camiseta->descripcion) }}">
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
                    <div>
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" placeholder="Precio de la camiseta" value="{{ old('precio', $camiseta->precio) }}">
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
                            <option value="1" {{ old('estado', $camiseta->estado) == 1 ? 'selected' : '' }}>Destacado</option>
                            <option value="2" {{ old('estado', $camiseta->estado) == 2 ? 'selected' : '' }}>Oferta</option>
                        </select>
                        @error('estado')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
                    <button type="submit">Editar camiseta</button>
                </form>                
            </article>
        </section>
    </div>
</main>

<x-footer></x-footer>