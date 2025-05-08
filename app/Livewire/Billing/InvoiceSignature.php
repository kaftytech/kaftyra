<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class InvoiceSignature extends Component
{
    public $invoiceId;

    protected $listeners = ['signatureSaved'];

    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function clearCanvas()
    {
        $this->dispatch('clearSignaturePad');
    }
    
    public function saveSignature()
    {
        $this->dispatch('callFunction', function () {
            return ['function' => 'saveSignaturePad'];
        });
    }
    
    public function signatureSaved($dataUrl)
    {
        $image = str_replace('data:image/png;base64,', '', $dataUrl);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signatures/invoice_' . $this->invoiceId . '_' . time() . '.png';
        Storage::disk('public')->put($imageName, base64_decode($image));

        // Save to DB
        \App\Models\InvoiceSignature::create([
            'invoice_id' => $this->invoiceId,
            'signature_path' => $imageName,
        ]);

        session()->flash('message', 'Signature saved successfully.');
    }

    public function render()
    {
        return view('livewire.billing.invoice-signature');
    }
}
