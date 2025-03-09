<div class="text-center">
    <h3>Scanner un Ticket</h3>

    <button id="startScanner">Démarrer le scan</button>

    <video id="scannerVideo" width="300" height="200"></video>
    <p>Résultat : <strong id="scannedData">@if($scannedData) {{ $scannedData }} @endif</strong></p>

    <!-- Instascan CDN -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        let scanner = new Instascan.Scanner({
            video: document.getElementById('scannerVideo')
        });

        $('#startScanner').click(function() {
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('Aucune caméra détectée');
                }
            }).catch(function(e) {
                console.error(e);
            });
        });

        scanner.addListener('scan', function(content) {
            $('#scannedData').text(content); // Met à jour l'affichage
            Livewire.dispatch('processScan', content); // Envoie à Livewire
        });
    });
    </script>
</div>