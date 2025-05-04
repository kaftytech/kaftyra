<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Stevebauman\Location\Facades\Location;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function index(Request $request){
        Log::debug('Admin/UserController:index - method begins');
        
        return view('admin.users.index');
    }
    public function create(){
        Log::debug('Admin/UserController:create - method begins');
        return view('admin.users.create');
    }
    public function store(Request $request){
        Log::debug('Admin/UserController:store - method begins');

        $input = $request->except('_token');
        $input['terms'] = 'on';

        $identifierFlag = 'BOTH-NULL';
        if(empty($input['email']) && empty($input['phone'])) $identifierFlag = "BOTH-NULL";
        elseif(!empty($input['email']) && !empty($input['phone'])) $identifierFlag = "EMAIL|PHONE";
        elseif(empty($input['email']) && !empty($input['phone'])) $identifierFlag = "PHONE";
        elseif(!empty($input['email']) && empty($input['phone'])) $identifierFlag = "EMAIL";

        Log::debug('Fortify/CreateNewUser::create - identifierFlag is : '.$identifierFlag);
        Log::debug('Fortify/CreateNewUser::create - email  is : '.$input['email']);
        Log::debug('Fortify/CreateNewUser::create - phone  is : '.$input['phone'].'length is :'.strlen($input['phone']));

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email'  => ((str_contains($identifierFlag,'EMAIL')) ? 'unique:users,email|required|string|email|max:255' : 'nullable|string|email|unique:users,email'),
            'phone'  => ((str_contains($identifierFlag,'PHONE')) ? 'unique:users,phone|required|numeric|min:10':'nullable|numeric|min:10|unique:users,phone'),
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
        ]);
        
        session()->flash('success','User has been created !!');
        return redirect()->route('admin.users.show', $user->id);
    }
    public function show($id){
        Log::debug("Admin/UserController:show - method begins");

        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();

        Log::debug("Admin/UserController:show - method ends");
        // dd($user->auditlogs);

        return view('admin.users.show')->with('user', $user)->with('roles',$roles)->with('permissions', $permissions);
    }
    public function edit($id){
        Log::debug("Admin/UserController:edit - method begins");

        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();

        Log::debug("Admin/UserController:edit - method ends");

        return view('admin.users.show')->with('user',$user)->with('roles',$roles)->with('permissions', $permissions);
    }
    public function update(Request $request, $id){

        $user = User::findOrFail($id);
        // dd($request->all());

        $existingEmail = User::where('email', $request->email)->whereNot('id', $id)->get();
        $existingPhone = User::where('email', $request->phone)->whereNot('id', $id)->get();

        if (isset($existingEmail) && count($existingEmail) > 0) {
            session()->flash('failed', $request->email.' Email is already exist, Please try others !!');
            return redirect()->back()->withInput();
        }
        if (isset($existingPhone) && count($existingPhone) > 0) {
            session()->flash('failed', $request->phone.' phone is already exist, Please try others !!');
            return redirect()->back()->withInput();
        }

        $input = array_filter($request->except('_token', 'changed_password'), function ($value) {
            // Remove the key if its value is null
            return $value !== null;
        });
        $input['opt_in'] = @$request->opt_in == "on" ? 1 : 0;
        $input['password'] = isset($request->changed_password)&&$request->changed_password!==NULL ? Hash::make($request->changed_password) : NULL;
        // dd($input);
        $originalValues = $user->getOriginal();
        unset($originalValues['password']);

        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }
        if ($user->isDirty()) {
            $user->save();

            $updatedUser = User::select( 'name', 'email', 'phone')->findOrFail($id);

            // Compare old and new values for audit log
            $changes = [
                'old' => [],
                'new' => []
            ];
            unset($input['password']);

            foreach ($input as $key => $value) {
                if ($originalValues[$key] !== $updatedUser->$key) {
                    $changes['old'][$key] = $originalValues[$key];
                    $changes['new'][$key] = $updatedUser->$key;
                }
            }

            // Create an audit log entry
            $auditLog = [
                'user_id' => auth()->user()->id,
                'event' => "User Update from Admin login",
                'old_values' => json_encode($changes['old']),
                'new_values' => json_encode($changes['new']),
                'ip_address' => \Request::getClientIp(),
                'comments' => "User updated from admin login by ".auth()->user()->name.".",
            ];

            $user->auditLogs()->create($auditLog);

            session()->flash('success','User has been updated !!');
            return redirect()->route('admin.users.show', $user->id);
        } else {
            session()->flash('failed','No values got changed !!');
            return redirect()->back()->withInput();
        }
    }
    public function updateRoles(Request $request, $id){
        $user = User::findOrFail($id);
        
        $input = $request->except('_token');

        $roles = collect($input['roles'])->mapWithKeys(function ($roleId) {
            return [$roleId => ['user_type' => \App\Models\User::class]];
        })->toArray();
        
        $user->roles()->sync($roles);        

        session()->flash('success','Roles has been synced to this User !!');
        return redirect()->route('admin.users.show', $user->id);
    }
    public function updatePermissions(Request $request, $id){
        $user = User::findOrFail($id);
        
        $input = $request->except('_token');
        // dd($input);

        $user->syncPermissions(!empty($input['permissions']) ? $input['permissions'] : []);

        session()->flash('success','Roles has been synced to this User !!');
        return redirect()->route('admin.users.show', $user->id);
    }
}
