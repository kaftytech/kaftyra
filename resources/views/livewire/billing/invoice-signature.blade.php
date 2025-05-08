<div>
    <h2 class="text-lg font-bold mb-2">Customer Signature for Invoice #{{ $invoiceId }}</h2>

    <canvas id="signature-pad" class="border border-dashed border-gray-400 w-full h-64 rounded"></canvas>

    <div class="mt-3 flex gap-2">
        <button wire:click="clearCanvas" id="clear" class="px-3 py-1 bg-gray-300 rounded">Clear</button>
        <button wire:click="saveSignature" id="save" class="px-3 py-1 bg-blue-600 text-white rounded">Save Signature</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        let signaturePad;

        document.addEventListener('livewire:load', function () {
            const canvas = document.getElementById('signature-pad');

            // Ensure proper canvas dimensions
            canvas.width = canvas.offsetWidth;
            canvas.height = 300;

            signaturePad = new SignaturePad(canvas);

            Livewire.on('clear-signature-pad', () => {
                signaturePad.clear();
            });

            Livewire.on('save-signature-pad', () => {
                if (!signaturePad.isEmpty()) {
                    Livewire.dispatch('signatureSaved', signaturePad.toDataURL());
                } else {
                    alert('Please provide a signature.');
                }
            });
        });
    </script>

</div>
