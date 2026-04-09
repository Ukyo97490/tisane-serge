<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotificationMail;
use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupPoint;
use App\Models\PickupSlot;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $pickupPoints = PickupPoint::where('active', true)->with('activeSlots')->get();
        $total        = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('checkout.index', compact('cart', 'pickupPoints', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_email'  => 'required|email|max:255',
            'customer_phone'  => 'nullable|string|max:30',
            'pickup_point_id' => 'required|exists:pickup_points,id',
            'pickup_date'     => 'required|date|after_or_equal:today',
            'pickup_time'     => 'required|date_format:H:i',
            'notes'           => 'nullable|string|max:500',
        ]);

        // Vérifier que le point de retrait est actif
        $pickupPoint = PickupPoint::where('id', $request->pickup_point_id)
            ->where('active', true)
            ->firstOrFail();

        // Calculer le total
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Créer la commande
        $order = Order::create([
            'reference'       => Order::generateReference(),
            'customer_name'   => $request->customer_name,
            'customer_email'  => $request->customer_email,
            'customer_phone'  => $request->customer_phone,
            'pickup_point_id' => $request->pickup_point_id,
            'pickup_date'     => $request->pickup_date,
            'pickup_time'     => $request->pickup_time,
            'total'           => $total,
            'status'          => 'en_attente',
            'notes'           => $request->notes,
        ]);

        // Créer les articles
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['id'],
                'product_name' => $item['name'],
                'unit_price'   => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
        }

        // Vider le panier
        session()->forget('cart');

        // Envoyer les emails
        Mail::to($order->customer_email)->send(new OrderConfirmedMail($order));
        Mail::to(config('mail.seller_email', config('mail.from.address')))
            ->send(new NewOrderNotificationMail($order));

        return redirect()->route('checkout.confirm', $order->reference);
    }

    public function confirm(string $reference)
    {
        $order = Order::where('reference', $reference)->with('pickupPoint', 'items')->firstOrFail();

        return view('checkout.confirm', compact('order'));
    }

    public function slotsForPoint(Request $request)
    {
        $request->validate([
            'pickup_point_id' => 'required|exists:pickup_points,id',
            'date'            => 'required|date',
        ]);

        $dayOfWeek = (int) date('w', strtotime($request->date));

        $slots = PickupSlot::where('pickup_point_id', $request->pickup_point_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get();

        return response()->json($slots->map(fn($s) => [
            'id'         => $s->id,
            'open_time'  => substr($s->open_time, 0, 5),
            'close_time' => substr($s->close_time, 0, 5),
            'label'      => substr($s->open_time, 0, 5) . ' - ' . substr($s->close_time, 0, 5),
        ]));
    }
}
