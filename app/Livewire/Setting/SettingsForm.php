<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use App\Models\Settings;

class SettingsForm extends Component
{
    public $site_name;
    public $date_format;
    public $timezone;
    public $items_per_page;

    public function mount()
    {
        $this->site_name = setting('site_name');
        $this->date_format = setting('date_format', 'd/m/Y');
        $this->timezone = setting('timezone', config('app.timezone'));
        $this->items_per_page = setting('items_per_page', 10);
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:100',
            'date_format' => 'required|string',
            'timezone' => 'required|string',
            'items_per_page' => 'required|numeric|min:1|max:100',
        ]);

        $data = [
            'site_name' => $this->site_name,
            'date_format' => $this->date_format,
            'timezone' => $this->timezone,
            'items_per_page' => $this->items_per_page,
        ];

        foreach ($data as $key => $value) {
            Settings::updateOrCreate(['key' => $key], [
                'value' => $value,
                'updated_by' => auth()->id(),
            ]);
        }

        session()->flash('success', 'Settings updated successfully!');
    }
    public function render()
    {
        return view('livewire.setting.settings-form');
    }
}
