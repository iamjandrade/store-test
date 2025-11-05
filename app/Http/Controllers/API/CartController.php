<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function __construct()
    {
        // Authentication is handled via header-based middleware (X-User-Email)
    }

    /**
     * Mostrar el carrito abierto del usuario actual (o crear uno).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)->whereNull('completed_at')->with('items.product')->first();

        if (! $cart) {
            $cart = Cart::create(['user_id' => $user->id]);
            $cart->load('items.product');
        }

        return CartResource::make($cart);
    }

    /**
     * Añade un producto al carrito o aumenta la cantidad.
     */
    public function addItem(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['integer', 'min:1'],
        ]);

        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)->whereNull('completed_at')->first() ?? Cart::create(['user_id' => $user->id]);

        $product = Product::findOrFail($data['product_id']);

        $item = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();

        $quantity = $data['quantity'] ?? 1;

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        $cart->load('items.product');

        return CartResource::make($cart);
    }

    /**
     * Actualizar la cantidad de un artículo existente en el carrito.
     */
    public function updateItem(Request $request, CartItem $item)
    {
        $user = $request->user();

        if ($item->cart->user_id !== $user->id || $item->cart->isCompleted()) {
            return response()->json(['message' => 'Not allowed'], Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        if ($data['quantity'] === 0) {
            $item->delete();
        } else {
            $item->quantity = $data['quantity'];
            $item->save();
        }

        $cart = $item->cart->load('items.product');

        return CartResource::make($cart);
    }

    /**
     * Eliminar artículo del carrito.
     */
    public function removeItem(Request $request, CartItem $item)
    {
        $user = $request->user();

        if ($item->cart->user_id !== $user->id || $item->cart->isCompleted()) {
            return response()->json(['message' => 'Not allowed'], Response::HTTP_FORBIDDEN);
        }

        $item->delete();

        $cart = Cart::where('id', $item->cart_id)->with('items.product')->first();

        return CartResource::make($cart);
    }

    /**
     * Marcar el carrito abierto actual como completado (convertirlo en un pedido).
     */
    public function complete(Request $request)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)->whereNull('completed_at')->with('items.product')->first();

        if (! $cart) {
            return response()->json(['message' => 'No open cart found'], Response::HTTP_NOT_FOUND);
        }

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], Response::HTTP_BAD_REQUEST);
        }

        $cart->markCompleted();

        return CartResource::make($cart);
    }
}
