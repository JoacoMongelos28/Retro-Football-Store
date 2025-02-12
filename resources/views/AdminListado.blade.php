<x-adminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/listado.css') }}">
    @endpush
</x-adminHeader>

<main class="contenedor-principal">
    <div>
        <div>
            <h2>Lista de camisetas</h2>
        </div>

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
            <section class="contenedor-camisetas">
                @if (!empty($camisetas))
                    @foreach ($camisetas as $camiseta)
                        <article>
                            <img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                            <p>{{ $camiseta->nombre }}</p>
                            <p>{{ $camiseta->precio }}</p>
                            <a href="/admin/editar/{{ $camiseta->slug }}">Editar</a>
                            <a href="/admin/eliminar/{{ $camiseta->id }}">Eliminar</a>
                        </article>
                    @endforeach
                @endif
            </section>
        </div>
    </div>
</main>

<x-footer></x-footer>