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
                        <article class="camiseta">
                            <div class="imagen-hover">
                                <img class="frontal" src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                                @if ($camiseta->imagen_trasera)
                                    <img class="trasera" src="{{ $camiseta->imagen_trasera }}"
                                        alt="{{ $camiseta->nombre }}">
                                @endif
                            </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.camiseta').each(function() {
        const trasera = $(this).find('.trasera');
        
        if (trasera.length > 0) {
            $(this).find('.imagen-hover').hover(
                function() {
                    $(this).find('.frontal').css('opacity', '0');
                    $(this).find('.trasera').css('opacity', '1');
                },
                function() {
                    $(this).find('.frontal').css('opacity', '1');
                    $(this).find('.trasera').css('opacity', '0');
                }
            );
        } else {
            $(this).find('.imagen-hover').off('mouseenter mouseleave');
        }
    });
});
</script>

<x-footer></x-footer>