<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
    @endpush
</x-header>


<main>
    <div class="contenedor-principal">
        <h1 class="titulo-txt text-3xl">Carrito de Compras</h1>

        @if ($carrito && count($carrito) > 0)
        <div class="carrito-container">
            @foreach ($carrito as $camiseta)
                <div class="carrito-item" id="carrito-item-{{ $camiseta->id }}">
                    <div class="carrito-img">
                        <img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                    </div>
                    <div class="carrito-info">
                        <a href="/home/camiseta/{{ $camiseta->slug }}" class="carrito-nombre">{{ $camiseta->nombre }}</a>
                        <p class="carrito-talle">Talle: <span class="camiseta-talle">{{ $camiseta->talle }}</span></p>
                    </div>
                    <div class="contenedor-info-carrito">
                    <div class="carrito-cantidad">
                        <button class="decrementar" data-id="{{ $camiseta->id }}">-</button>
                        <input type="number" value="{{ $camiseta->cantidad }}" id="cantidad-{{ $camiseta->id }}" readonly>
                        <button class="incrementar" data-id="{{ $camiseta->id }}">+</button>
                    </div>
                    <div class="carrito-precio">${{ $camiseta->precio }}</div>
                    <div class="carrito-subtotal" id="total-{{ $camiseta->id }}">${{ $camiseta->total }}</div>
                    <div style="display: none" id="stock">{{ $camiseta->stock_disponible }}</div>
                    
                </div><div class="carrito-eliminar">
                        <button class="eliminar" data-id="{{ $camiseta->id }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    
        <div id="contenedor-pagar">
            <p id="totalPrecio">Total: ${{ $total }}</p>
            <form action="/pagar" method="POST">
                @csrf
                <button type="submit">Comprar</button>
            </form>
        </div>
    @else
        <p id="mensaje-vacio">No hay productos en el carrito</p>
    @endif

    @if (session('exitoso'))
        <p>{{ session('exitoso') }}</p>
    @endif
    
    </div>
</main>

<x-footer>
    @push('scripts')
        <script src="{{ asset('js/carrito.js') }}"></script>
    @endpush
</x-footer>