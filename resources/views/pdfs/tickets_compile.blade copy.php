<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tickets QR Code</title>
</head>

<body>

    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 20px; width: 100%;">
        @foreach ($tickets->chunk(2) as $ticketPair)
        <div style="display: flex; justify-content: space-between; width: 100%;">
            @foreach ($ticketPair as $ticket)
            <div style="display: flex; align-items: center; border: 2px dashed #000; padding: 10px; width: 48%;">
                <img src="{{ storage_path('app/public/' . $event->branding_image) }}"
                    style="width: 70%; height: 120px; object-fit: cover; border-right: 2px dashed #000;">
                <img src="{{ storage_path('app/public/' . $ticket->qr_code) }}"
                    style="width: 30%; height: 120px; object-fit: cover; padding-left: 10px;">
            </div>
            @endforeach
        </div>
        @endforeach
    </div>


</body>

</html>

</html>

</html>

</html>

</html>

</html>

</html>

</html>

</html>