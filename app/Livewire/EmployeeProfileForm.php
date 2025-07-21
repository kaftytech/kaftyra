<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use Livewire\WithPagination;
use DB;
class EmployeeProfileForm extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $showDetails = false;
    public $selectedEmployee;
    public $employeeId;
    public $selectedBranches = [];
    public $selectedRoles = [];
    public $user_id, $designation, $department, $joining_date, $dob, $gender,
           $emergency_contact, $national_id, $address, $phone, $employee_name, $email;

    protected $rules = [
        'employee_name' => 'required|string',
        'email' => 'required|email',
        'designation' => 'required|string',
        'department' => 'nullable|string',
        'joining_date' => 'nullable|date',
        'dob' => 'nullable|date',
        'gender' => 'nullable|string',
        'emergency_contact' => 'nullable|string',
        'national_id' => 'nullable|string',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
        'selectedBranches' => 'required|array|min:1',
        'selectedRoles' => 'required|array|min:1',
    ];

    public function render()
    {
        return view('livewire.employee-profile-form', [
            'employees' => Employee::latest()->paginate(10),
            'users' => User::all(),
            'branches' => Branch::all(),
            'roles' => Role::all()
        ]);
    }
    public function view($id)
    {
        $this->selectedEmployee = Employee::findOrFail($id);
        $this->showDetails = true;
    }
    public function getAuditLogsProperty()
    {
        return $this->selectedEmployee
            ? $this->selectedEmployee->auditLogs()->latest()->paginate(10)
            : collect(); // or null
    }


    public function closeDetails()
    {
        $this->selectedEmployee = null;
        $this->showDetails = false;
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
        $this->employee_name = $employee->employee_name;
        $this->email = $employee->email;
        $this->designation = $employee->designation;
        $this->department = $employee->department;
        $this->joining_date = $employee->joining_date;
        $this->dob = $employee->dob;
        $this->gender = $employee->gender;
        $this->emergency_contact = $employee->emergency_contact;
        $this->national_id = $employee->national_id;
        $this->address = $employee->address;
        $this->phone = $employee->phone;
        $this->showModal = true;
        $this->editMode = true;
        $user = $employee->user;
        $this->selectedBranches = $user ? $user->branches->pluck('id')->toArray() : [];
        $this->selectedRoles = $user ? $user->roles->pluck('id')->toArray() : [];
        // dd($user);

    }

   public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Save or update employee
            $employee = Employee::updateOrCreate(
                ['id' => $this->employeeId],
                [
                    'employee_name' => $this->employee_name,
                    'email' => $this->email,
                    'designation' => $this->designation,
                    'department' => $this->department,
                    'joining_date' => $this->joining_date,
                    'dob' => $this->dob,
                    'gender' => $this->gender,
                    'emergency_contact' => $this->emergency_contact,
                    'national_id' => $this->national_id,
                    'address' => $this->address,
                    'phone' => $this->phone,
                ]
            );

            // Step 1: Get selected branch and roles
            $branchId = collect($this->selectedBranches)->first(); // Assuming only one branch is allowed
            $selectedRoleIds = collect($this->selectedRoles)->toArray();

            $selectedRoles = Role::whereIn('id', $selectedRoleIds)->pluck('name', 'id')->toArray();
            $roleNames = array_values($selectedRoles);

            // Step 2: Define role groups
            $multiRoleAllowed = ['sales_man', 'store_keeper'];
            $restrictedAloneRoles = ['accountant', 'admin', 'super_admin', 'customer'];

            // Step 3: Validate combinations

            // Rule: Restricted roles (accountant etc.) must be alone
            foreach ($restrictedAloneRoles as $restrictedRole) {
                if (in_array($restrictedRole, $roleNames) && count($roleNames) > 1) {
                    session()->flash('failed', ucfirst($restrictedRole) . ' role cannot be combined with any other role.');
                    return redirect()->back();
                }
            }

            // Rule: Only sales_man and store_keeper can be combined
            if (count($roleNames) > 1) {
                $diff = array_diff($roleNames, $multiRoleAllowed);
                if (count($diff) > 0) {
                    session()->flash('failed', 'Only Sales Man and Store Keeper can be combined. Other roles must be used alone.');
                    return redirect()->back();
                }
            }

            // Step 4: Create or update user
            if ($employee->user) {
                $user = $employee->user;
                $user->update([
                    'name' => $this->employee_name,
                    'branch_id' => $branchId, // optional
                ]);
            } else {
                $user = User::create([
                    'name' => $this->employee_name,
                    'email' => $this->email,
                    'password' => bcrypt('password'),
                    'branch_id' => $branchId,
                ]);
            }

            // Step 5: Link user to employee
            $employee->update(['user_id' => $user->id]);

            // Step 6: Sync roles
            $rolesToSync = collect($selectedRoleIds)->mapWithKeys(function ($roleId) {
                return [$roleId => ['user_type' => \App\Models\User::class]];
            })->toArray();

            $user->roles()->sync($rolesToSync);

            // Step 7: Sync branches
            $user->branches()->sync($this->selectedBranches);

            DB::commit();

            session()->flash('message', 'Employee saved successfully!');
            $this->showModal = false;
            $this->resetFields();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
        $this->address = null;
        $this->phone = null;
        $this->selectedBranches = [];
        $this->selectedRoles = [];
        $this->employee_name = null;
        $this->email = null;
    }
}
