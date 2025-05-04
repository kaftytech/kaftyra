<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use App\Models\Company;  // Ensure you have the Company model
class CompanyForm extends Component
{
    public $company;
    public $name, $registration_number, $email, $phone, $address, $notes;
    public $city, $state, $country, $postal_code, $bank_account_number, $bank_ifsc_code;
    public $contact_person, $contact_person_email, $contact_person_mobile;
    public $address_line_1, $address_line_2;

    // Mount method to handle create or edit
    public function mount($companyId = null)
    {
        $this->company = Company::find(1);

        if ($this->company) {
            // Fill form fields with existing company data
            $this->name = $this->company->name;
            $this->registration_number = $this->company->registration_number;
            $this->email = $this->company->email;
            $this->phone = $this->company->phone;
            $this->address_line_1 = $this->company->address_line_1;
            $this->address_line_2 = $this->company->address_line_2;
            $this->city = $this->company->city;
            $this->state = $this->company->state;
            $this->country = $this->company->country;
            $this->postal_code = $this->company->postal_code;
            $this->bank_account_number = $this->company->bank_account_number;
            $this->bank_ifsc_code = $this->company->bank_ifsc_code;
            $this->contact_person = $this->company->contact_person;
            $this->contact_person_email = $this->company->contact_person_email;
            $this->contact_person_mobile = $this->company->contact_person_mobile;
        }
    }

    // Method to handle form submission for create and update
    public function save()
    {
        $validatedData = $this->validate([
            'name' => 'required|string',
            'registration_number' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address_line_1' => 'required|string',
            'address_line_2' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string',
            // 'bank_account_number' => 'nullable|string',
            // 'bank_ifsc_code' => 'nullable|string',
            'contact_person_mobile' => 'nullable|string'
        ]);

        if ($this->company) {
            // Update the existing company
            $this->company->update($validatedData);
        } else {
            // Create a new company
            Company::create($validatedData);
        }

        return redirect()->route('company.index')->with('success', 'Company saved successfully.');
    }
    public function render()
    {
        return view('livewire.setting.company-form');
    }
}
