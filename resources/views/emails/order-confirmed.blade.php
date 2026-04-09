<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmation de commande</title>
<style>
    body { font-family: 'Georgia', serif; background: #f9f6ef; margin: 0; padding: 20px; color: #382318; }
    .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .header { background: #2d6840; color: white; padding: 32px; text-align: center; }
    .header h1 { margin: 0; font-size: 24px; font-weight: normal; }
    .header .emoji { font-size: 40px; display: block; margin-bottom: 12px; }
    .body { padding: 32px; }
    .ref-box { background: #f0f7f2; border-left: 4px solid #2d6840; padding: 12px 16px; border-radius: 4px; margin: 20px 0; }
    .ref-box .ref { font-family: monospace; font-size: 20px; font-weight: bold; color: #2d6840; }
    table { width: 100%; border-collapse: collapse; margin: 16px 0; }
    th { background: #f9f6ef; padding: 8px 12px; text-align: left; font-size: 12px; text-transform: uppercase; color: #8c5e3a; letter-spacing: 0.05em; }
    td { padding: 10px 12px; border-bottom: 1px solid #f3ede0; font-size: 14px; }
    .total-row td { font-weight: bold; font-size: 16px; border-top: 2px solid #d9c4ac; border-bottom: none; }
    .info-block { background: #f9f6ef; border-radius: 8px; padding: 16px; margin: 16px 0; }
    .info-block h3 { margin: 0 0 8px; font-size: 14px; color: #2d6840; }
    .info-block p { margin: 4px 0; font-size: 14px; color: #523421; }
    .reminder-box { background: #fef9ec; border: 1px solid #fdf0ca; border-radius: 8px; padding: 16px; margin: 16px 0; font-size: 13px; color: #7d4606; }
    .footer { background: #2d6840; color: #b0d9bc; padding: 20px 32px; text-align: center; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <span class="emoji">🌿</span>
        <h1>Tisane Lontan</h1>
        <p style="margin:8px 0 0;opacity:0.85;font-size:15px;">Votre commande est confirmée</p>
    </div>

    <div class="body">
        <p>Bonjour <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Nous avons bien recu votre commande. Vous pouvez la récupérer directement sur notre stand lors du créneau que vous avez choisi.</p>

        <div class="ref-box">
            <div style="font-size:12px;color:#8c5e3a;margin-bottom:4px;">RÉFÉRENCE DE COMMANDE</div>
            <div class="ref">{{ $order->reference }}</div>
        </div>

        <h3 style="color:#2d6840;font-size:15px;">Articles commandés</h3>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th style="text-align:center">Qté</th>
                    <th style="text-align:right">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td style="text-align:right">{{ number_format($item->subtotal, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">Total a payer sur place</td>
                    <td style="text-align:right">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                </tr>
            </tbody>
        </table>

        <div class="info-block">
            <h3>📍 Point de retrait</h3>
            <p><strong>{{ $order->pickupPoint->name }}</strong></p>
            <p>{{ $order->pickupPoint->full_address }}</p>
        </div>

        <div class="info-block">
            <h3>🗓️ Date et heure de retrait</h3>
            <p><strong>{{ \Carbon\Carbon::parse($order->pickup_date)->locale('fr')->isoFormat('dddd D MMMM Y') }}</strong></p>
            <p>Heure prévue : <strong>{{ substr($order->pickup_time, 0, 5) }}</strong></p>
        </div>

        <div class="reminder-box">
            <strong>📬 Rappels automatiques</strong><br>
            Vous recevrez un rappel par email 24h avant votre retrait, puis un second rappel 1h avant l'heure choisie.
        </div>

        <p style="font-size:13px;color:#8c5e3a;">Le paiement s'effectue sur place, en espèces ou par chèque.</p>
    </div>

    <div class="footer">
        <p>Tisane Lontan &mdash; Saveurs naturelles de La Réunion</p>
        <p style="margin:4px 0 0;">Produits artisanaux, récoltés avec soin</p>
    </div>
</div>
</body>
</html>
