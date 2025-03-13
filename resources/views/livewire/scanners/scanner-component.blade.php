<div class="text-center">
    <h3>Scanner un Ticket</h3>

    <button id="startScanner" class="text-start btn btn-success">Démarrer le scan</button>

    <video id="scannerVideo" width="100%" height="auto" style="border: 1px solid #ddd; margin-top: 15px;"></video>

    <p>Résultat : <strong id="scannedData">@if($scannedData) {{ $scannedData }} @endif</strong></p>

    <div wire:ignore id="info-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="p-4 modal-body">
                    <div class="text-center">
                        <i class="text-info dripicons-information h1"></i>
                        <h4 class="mt-2">Informations</h4>
                        <p class="mt-3">
                        <p><strong>Code du Ticket :</strong> <span id="ticket-code">-</span></p>
                        <p><strong>Nom de l'Événement :</strong> <span id="event-name">-</span></p>
                        <p><strong>Date de Création :</strong> <span id="created-date">-</span></p>
                        <p><strong>Statut :</strong> <span id="is-used">-</span></p>
                        </p>
                        <button type="button" class="my-2 btn btn-info" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>



    <script src="{{ asset('assets/cam/instascan.min.js') }}"></script>
    <script src="{{ asset('assets/cam/jquery.min.js') }}"></script>

    <script>
        // document.addEventListener('livewire:initialized', () => {
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
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "error",
                            title: "Oups !!! Aucune caméra arrière détectée."
                        });
                    }
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Oups !!! Aucune caméra détectée."
                    });
                }
            }).catch(function(e) {
                console.error(e);
            });
        });

        scanner.addListener('scan', function(content) {
            $('#scannedData').text(content); // Met à jour l'affichage du contenu scanné
            // scanner.stop();
            // backCamera = null;
            fetch('/qr-code/scanners', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Important pour Laravel
                    },
                    body: JSON.stringify({
                        content: content // Envoie le JSON du QR code
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Erreur: ' + data.error);
                    } else {
                        // Injecter les données dans la vue
                        $('#ticket-code').text(data.code);
                        $('#event-name').text(data.eventName);
                        $('#created-date').text(data.created);
                        $('#is-used').text(data.is_used);

                        // Afficher le modal
                        $('#info-alert-modal').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du scan:', error);
                });
        });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "success",
            title: "Scanner avec succès !!!"
        });
        // });
    </script>
</div>