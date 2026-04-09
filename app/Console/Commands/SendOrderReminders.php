<?php

namespace App\Console\Commands;

use App\Mail\OrderReminderMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendOrderReminders extends Command
{
    protected $signature   = 'orders:send-reminders';
    protected $description = 'Envoie les rappels 24h et 1h avant la récupération de commandes';

    public function handle(): void
    {
        $now = Carbon::now();

        // Rappel 24h : commandes demain, non encore rappelées
        $tomorrow = $now->copy()->addDay()->toDateString();

        $orders24h = Order::whereDate('pickup_date', $tomorrow)
            ->where('reminder_24h_sent', false)
            ->whereNotIn('status', ['annulee', 'recuperee'])
            ->with('pickupPoint')
            ->get();

        foreach ($orders24h as $order) {
            Mail::to($order->customer_email)->send(new OrderReminderMail($order, '24h'));
            $order->update(['reminder_24h_sent' => true]);
            $this->info('Rappel 24h envoyé pour commande ' . $order->reference);
        }

        // Rappel 1h : commandes aujourd'hui, pickup dans 60-70 minutes
        $orders1h = Order::whereDate('pickup_date', $now->toDateString())
            ->where('reminder_1h_sent', false)
            ->whereNotIn('status', ['annulee', 'recuperee'])
            ->with('pickupPoint')
            ->get();

        foreach ($orders1h as $order) {
            $pickupDateTime = Carbon::parse($order->pickup_date->format('Y-m-d') . ' ' . $order->pickup_time);
            $diff           = $now->diffInMinutes($pickupDateTime, false);

            // Envoyer si on est entre 55 et 70 minutes avant le retrait
            if ($diff >= 55 && $diff <= 70) {
                Mail::to($order->customer_email)->send(new OrderReminderMail($order, '1h'));
                $order->update(['reminder_1h_sent' => true]);
                $this->info('Rappel 1h envoyé pour commande ' . $order->reference);
            }
        }

        $this->info('Rappels traités : ' . $orders24h->count() . ' (24h), envois 1h vérifiés.');
    }
}
