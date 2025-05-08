<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signature Pad</title>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Customer Signature</h2>

        <canvas id="signature-pad" class="border border-gray-400 w-full rounded" style="height: 300px;"></canvas>

        <div class="mt-4 flex gap-4">
            <button onclick="clearSignature()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Clear
            </button>
            <button onclick="saveSignature()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Save
            </button>
        </div>

        <div id="output" class="mt-4"></div>
    </div>

    <script>
        let signaturePad;

        window.addEventListener('load', () => {
            const canvas = document.getElementById('signature-pad');

            // Resize canvas for drawing
            canvas.width = canvas.offsetWidth;
            canvas.height = 300;

            signaturePad = new SignaturePad(canvas);
        });

        function clearSignature() {
            signaturePad.clear();
            document.getElementById('output').innerHTML = '';
        }

        function saveSignature() {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                return;
            }

            const dataURL = signaturePad.toDataURL();

            // Preview or send it to backend via AJAX/fetch
            document.getElementById('output').innerHTML = `
                <p class="text-sm text-gray-600">Signature saved:</p>
                <img src="${dataURL}" class="mt-2 border rounded max-w-full" />
            `;

            // To send to backend:
            // fetch('/save-signature', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify({ signature: dataURL })
            // });
        }
    </script>
</body>
</html>
