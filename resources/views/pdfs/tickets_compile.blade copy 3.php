<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tickets QR Code</title>
    <style>
        .ticket-row {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }

        .qr-container {
            text-align: center;
            margin-right: 10px;
        }

        .qr-container img {
            width: 120px;
            /* Ajuste la taille du QR code */
            display: block;
            margin: 0 auto;
        }

        .ticket {
            width: 95%;
            height: 200px;
            /* Ajuste la taille du ticket */
        }

        .scan-text {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
    </style>
</head>


<body>
    @foreach($tickets as $ticket)
    <div class="ticket-row" style="position: relative;">
        <div class="qr-container" style="position: absolute; left: 25px; top: 87px; z-index: 1000;">
            <img src="{{ storage_path('app/public/' . $ticket->qr_code) }}" alt="QR Code"
                style="width: 100px; height: 100px;">
        </div>
        <img src="{{ storage_path('app/public/' . $event->branding_image) }}" class="ticket" alt="Ticket">
    </div>
    @endforeach
</body>

</html>
