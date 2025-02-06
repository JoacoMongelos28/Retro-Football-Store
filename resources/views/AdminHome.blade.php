<x-adminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @endpush
</x-adminHeader>

<main>
    <div class="contenedor-principal">
        <div>
            @if (session('exitoso'))
                <p>
                    {{ session('exitoso') }}
                </p>
            @endif
        </div>

        <div>
            @if (session('error'))
                <p>
                    {{ session('error') }}
                </p>
            @endif
        </div>

        <div>
            <a href="/admin/agregar">
                <h3>Agregar nueva camiseta</h3>
            </a>
        </div>

        <div>
            <a href="/admin/listado">
                <h3>Editar camisetas</h3>
            </a>
        </div>

        <div>
            <a href="/admin/listado">
                <h3>Eliminar camisetas</h3>
            </a>
        </div>
    </div>
</main>

<x-footer></x-footer>