<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouvelle commande</title>
<style>
    body { font-family: 'Georgia', serif; background: #f9f6ef; margin: 0; padding: 20px; color: #382318; }
    .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .header { background: #1c3f27; color: white; padding: 24px 32px; }
    .header h1 { margin: 0; font-size: 18px; font-weight: normal; }
    .badge { display: inline-block; background: #52a066; color: white; padding: 3px 12px; border-radius: 99px; font-size: 13px; margin-top: 4px; }
    .body { padding: 32px; }
    .stat-row { display: flex; gap: 12px; margin: 16px 0; }
    .stat { flex: 1; background: #f0f7f2; border-radius: 8px; padding: 14px; text-align: center; }
    .stat .value { font-size: 22px; font-weight: bold; color: #2d6840; }
    .stat .label { font-size: 11px; color: #8c5e3a; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin: 12px 0; }
    th { background: #f9f6ef; padding: 8px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #8c5e3a; }
    td { padding: 9px 12px; border-bottom: 1px solid #f3ede0; font-size: 13px; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 16px 0; }
    .info-card { background: #f9f6ef; border-radius: 8px; padding: 12px; }
    .info-card h4 { margin: 0 0 6px; font-size: 12px; color: #2d6840; text-transform: uppercase; letter-spacing: 0.05em; }
    .info-card p { margin: 2px 0; font-size: 13px; color: #523421; }
    .cta { display: inline-block; background: #2d6840; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 14px; margin-top: 12px; }
    .footer { background: #2d6840; color: #b0d9bc; padding: 16px 32px; text-align: center; font-size: 11px; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🌿 Tisane Lontan &mdash; Nouvelle commande</h1>
        <span class="badge">{{ $order->reference }}</span>
    </div>

    <div class="body">
        <p style="font-size:15px;">Une nouvelle commande vient d'etre passée.</p>

        <div class="stat-row">
            <div class="stat">
                <div class="value">{{ number_format($order->total, 2, ',', ' ') }} €</div>
                <div class="label">Montant total</div>
            </div>
            <div class="stat">
                <div class="value">{{ $order->items->sum('quantity') }}</div>
                <div class="label">Article{{ $order->items->sum('quantity') > 1 ? 's' : '' }}</div>
            </div>
        </div>

        <h3 style="font-size:14px;color:#2d6840;">Articles commandés</h3>
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
            </tbody>
        </table>

        <div class="info-grid">
            <div class="info-card">
                <h4>Client</h4>
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>{{ $order->customer_email }}</p>
                @if($order->customer_phone)<p>{{ $order->customer_phone }}</p>@endif
            </div>
            <div class="info-card">
                <h4>Retrait</h4>
                <p><strong>{{ $order->pickupPoint->name }}</strong></p>
                <p>{{ \Carbon\Carbon::parse($order->pickup_date)->locale('fr')->isoFormat('dddd D MMMM') }}</p>
                <p>{{ substr($order->pickup_time, 0, 5) }}</p>
            </div>
        </div>

        @if($order->notes)
        <div class="info-card" style="margin:12px 0;">
            <h4 style="font-size:12px;color:#2d6840;text-transform:uppercase;margin:0 0 4px;">Notes</h4>
            <p style="font-size:13px;color:#523421;">{{ $order->notes }}</p>
        </div>
        @endif

        <a href="{{ route('admin.orders.show', $order) }}" class="cta">Voir la commande dans l'administration</a>
    </div>

    <div class="footer">
        <p>Tisane Lontan &mdash; Notification automatique</p>
    </div>
</div>
</body>
</html>
