@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md overflow-hidden">
        <!-- User profile header -->
        <div class="p-8">
            <div class="flex items-center gap-6">
                <div class="h-24 w-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{$user->name}}</h1>
                    <p class="text-indigo-600 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        User ID: {{$user->id}}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs navigation -->
        <div class="bg-white border-b border-gray-200">
            <div class="px-8">
                <nav class="-mb-px flex space-x-8">
                    <button id="profileTab" onclick="openTab('profile')" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Profile Information
                    </button>
                    <button id="rolesTab" onclick="openTab('roles')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Roles
                    </button>
                    <button id="permissionsTab" onclick="openTab('permissions')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Permissions
                    </button>
                </nav>
            </div>
        </div>

        <!-- Profile information tab -->
        <div id="profile" class="tab-content">
            <div class="p-8">
                @if (url()->current() == route('admin.users.edit', $user->id))
                <form action="{{route('admin.users.update', $user->id)}}" method="post" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{old('name') !== NULL ? old('name') : $user->name}}" required>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{old('email') !== NULL ? old('email') : $user->email}}">
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <div class="mt-1">
                                <input type="text" name="phone" id="phone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" value="{{old('phone') !== NULL ? old('phone') : $user->phone}}" pattern="[6-9]{1}[0-9]{9}" minlength="10" maxlength="10">
                            </div>
                        </div>

                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700">Branch</label>
                            <div class="mt-1">
                                <select id="branch" name="branch_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Branch</option>
                                    {{-- This is where you'll populate branch options from your controller --}}
                                    @if(isset($branches))
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ (isset($user->branch_id) && $user->branch_id == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Change Password</label>
                            <div class="mt-1 relative">
                                <input type="password" name="changed_password" id="password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10" >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <button id="togglePassword" type="button" class="text-indigo-600 hover:text-indigo-900 focus:outline-none">
                                        Show
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{route('admin.users.show', $user->id)}}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update
                        </button>
                    </div>
                </form>
                @else
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{isset($user->name) ? $user->name : '--'}}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-lg text-gray-900 flex items-center">
                                    {{isset($user->email) ? $user->email : '--'}}
                                    @if ($user->hasVerifiedEmail())
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-2 text-green-700">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-2 text-red-600">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 00-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
                                    </svg>
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-lg text-gray-900 flex items-center">
                                    {{isset($user->phone) ? $user->phone : '--'}}
                                    @if (isset($user->phone_verified_at) && !empty($user->phone_verified_at) && isset($user->phone) && !empty($user->phone))
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-2 text-green-700">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                    @elseif(isset($user->phone) && !empty($user->phone))
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-2 text-red-600">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 00-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
                                    </svg>
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Branch</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{isset($user->branch) ? $user->branch->name : '--'}}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end">
                        <a href="{{route('admin.users.edit', $user->id)}}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Roles tab -->
        <div id="roles" class="tab-content hidden">
            <div class="p-8">
                <form action="{{route('admin.users.updateRoles', $user->id)}}" method="post">
                    @csrf
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">User Roles</h3>
                            <div class="mt-5 space-y-2">
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                    @forelse($roles as $role)
                                    <div class="relative flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input id="{{$role->name}}" name="roles[]" type="checkbox" value="{{$role->id}}" {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="{{$role->name}}" class="font-medium text-gray-700">{{ucwords($role->display_name)}}</label>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-500">No Role records are found. Please create role.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Assign Roles
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Permissions tab -->
        <div id="permissions" class="tab-content hidden">
            <div class="p-8">
                <form action="{{route('admin.users.updatePermissions', $user->id)}}" method="post">
                    @csrf
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">User Permissions</h3>
                            <div class="mt-5 space-y-2">
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                    @forelse($permissions as $permission)
                                    <div class="relative flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input id="{{$permission->name}}" name="permissions[]" type="checkbox" value="{{$permission->id}}" {{ $user->permissions->contains('name', $permission->name) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="{{$permission->name}}" class="font-medium text-gray-700">{{ucwords($permission->display_name)}}</label>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-sm text-gray-500">No Permission records are found. Please create permission.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Assign Permissions
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    function openTab(tabName) {
        // Hide all tab contents
        const tabContents = document.getElementsByClassName('tab-content');
        for (let i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.add('hidden');
        }
        
        // Remove active class from all tabs
        const tabs = document.querySelectorAll('button[id$="Tab"]');
        tabs.forEach(tab => {
            tab.classList.remove('border-indigo-500', 'text-indigo-600');
            tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        // Show the selected tab content
        document.getElementById(tabName).classList.remove('hidden');
        
        // Add active class to the clicked tab
        document.getElementById(tabName + 'Tab').classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        document.getElementById(tabName + 'Tab').classList.add('border-indigo-500', 'text-indigo-600');
    }
    
    // Password toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const password = document.getElementById('password');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.textContent = type === 'password' ? 'Show' : 'Hide';
            });
        }
    });
</script>
@endsection