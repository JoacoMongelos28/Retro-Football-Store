<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class MercadoPagoModel extends Model
{
    
    public function pagarCamiseta($total, $idUsuario)
    {
        $apiKey = config('services.mercadopago.public_key');
        
        $item = [
            'id' => $idUsuario,
            'title' => 'Retro Football Store',
            'description' => 'Camisetas de Futbol',
            'currency_id' => 'ARS',
            'unit_price' => (float)$total,
            'quantity' => 1
        ];

        $backUrls = [
            'success' => 'http://localhost/carrito/estado'
        ];

        $jsonBody = [
            'items' => [$item],
            'back_urls' => $backUrls,
            'auto_return' => 'approved',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.mercadopago.com/checkout/preferences', $jsonBody);

        $jsonResponse = $response->json();

        $preferenceId = $jsonResponse['id'];

        return "https://www.mercadopago.com.ar/checkout/v1/redirect?pref_id=" . $preferenceId;
    }
}