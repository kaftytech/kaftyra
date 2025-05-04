@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-medium">User Details</h2>
    <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Details and informations about {{$user->name}}.
    </p>
    <hr class="my-4">
    <div class="mt-4 pt-6">
        <div class="border-t border-white">
            <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Id #
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{$user->id}}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Name
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if (url()->current() == route('admin.users.edit', $user->id))
                        <form action="{{route('admin.users.update', $user->id)}}" method="post">
                        @csrf
                        <x-input id="name" class="block w-full" type="text" name="name" value="{{old('name') !== NULL ? old('name') : $user->name}}" required autofocus autocomplete="name" />
                        @else
                        {{isset($user->name) ? $user->name : '--'}}
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Email
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if (url()->current() == route('admin.users.edit', $user->id))
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{old('email') !== NULL ? old('email') : $user->email}}" autocomplete="email" />
                        @else
                        <div class=" flex">
                            {{isset($user->email) ? $user->email : '--'}}
                            @if ($user->hasVerifiedEmail())
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 ml-2 text-green-700">
                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 ml-2 text-red-600">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                            </svg>
                            @endif
                        </div>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Phone
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if (url()->current() == route('admin.users.edit', $user->id))
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" value="{{old('phone') !== NULL ? old('phone') : $user->phone}}" autocomplete="phone" pattern="[6-9]{1}[0-9]{9}" minlength="10" maxlength="10" />
                        @else
                        <div class=" flex">
                            {{isset($user->phone) ? $user->phone : '--'}}
                            @if (isset($user->phone_verified_at) && !empty($user->phone_verified_at) && isset($user->phone) && !empty($user->phone))
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 ml-2 text-green-700">
                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                            </svg>
                            @elseif(isset($user->phone) && !empty($user->phone))
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 ml-2 text-red-600">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @endif
                        @endif
                    </dd>
                </div>
                {{-- <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        WhatsApp and SMS opt-in
                    </dt>
                    <dd class="mt-1 text-sm {{@$user->opt_in==1 ? 'text-green-700' : 'text-red-600'}} font-medium sm:mt-0 sm:col-span-2">
                        {{@$user->opt_in==1 ? 'YES' : 'NO'}}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        WhatsApp and SMS opt-in time
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{@$user->opt_in_time ? @$user->opt_in_time : '--'}}
                    </dd>
                </div> --}}
                @if (url()->current() == route('admin.users.edit', $user->id))
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Change Password
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="relative">
                            <input id="password" class="block mt-1 w-full pr-10" type="password" name="changed_password" autocomplete="new-password" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-auto">
                                <button id="togglePassword" type="button" class="bg-transparent text-gray-500 hover:text-gray-900 focus:outline-none">
                                    Show
                                </button>
                            </div>
                        </div>
                    </dd>
                </div>
                @endif
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        @if (url()->current() == route('admin.users.edit', $user->id))
                        <button class=" bg-blue-700 text-white font-semibold px-3 py-1 rounded hover:bg-blue-600 active:bg-blue-500" type="submit">Update</button>
                        </form>
                        <a href="{{route('admin.users.show', $user->id)}}">
                            <button class=" bg-red-700 text-white font-semibold px-3 py-1 rounded hover:bg-red-600 active:bg-red-500">Cancel</button>
                        </a>
                        @else
                            <a href="{{route('admin.users.edit', $user->id)}}">
                                <button class=" bg-blue-700 text-white font-semibold px-3 py-1 rounded hover:bg-blue-600 active:bg-blue-500">Edit</button>
                            </a>
                        @endif
                    </dt>
                </div>
            </dl>
        </div>
    </div>
</div>

<div class="bg-white p-6 mt-5 rounded shadow">
    <h2 class="text-2xl font-medium">Roles</h2>
    <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Role access of {{ucwords($user->name)}} role.
    </p>
    <hr class="my-4">
    <div class="mt-2 pt-6">
        <form action="{{route('admin.users.updateRoles', $user->id)}}" method="post">
            @csrf
        <div class="border-t border-white">
            <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 grid col-span-2 lg:col-span-3 md:col-span-3 grid-cols-2 lg:grid-cols-3 md:grid-cols-3">
                        @forelse($roles as $role)
                        <div class="form-check px-4 py-4 flex">
                            <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" value="{{$role->id}}" name="roles[]" id="{{$role->name}}" {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                            <label class="form-check-label inline-block text-gray-800" for="{{$role->name}}">
                                {{ucwords($role->display_name)}} 
                            </label>
                        </div>
                        @empty
                        <div class=" px-4 py-4">
                            No Role records are found, Please create role.
                        </div>
                        @endforelse
                    </dd>
                </div>
            </dl>
        </div>
        <div class="border-t border-white">
            <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 justify-center">
                        <button type="submit" class=" bg-blue-700 text-white font-semibold px-3 py-1 rounded hover:bg-blue-600 active:bg-blue-500">Assign Roles</button>
                    </dd>
                </div>
            </dl>
        </div>
        </form>
    </div>
</div>

<div class="bg-white p-6 mt-5 rounded shadow">
    <h2 class="text-2xl font-medium">Permissions</h2>
    <p class="mt-1 max-w-2xl text-sm text-gray-500">
        Permission access of {{ucwords($user->name)}} role.
    </p>
    <hr class="my-4">
    <div class="mt-2 pt-6">
        <form action="{{route('admin.users.updatePermissions', $user->id)}}" method="post">
            @csrf
        <div class="border-t border-white">
            <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 grid col-span-2 lg:col-span-3 md:col-span-3 grid-cols-2 lg:grid-cols-3 md:grid-cols-3">
                        @forelse($permissions as $permission)
                        <div class="form-check px-4 py-4 flex">
                            <input class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" value="{{$permission->id}}" name="permissions[]" id="{{$permission->name}}" {{ $user->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                            <label class="form-check-label inline-block text-gray-800" for="{{$permission->name}}">
                                {{ucwords($permission->display_name)}} 
                            </label>
                        </div>
                        @empty
                        <div class=" px-4 py-4">
                            No Permissions records are found, Please create permision.
                        </div>
                        @endforelse
                    </dd>
                </div>
            </dl>
        </div>
        <div class="border-t border-white">
            <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 justify-center">
                        <button type="submit" class=" bg-blue-700 text-white font-semibold px-3 py-1 rounded hover:bg-blue-600 active:bg-blue-500">Assign</button>
                    </dd>
                </div>
            </dl>
        </div>
        </form>
    </div>
</div>
@endsection
