<div class="text-center">
    <h3>Scanner un Ticket</h3>

    <button id="startScanner">Démarrer le scan</button>

    <video id="scannerVideo" width="100%" height="auto" style="border: 1px solid #ddd;"></video>
    <p>Résultat : <strong id="scannedData">@if($scannedData) {{ $scannedData }} @endif</strong></p>

    <!-- Instascan CDN -->
    <script src="{{ asset('assets/instascan_assets/instascan.min.js')}}"></script>
    <script src="{{ asset('assets/instascan_assets/jquery-3.6.0.min.js')}}"></script>

    <script>
    $(document).ready(function() {
        // Définir les options de scanner
        let scanner = new Instascan.Scanner({
            video: document.getElementById('scannerVideo'),
            continuous: true, // Permet de scanner en continu sans avoir à redémarrer le scan
            mirror: false, // Ne pas inverser l'image de la caméra (utile pour la caméra arrière)
            videoConstraints: {
                facingMode: "environment", // Utilisation de la caméra arrière par défaut
                width: {
                    ideal: 1280
                }, // Résolution idéale
                height: {
                    ideal: 720
                }
            }
        });

        $('#startScanner').click(function() {
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    // Trouver la caméra arrière (back)
                    let backCamera = cameras.find(camera => camera.name.toLowerCase().includes(
                        'back') || camera.name.toLowerCase().includes('rear'));
                    if (backCamera) {
                        scanner.start(backCamera); // Démarrer avec la caméra arrière
                    } else {
                        alert('Aucune caméra arrière détectée');
                    }
                } else {
                    alert('Aucune caméra détectée');
                }
            }).catch(function(e) {
                console.error(e);
            });
        });

        scanner.addListener('scan', function(content) {
            $('#scannedData').text(content); // Met à jour l'affichage du contenu scanné
            Livewire.dispatch('processScan', content); // Envoie à Livewire
        });
    });
    </script>
</div>
