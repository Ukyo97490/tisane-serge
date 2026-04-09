<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rappel de retrait</title>
<style>
    body { font-family: 'Georgia', serif; background: #f9f6ef; margin: 0; padding: 20px; color: #382318; }
    .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .header { padding: 32px; text-align: center; }
    .body { padding: 32px; }
    .highlight-box { border-radius: 12px; padding: 24px; text-align: center; margin: 24px 0; }
    .info-block { background: #f9f6ef; border-radius: 8px; padding: 16px; margin: 16px 0; }
    .info-block h3 { margin: 0 0 8px; font-size: 14px; }
    .info-block p { margin: 4px 0; font-size: 14px; color: #523421; }
    .footer { background: #2d6840; color: #b0d9bc; padding: 20px 32px; text-align: center; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
    <div class="header" style="background: {{ $type === '1h' ? '#fef9ec' : '#f0f7f2' }}">
        <div style="font-size:48px;margin-bottom:12px;">{{ $type === '1h' ? '⏰' : '🗓️' }}</div>
        <h1 style="margin:0;font-size:22px;color: {{ $type === '1h' ? '#7d4606' : '#2d6840' }};">
            {{ $type === '1h' ? 'Retrait dans 1 heure !' : 'Rappel : votre retrait est demain' }}
        </h1>
    </div>

    <div class="body">
        <p>Bonjour <strong>{{ $order->customer_name }}</strong>,</p>

        @if($type === '1h')
            <p>Votre commande <strong>{{ $order->reference }}</strong> est prête à être récupérée dans <strong>environ 1 heure</strong>.</p>
        @else
            <p>Ce message est un rappel que votre commande <strong>{{ $order->reference }}</strong> est à récupérer <strong>demain</strong>.</p>
        @endif

        <div class="highlight-box" style="background: {{ $type === '1h' ? '#fef9ec' : '#f0f7f2' }}; border: 2px solid {{ $type === '1h' ? '#fbdc8a' : '#b0d9bc' }}">
            <div style="font-size:28px;font-weight:bold;color: {{ $type === '1h' ? '#7d4606' : '#2d6840' }}">
                {{ \Carbon\Carbon::parse($order->pickup_date)->locale('fr')->isoFormat('dddd D MMMM') }}
            </div>
            <div style="font-size:22px;margin-top:8px;color: {{ $type === '1h' ? '#a86208' : '#3a8250' }}">
                {{ substr($order->pickup_time, 0, 5) }}
            </div>
        </div>

        <div class="info-block">
            <h3 style="color:#2d6840;">📍 Ou venir récupérer</h3>
            <p><strong>{{ $order->pickupPoint->name }}</strong></p>
            <p>{{ $order->pickupPoint->full_address }}</p>
        </div>

        <div class="info-block">
            <h3 style="color:#2d6840;">💶 Paiement sur place</h3>
            <p>Montant total : <strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong></p>
            <p>Règlement en espèces ou par chèque directement sur le stand.</p>
        </div>

        <p style="font-size:13px;color:#8c5e3a;">
            Si vous avez des questions, n'hésitez pas à nous contacter.
        </p>
    </div>

    <div class="footer">
        <p>Tisane Lontan &mdash; Saveurs naturelles de La Réunion</p>
    </div>
</div>
</body>
</html>
