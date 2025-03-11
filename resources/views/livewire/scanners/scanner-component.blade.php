<div class="text-center">
    <h3>Scanner un Ticket</h3>

    <button id="startScanner" class="btn btn-success text-start">Démarrer le scan</button>

    <video id="scannerVideo" width="100%" height="auto" style="border: 1px solid #ddd; margin-top: 15px;"></video>

    @if ($scannedData)
    {{ $scannedData }}
    @endif
    <!-- <div class="modal fade" id="scrollable-modal-show-details" tabindex="-1" role="dialog"
        aria-labelledby="scrollableModalTitle1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Hi !!!
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="scrollable-modal-show-details" tabindex="-1" role="dialog"
        aria-labelledby="scrollableModalTitle1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle1">
                        Text
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Hi !!!
                </div>
            </div>
        </div>
    </div>


    <!-- Instascan CDN -->
    <script src="{{ asset('assets/cam/instascan.min.js') }}"></script>
    <script src="{{ asset('assets/cam/jquery.min.js') }}"></script>

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
                            icon: "danger",
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
                        icon: "danger",
                        title: "Oups !!! Aucune caméra détectée."
                    });
                }
            }).catch(function(e) {
                console.error(e);
            });
        });

        scanner.addListener('scan', function(content) {
            $('#scannedData').text(content); // Met à jour l'affichage du contenu scanné
            Livewire.dispatch('processScan', content); // Envoie à Livewire

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
        });
    });
    </script>
</div>
