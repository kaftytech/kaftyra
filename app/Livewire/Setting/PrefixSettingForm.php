<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use App\Models\PrefixSetting;

class PrefixSettingForm extends Component
{
    public $settings = [];

    public $types = [
        'Invoice' => 'Invoice',
        'Purchase' => 'Purchase',
        'CreditNote' => 'Credit Note',
        'OrderRequest' => 'Order Request',
    ];    

    public function mount()
    {
        foreach ($this->types as $model => $label) {
            // Use the correct model reference
            $setting = PrefixSetting::firstOrNew(['prefix_for' => $model]);

            $this->settings[$model] = [
                'prefix' => $setting->prefix ?? $this->defaultPrefix($model),
                'suffix' => $setting->suffix ?? '',
                'start_number' => $setting->start_number ?? 1,
            ];
        }
    }


    public function save($model)
    {
        // Ensure the model is passed correctly, here we use the same model name as key
        PrefixSetting::updateOrCreate(
            ['prefix_for' => $model],
            [
                'prefix' => $this->settings[$model]['prefix'],
                'suffix' => $this->settings[$model]['suffix'],
                'start_number' => $this->settings[$model]['start_number'],
                'current_number' => $this->settings[$model]['start_number'],
            ]
        );

        session()->flash('message', 'Saved for ' . $this->types[$model]);
    }


    public function defaultPrefix($model)
    {
        return match ($model) {
            'Invoice' => 'INV',
            'Purchase' => 'PUR',
            'CreditNote' => 'CRN',
            'OrderRequest' => 'ORD',
            default => '',
        };
    }
    
    public function render()
    {
        return view('livewire.setting.prefix-setting-form');
    }
}
