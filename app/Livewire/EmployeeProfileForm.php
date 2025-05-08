<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\User;
use Livewire\WithPagination;

class EmployeeProfileForm extends Component
{
    use WithPagination;

    public $showModal = false;
    public $employeeId;
    public $user_id, $designation, $department, $joining_date, $dob, $gender,
           $emergency_contact, $national_id, $employee_code, $address, $phone;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'designation' => 'required|string',
        'department' => 'nullable|string',
        'joining_date' => 'nullable|date',
        'dob' => 'nullable|date',
        'gender' => 'nullable|string',
        'emergency_contact' => 'nullable|string',
        'national_id' => 'nullable|string',
        'employee_code' => 'nullable|string',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.employee-profile-form', [
            'employees' => Employee::latest()->paginate(10),
            'users' => User::all(),
        ]);
    }

    public function openModal()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $employee->id;
        $this->user_id = $employee->user_id;
        $this->designation = $employee->designation;
        $this->department = $employee->department;
        $this->joining_date = $employee->joining_date;
        $this->dob = $employee->dob;
        $this->gender = $employee->gender;
        $this->emergency_contact = $employee->emergency_contact;
        $this->national_id = $employee->national_id;
        $this->employee_code = $employee->employee_code;
        $this->address = $employee->address;
        $this->phone = $employee->phone;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Employee::updateOrCreate(
            ['id' => $this->employeeId],
            [
                'user_id' => $this->user_id,
                'designation' => $this->designation,
                'department' => $this->department,
                'joining_date' => $this->joining_date,
                'dob' => $this->dob,
                'gender' => $this->gender,
                'emergency_contact' => $this->emergency_contact,
                'national_id' => $this->national_id,
                'employee_code' => $this->employee_code,
                'address' => $this->address,
                'phone' => $this->phone,
            ]
        );

        session()->flash('message', 'Employee saved successfully!');
        $this->showModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->employeeId = null;
        $this->user_id = null;
        $this->designation = null;
        $this->department = null;
        $this->joining_date = null;
        $this->dob = null;
        $this->gender = null;
        $this->emergency_contact = null;
        $this->national_id = null;
        $this->employee_code = null;
        $this->address = null;
        $this->phone = null;
    }
}
