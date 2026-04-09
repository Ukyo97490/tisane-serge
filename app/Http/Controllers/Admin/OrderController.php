<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $showArchived = $request->boolean('archives');

        $query = Order::with('pickupPoint')->orderByDesc('created_at');

        if ($showArchived) {
            $query->archived();
        } else {
            $query->active();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', '%' . $request->q . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->q . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('pickup_date', $request->date);
        }

        $orders = $query->paginate(20)->withQueryString();

        $statusLabels = [
            'en_attente' => 'En attente',
            'confirmee'  => 'Confirmée',
            'prete'      => 'Prête',
            'recuperee'  => 'Récupérée',
            'annulee'    => 'Annulée',
        ];

        return view('admin.orders.index', compact('orders', 'statusLabels', 'showArchived'));
    }

    public function show(Order $order)
    {
        $order->load('pickupPoint', 'items.product');

        $statusLabels = [
            'en_attente' => 'En attente',
            'confirmee'  => 'Confirmée',
            'prete'      => 'Prête',
            'recuperee'  => 'Récupérée',
            'annulee'    => 'Annulée',
        ];

        return view('admin.orders.show', compact('order', 'statusLabels'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:en_attente,confirmee,prete,recuperee,annulee',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }

    public function archive(Order $order)
    {
        $order->update(['archived_at' => now()]);

        return back()->with('success', 'Commande archivée.');
    }

    public function unarchive(Order $order)
    {
        $order->update(['archived_at' => null]);

        return back()->with('success', 'Commande désarchivée.');
    }
}
