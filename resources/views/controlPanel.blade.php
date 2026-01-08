@extends('layouts.app')

@section('title', 'Control Panel')
@php
    $role = Auth::user()->role;
@endphp

@section('content')
<div class="space-y-10">

    <!-- User Management -->
    @if($role === 'SuperAdmin')
    <section>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">User & Role Management</h2>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">System Users</h3>
            <button data-modal-target="addUserModal" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">+ Add User</button>
        </div>

        <!-- Scrollable container -->
        <div class="max-h-96 overflow-y-auto">
            <table class="w-full text-left border">
                <thead class="sticky top-0 bg-gray-100 z-10">
                    <tr>
                        <th class="p-3 border">username</th>
                        <th class="p-3 border">Role</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="p-3 border">{{ $user->username }}</td>
                        <td class="p-3 border">{{ $user->role }}</td>
                        <td class="p-3 border text-green-600">{{ $user->removed ? 'Inactive' : 'Active' }}</td>
                        <td class="p-3 border space-x-2">
                            <button data-modal-target="editUserModal{{ $user->user_id }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                            <form action="{{ route('control-panel.deleteUser', $user->user_id) }}" method="POST" class="inline" onsubmit="return confirmDelete();">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
                    <div id="editUserModal{{ $user->user_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 p-4">
                        <div class="bg-white p-6 rounded-lg w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-lg">Edit User: {{ $user->username }}</h3>
                                <button onclick="closeModal('editUserModal{{ $user->user_id }}')" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
                            </div>
                            <form action="{{ route('control-panel.updateUser', $user->user_id) }}" method="POST" id="editUserForm{{ $user->user_id }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-1">Username</label>
                                    <input type="text" name="username" value="{{ $user->username }}" class="w-full mb-2 p-2 border rounded">
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-1">Password (leave blank to keep current)</label>
                                    <div class="relative">
                                        <input type="password" 
                                            id="editPassword{{ $user->user_id }}" 
                                            name="password" 
                                            class="w-full mb-1 p-2 border rounded pr-10"
                                            placeholder="New password"
                                            onkeyup="checkEditPasswordStrength({{ $user->user_id }})"
                                        />
                                        <button type="button" 
                                                onclick="togglePasswordVisibility('editPassword{{ $user->user_id }}', 'editToggle{{ $user->user_id }}')"
                                                id="editToggle{{ $user->user_id }}"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                                            👁️
                                        </button>
                                    </div>
                                    
                                    <!-- Password Strength Indicator -->
                                    <div class="mt-2">
                                        <div class="flex items-center mb-1">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div id="editStrengthBar{{ $user->user_id }}" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                            </div>
                                            <span id="editPasswordStrength{{ $user->user_id }}" class="ml-2 text-sm">No password</span>
                                        </div>
                                        <div id="editCriteria{{ $user->user_id }}" class="text-xs text-gray-600 grid grid-cols-2 gap-1 mt-2">
                                            <div id="editLength{{ $user->user_id }}" class="flex items-center">
                                                <span class="mr-1">⬜</span> 8+ characters
                                            </div>
                                            <div id="editLowercase{{ $user->user_id }}" class="flex items-center">
                                                <span class="mr-1">⬜</span> Lowercase letter
                                            </div>
                                            <div id="editUppercase{{ $user->user_id }}" class="flex items-center">
                                                <span class="mr-1">⬜</span> Uppercase letter
                                            </div>
                                            <div id="editNumber{{ $user->user_id }}" class="flex items-center">
                                                <span class="mr-1">⬜</span> Contains number
                                            </div>
                                            <div id="editSpecial{{ $user->user_id }}" class="flex items-center">
                                                <span class="mr-1">⬜</span> Special character
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <button type="button" 
                                                onclick="generateEditPassword({{ $user->user_id }})" 
                                                class="bg-amber-900 text-white px-4 py-1 rounded-lg hover:bg-amber-800 text-sm">
                                            Generate Secure Password
                                        </button>
                                        <button type="button" 
                                                onclick="showPasswordTip({{ $user->user_id }})" 
                                                class="ml-2 text-amber-900 hover:text-amber-800 text-sm">
                                            Password Tips
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="editPasswordConfirm{{ $user->user_id }}" 
                                               name="password_confirmation"
                                               class="w-full mb-1 p-2 border rounded pr-10"
                                               placeholder="Confirm password"
                                               onkeyup="checkEditPasswordMatch({{ $user->user_id }})"
                                        />
                                        <button type="button" 
                                                onclick="togglePasswordVisibility('editPasswordConfirm{{ $user->user_id }}', 'editConfirmToggle{{ $user->user_id }}')"
                                                id="editConfirmToggle{{ $user->user_id }}"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                                            👁️
                                        </button>
                                    </div>
                                    <div id="editPasswordMatch{{ $user->user_id }}" class="text-sm mt-1"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-1">Role</label>
                                    <select name="role" class="w-full mb-2 p-2 border rounded">
                                        <option value="SuperAdmin" @if($user->role=='SuperAdmin') selected @endif>SuperAdmin</option>
                                        <option value="Coach" @if($user->role=='Coach') selected @endif>Coach</option>
                                        <option value="Staff" @if($user->role=='Staff') selected @endif>Admin</option>
                                    </select>
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal('editUserModal{{ $user->user_id }}')">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
 @endif

<!-- Section: Sports Management -->
<section class="mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Sports Management</h2>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Sports</h3>
            <button data-modal-target="addSportModal" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">+ Add Sport</button>
        </div>
        <div class="overflow-y-auto max-h-64 border rounded-lg">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-gray-100">
                    <tr>
                        <th class="p-3 border">Sport Name</th>
                        <th class="p-3 border">Assigned Coach</th>
                        <th class="p-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sports as $sport)
                    <tr>
                        <td class="p-3 border">{{ $sport->sport_name }}</td>
                        <td class="p-3 border">{{ $sport->coaches?->full_name ?? 'Unassigned' }}</td>
                        <td class="p-3 border text-center space-x-2">
                            <button data-modal-target="editSportModal{{ $sport->sport_id }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                            <form action="{{ route('sports.deactivate', $sport->sport_id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Deactivate</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Sport Modal -->
                    <div id="editSportModal{{ $sport->sport_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                        <div class="bg-white p-6 rounded-lg w-1/3">
                            <h3 class="font-bold text-lg mb-4">Edit Sport</h3>
                            <form action="{{ route('sports.update', $sport->sport_id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <label>Sport Name</label>
                                <input type="text" name="sport_name" value="{{ $sport->sport_name }}" class="w-full mb-2 p-2 border rounded" required>

                                <label>Assigned Coach</label>
                                <select name="coach_id" class="w-full mb-2 p-2 border rounded">
                                    <option value="">-- Select Coach --</option>
                                    @foreach($coaches as $coach)
                                        <option value="{{ $coach->user_id }}" {{ $sport->coach_id == $coach->user_id ? 'selected' : '' }}>
                                            {{ $coach->username }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('editSportModal{{ $sport->sport_id }}')">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add Sport Modal -->
<div id="addSportModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="font-bold text-lg mb-4">Add Sport</h3>
        <form action="{{ route('sports.store') }}" method="POST">
            @csrf
            <label>Sport Name</label>
            <input type="text" name="sport_name" class="w-full mb-2 p-2 border rounded" required>

            <label>Assigned Coach</label>
            <select name="coach_id" class="w-full mb-2 p-2 border rounded">
                <option value="">-- Select Coach --</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->user_id }}">{{ $coach->username }}</option>
                @endforeach
            </select>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('addSportModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

    <!-- Team Management -->
    <section>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Teams</h2>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Teams</h3>
            <button data-modal-target="addTeamModal" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">+ Add Team</button>
        </div>

        <!-- Scrollable container -->
        <div class="max-h-96 overflow-y-auto">
            <ul class="space-y-2">
                @foreach($teams as $team)
                <li class="flex justify-between bg-gray-50 p-3 rounded-lg">
                    <span>{{ $team->team_name }}</span>
                    <div class="space-x-2">
                        <button data-modal-target="editTeamModal{{ $team->team_id }}" class="text-sm text-blue-600 hover:underline">Edit</button>
                        <form action="{{ route('control-panel.deleteTeam', $team->team_id) }}" method="POST" class="inline-block" onsubmit="return confirmDeleteTeam();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </li>
                <!-- Edit Team Modal -->
                <div id="editTeamModal{{ $team->team_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                  <div class="bg-white p-6 rounded-lg w-1/3">
                    <h3 class="font-bold text-lg mb-4">Edit Team</h3>
                    <form action="{{ route('control-panel.updateTeam', $team->team_id) }}" method="POST">
                      @csrf
                      @method('PUT')

                      <label>Team Name</label>
                      <input type="text" name="team_name" value="{{ $team->team_name }}" class="w-full mb-2 p-2 border rounded">
                      
                      <label>Sport ID</label>
                      <input type="number" name="sport_id" value="{{ $team->sport_id }}" class="w-full mb-2 p-2 border rounded">

                      <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('editTeamModal{{ $team->team_id }}')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                      </div>
                    </form>
                  </div>
                </div>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<!-- Section: Departments Management -->
<section>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Department Management</h2>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">College Department</h3>
            <button data-modal-target="addDepartmentModal" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">+ Add Department</button>
        </div>
        <div class="overflow-y-auto max-h-64 border rounded-lg">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-gray-100">
                    <tr>
                        <th class="p-3 border">Department Name</th>
                        <th class="p-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                    <tr>
                        <td class="p-3 border">{{ $department->department_name }}</td>
                        <td class="p-3 border text-center space-x-2">
                            <button data-modal-target="editDepartmentModal{{ $department->department_id }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                            <form action="{{ route('departments.deactivate', $department->department_id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Deactivate</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Department Modal -->
                    <div id="editDepartmentModal{{ $department->department_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                        <div class="bg-white p-6 rounded-lg w-1/3">
                            <h3 class="font-bold text-lg mb-4">Edit Department</h3>
                            <form action="{{ route('departments.update', $department->department_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label>Department Name</label>
                                <input type="text" name="name" value="{{ $department->department_name }}" class="w-full mb-2 p-2 border rounded">
                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('editDepartmentModal{{ $department->id }}')">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add Department Modal -->
<div id="addDepartmentModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="font-bold text-lg mb-4">Add Department</h3>
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            <label>Department Name</label>
            <input type="text" name="name" class="w-full mb-2 p-2 border rounded">
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('addDepartmentModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Section: Course Management -->
<section class="mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Course Management</h2>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">College Course</h3>
            <button data-modal-target="addCourseModal" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">+ Add Course</button>
        </div>
        <div class="overflow-y-auto max-h-64 border rounded-lg">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-gray-100">
                    <tr>
                        <th class="p-3 border">Course Name</th>
                        <th class="p-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td class="p-3 border">{{ $course->course_name }}</td>
                        <td class="p-3 border text-center space-x-2">
                            <button data-modal-target="editCourseModal{{ $course->course_id }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                            <form action="{{ route('courses.deactivate', $course->course_id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Deactivate</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Course Modal -->
                    <div id="editCourseModal{{ $course->course_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                        <div class="bg-white p-6 rounded-lg w-1/3">
                            <h3 class="font-bold text-lg mb-4">Edit Course</h3>
                            <form action="{{ route('courses.update', $course->course_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label>Course Name</label>
                                <input type="text" name="name" value="{{ $course->course_name }}" class="w-full mb-2 p-2 border rounded" required>

                                <label>Department</label>
                                <select name="department_id" class="w-full mb-2 p-2 border rounded text-gray-900" required>
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}" {{ $course->department_id == $department->department_id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('editCourseModal{{ $course->course_id }}')">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add Course Modal -->
<div id="addCourseModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="font-bold text-lg mb-4">Add Course</h3>
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            <label>Course Name</label>
            <input type="text" name="name" class="w-full mb-2 p-2 border rounded" required>

            <label>Department</label>
            <select name="department_id" class="w-full mb-2 p-2 border rounded text-gray-900" required>
                <option value="" disabled selected>Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->department_id }}" {{ $course->department_id == $department->department_id ? 'selected' : '' }}>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('addCourseModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Section: Section Management -->
<section class="mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Section Management</h2>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">College Sections</h3>
            <button data-modal-target="addSectionModal" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">+ Add Section</button>
        </div>
        <div class="overflow-y-auto max-h-64 border rounded-lg">
            <table class="w-full text-left">
                <thead class="sticky top-0 bg-gray-100">
                    <tr>
                        <th class="p-3 border">Section Name</th>
                        <th class="p-3 border">Course</th>
                        <th class="p-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                    <tr>
                        <td class="p-3 border">{{ $section->section_name }}</td>
                        <td class="p-3 border">{{ $section->course?->course_name }}</td>
                        <td class="p-3 border text-center space-x-2">
                            <button data-modal-target="editSectionModal{{ $section->section_id }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                            <form action="{{ route('sections.deactivate', $section->section_id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Deactivate</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Section Modal -->
                    <div id="editSectionModal{{ $section->section_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                        <div class="bg-white p-6 rounded-lg w-1/3">
                            <h3 class="font-bold text-lg mb-4">Edit Section</h3>
                            <form action="{{ route('sections.update', $section->section_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label>Section Name</label>
                                <input type="text" name="name" value="{{ $section->section_name }}" class="w-full mb-2 p-2 border rounded">

                                <label>Course</label>
                                <select name="course_id" class="w-full mb-2 p-2 border rounded">
                                    <option value="">-- Select Course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}" {{ $section->course_id == $course->course_id ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('editSectionModal{{ $section->section_id }}')">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add Section Modal -->
<div id="addSectionModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="font-bold text-lg mb-4">Add Section</h3>
        <form action="{{ route('sections.store') }}" method="POST">
            @csrf
            <label>Section Name</label>
            <input type="text" name="name" class="w-full mb-2 p-2 border rounded">

            <label>Course</label>
            <select name="course_id" class="w-full mb-2 p-2 border rounded">
                <option value="">-- Select Course --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                @endforeach
            </select>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('addSectionModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Add</button>
            </div>
        </form>
    </div>
</div>


@if($role === 'SuperAdmin')
<section>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">System Settings</h2>
    <div class="bg-white rounded-lg shadow-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <button data-modal-target="dataBackupModal" class="bg-gray-800 text-white px-4 py-6 rounded-lg shadow">📂 Data Backup</button>

        <!-- Data Backup Modal -->
        <div id="dataBackupModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center p-4">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <h3 class="text-xl font-bold mb-4">Data Backup</h3>
                <button class="absolute top-4 right-4 text-gray-600" onclick="closeModal('dataBackupModal')">✖️</button>

                <p class="mb-4">Click the button below to download a full database backup.</p>

                <a href="{{ route('control-panel.backupDatabase') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Download Backup
                </a>
            </div>
        </div>
        <button data-modal-target="auditLogsModal" class="bg-gray-800 text-white px-4 py-6 rounded-lg shadow">📑 Audit Logs</button>
    </div>
</section>
@endif
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white p-6 rounded-lg w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Add New User</h3>
            <button onclick="closeModal('addUserModal')" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <form action="{{ route('control-panel.storeUser') }}" method="POST" id="addUserForm">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" class="w-full mb-2 p-2 border rounded" required>
            </div>
            
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Password</label>
                <div class="relative">
                    <input type="password" 
                        id="generatedPassword" 
                        name="password" 
                        class="w-full mb-1 p-2 border rounded pr-10"
                        placeholder="Enter password"
                        onkeyup="checkAddPasswordStrength()"
                        required
                    />
                    <button type="button" 
                            onclick="togglePasswordVisibility('generatedPassword', 'addToggle')"
                            id="addToggle"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                        👁️
                    </button>
                </div>
                
                <!-- Password Strength Indicator -->
                <div class="mt-2">
                    <div class="flex items-center mb-1">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="strengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <span id="passwordStrength" class="ml-2 text-sm">No password</span>
                    </div>
                    <div id="addCriteria" class="text-xs text-gray-600 grid grid-cols-2 gap-1 mt-2">
                        <div id="addLength" class="flex items-center">
                            <span class="mr-1">⬜</span> 8+ characters
                        </div>
                        <div id="addLowercase" class="flex items-center">
                            <span class="mr-1">⬜</span> Lowercase letter
                        </div>
                        <div id="addUppercase" class="flex items-center">
                            <span class="mr-1">⬜</span> Uppercase letter
                        </div>
                        <div id="addNumber" class="flex items-center">
                            <span class="mr-1">⬜</span> Contains number
                        </div>
                        <div id="addSpecial" class="flex items-center">
                            <span class="mr-1">⬜</span> Special character
                        </div>
                    </div>
                </div>
                
                <div class="mt-2">
                    <button type="button" 
                            onclick="generatePassword()" 
                            class="bg-amber-900 text-white px-4 py-1 rounded-lg hover:bg-amber-800 text-sm">
                        Generate Secure Password
                    </button>
                    <button type="button" 
                            onclick="showAddPasswordTip()" 
                            class="ml-2 text-amber-900 hover:text-amber-800 text-sm">
                        Password Tips
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <div class="relative">
                    <input type="password" 
                           id="confirmPassword" 
                           name="password_confirmation"
                           class="w-full mb-1 p-2 border rounded pr-10"
                           placeholder="Confirm password"
                           onkeyup="checkAddPasswordMatch()"
                           required
                    />
                    <button type="button" 
                            onclick="togglePasswordVisibility('confirmPassword', 'addConfirmToggle')"
                            id="addConfirmToggle"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                        👁️
                    </button>
                </div>
                <div id="addPasswordMatch" class="text-sm mt-1"></div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" class="w-full mb-2 p-2 border rounded" required>
                    <option value="SuperAdmin">SuperAdmin</option>
                    <option value="Coach">Coach</option>
                    <option value="Staff">Admin</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal('addUserModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Team Modal -->
<div id="addTeamModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h3 class="font-bold text-lg mb-4">Add Team</h3>
        <form action="{{ route('control-panel.storeTeam') }}" method="POST">
            @csrf

            <!-- Team Name -->
            <label>Team Name</label>
            <input type="text" name="team_name" class="w-full mb-2 p-2 border rounded" required>

            <!-- Sport Dropdown -->
            <label>Sport</label>
            <select name="sport_id" class="w-full mb-2 p-2 border rounded" required>
                <option value="">-- Select Sport --</option>
                @foreach($sports as $sport)
                    <option value="{{ $sport->sport_id }}">{{ $sport->sport_name }}</option>
                @endforeach
            </select>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('addTeamModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Audit Logs Modal -->
<div id="auditLogsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center overflow-auto p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl p-6 relative">
        <h3 class="text-xl font-bold mb-4">Audit Logs</h3>
        <button class="absolute top-4 right-4 text-gray-600" onclick="closeModal('auditLogsModal')">✖️</button>

        <div class="overflow-x-auto max-h-[70vh]">
            <table class="w-full text-left border">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="p-2 border">User</th>
                        <th class="p-2 border">Action</th>
                        <th class="p-2 border">Module</th>
                        <th class="p-2 border">Description</th>
                        <th class="p-2 border">IP Address</th>
                        <th class="p-2 border">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $log->user->username ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $log->action }}</td>
                            <td class="p-2 border">{{ $log->module }}</td>
                            <td class="p-2 border">{{ $log->description }}</td>
                            <td class="p-2 border">{{ $log->ip_address }}</td>
                            <td class="p-2 border">{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Password Tips Modal -->
<div id="passwordTipsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white p-6 rounded-lg w-full max-w-lg mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Password Security Tips</h3>
            <button onclick="closeModal('passwordTipsModal')" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <div class="space-y-3 text-sm">
            <p><strong>✓ Strong passwords should include:</strong></p>
            <ul class="list-disc pl-5 space-y-1">
                <li>At least 12 characters (14+ recommended)</li>
                <li>Mix of uppercase and lowercase letters</li>
                <li>Numbers (0-9)</li>
                <li>Special characters (!@#$%^&* etc.)</li>
                <li>Avoid common words or personal information</li>
            </ul>
            <p><strong>✓ Best practices:</strong></p>
            <ul class="list-disc pl-5 space-y-1">
                <li>Use a different password for each account</li>
                <li>Consider using a password manager</li>
                <li>Change passwords regularly (every 90 days)</li>
                <li>Never share passwords via email or chat</li>
            </ul>
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                <p class="text-yellow-800"><strong>Note:</strong> The system will automatically generate a strong, secure password for you.</p>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal('passwordTipsModal')">Close</button>
        </div>
    </div>
</div>

<script>
  function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
  }

  // Open modals
  document.querySelectorAll('[data-modal-target]').forEach(btn => {
      btn.addEventListener('click', () => {
          document.getElementById(btn.getAttribute('data-modal-target')).classList.remove('hidden');
      });
  });

  function showAddPasswordTip() {
      document.getElementById('passwordTipsModal').classList.remove('hidden');
  }

  function showPasswordTip(userId) {
      document.getElementById('passwordTipsModal').classList.remove('hidden');
      // You could also set a data attribute to know which modal we came from
      document.getElementById('passwordTipsModal').dataset.userId = userId || '';
  }

  // Password strength functions for Add User modal
  function checkAddPasswordStrength() {
      const password = document.getElementById('generatedPassword').value;
      const strengthBar = document.getElementById('strengthBar');
      const strengthText = document.getElementById('passwordStrength');
      
      // Reset checks
      resetAddCheck('addLength', '8+ characters');
      resetAddCheck('addLowercase', 'Lowercase letter');
      resetAddCheck('addUppercase', 'Uppercase letter');
      resetAddCheck('addNumber', 'Contains number');
      resetAddCheck('addSpecial', 'Special character');
      
      if (password.length === 0) {
          strengthBar.style.width = '0%';
          strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
          strengthText.textContent = 'No password';
          strengthText.className = 'ml-2 text-sm text-gray-600';
          return;
      }
      
      let score = 0;
      const totalChecks = 5;
      
      // Check criteria
      const hasLength = password.length >= 8;
      const hasLowercase = /[a-z]/.test(password);
      const hasUppercase = /[A-Z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
      
      // Update checkmarks
      updateAddCheck('addLength', hasLength, '8+ characters');
      updateAddCheck('addLowercase', hasLowercase, 'Lowercase letter');
      updateAddCheck('addUppercase', hasUppercase, 'Uppercase letter');
      updateAddCheck('addNumber', hasNumber, 'Contains number');
      updateAddCheck('addSpecial', hasSpecial, 'Special character');
      
      // Calculate score
      score = [hasLength, hasLowercase, hasUppercase, hasNumber, hasSpecial]
          .filter(Boolean).length;
      
      // Calculate percentage
      const percentage = (score / totalChecks) * 100;
      strengthBar.style.width = percentage + '%';
      
      // Update strength indicator
      if (score === 0) {
          strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
          strengthText.textContent = 'Very Weak';
          strengthText.className = 'ml-2 text-sm text-red-600';
      } else if (score <= 2) {
          strengthBar.className = 'h-2 rounded-full bg-red-500 transition-all duration-300';
          strengthText.textContent = 'Weak';
          strengthText.className = 'ml-2 text-sm text-red-500';
      } else if (score === 3) {
          strengthBar.className = 'h-2 rounded-full bg-yellow-500 transition-all duration-300';
          strengthText.textContent = 'Fair';
          strengthText.className = 'ml-2 text-sm text-yellow-600';
      } else if (score === 4) {
          strengthBar.className = 'h-2 rounded-full bg-blue-500 transition-all duration-300';
          strengthText.textContent = 'Good';
          strengthText.className = 'ml-2 text-sm text-blue-600';
      } else {
          strengthBar.className = 'h-2 rounded-full bg-green-500 transition-all duration-300';
          strengthText.textContent = 'Strong';
          strengthText.className = 'ml-2 text-sm text-green-600';
      }
      
      // Also check password match
      checkAddPasswordMatch();
  }

  function resetAddCheck(elementId, text) {
      const element = document.getElementById(elementId);
      if (element) {
          element.innerHTML = '<span class="mr-1">⬜</span>' + text;
          element.className = 'flex items-center text-gray-600';
      }
  }

  function updateAddCheck(elementId, isValid, text) {
      const element = document.getElementById(elementId);
      if (element) {
          if (isValid) {
              element.innerHTML = '<span class="mr-1 text-green-500">✓</span>' + text;
              element.className = 'flex items-center text-green-600';
          } else {
              element.innerHTML = '<span class="mr-1 text-red-500">✗</span>' + text;
              element.className = 'flex items-center text-red-600';
          }
      }
  }

  function checkAddPasswordMatch() {
      const password = document.getElementById('generatedPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const messageElement = document.getElementById('addPasswordMatch');
      
      if (!messageElement) return;
      
      if (password === '' && confirmPassword === '') {
          messageElement.textContent = '';
          messageElement.className = 'text-sm mt-1';
          return;
      }
      
      if (confirmPassword === '') {
          messageElement.textContent = 'Please confirm password';
          messageElement.className = 'text-sm mt-1 text-yellow-600';
          return;
      }
      
      if (password === confirmPassword) {
          messageElement.textContent = 'Passwords match ✓';
          messageElement.className = 'text-sm mt-1 text-green-600';
      } else {
          messageElement.textContent = 'Passwords do not match ✗';
          messageElement.className = 'text-sm mt-1 text-red-600';
      }
  }

  // Password strength functions for Edit User modal
  function checkEditPasswordStrength(userId) {
      const password = document.getElementById('editPassword' + userId).value;
      const strengthBar = document.getElementById('editStrengthBar' + userId);
      const strengthText = document.getElementById('editPasswordStrength' + userId);
      
      // Reset checks
      resetEditCheck('editLength' + userId, '8+ characters');
      resetEditCheck('editLowercase' + userId, 'Lowercase letter');
      resetEditCheck('editUppercase' + userId, 'Uppercase letter');
      resetEditCheck('editNumber' + userId, 'Contains number');
      resetEditCheck('editSpecial' + userId, 'Special character');
      
      if (password.length === 0) {
          strengthBar.style.width = '0%';
          strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
          strengthText.textContent = 'No password (leave blank to keep current)';
          strengthText.className = 'ml-2 text-sm text-gray-600';
          return;
      }
      
      let score = 0;
      const totalChecks = 5;
      
      // Check criteria
      const hasLength = password.length >= 8;
      const hasLowercase = /[a-z]/.test(password);
      const hasUppercase = /[A-Z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
      
      // Update checkmarks
      updateEditCheck('editLength' + userId, hasLength, '8+ characters');
      updateEditCheck('editLowercase' + userId, hasLowercase, 'Lowercase letter');
      updateEditCheck('editUppercase' + userId, hasUppercase, 'Uppercase letter');
      updateEditCheck('editNumber' + userId, hasNumber, 'Contains number');
      updateEditCheck('editSpecial' + userId, hasSpecial, 'Special character');
      
      // Calculate score
      score = [hasLength, hasLowercase, hasUppercase, hasNumber, hasSpecial]
          .filter(Boolean).length;
      
      // Calculate percentage
      const percentage = (score / totalChecks) * 100;
      strengthBar.style.width = percentage + '%';
      
      // Update strength indicator
      if (score === 0) {
          strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
          strengthText.textContent = 'Very Weak';
          strengthText.className = 'ml-2 text-sm text-red-600';
      } else if (score <= 2) {
          strengthBar.className = 'h-2 rounded-full bg-red-500 transition-all duration-300';
          strengthText.textContent = 'Weak';
          strengthText.className = 'ml-2 text-sm text-red-500';
      } else if (score === 3) {
          strengthBar.className = 'h-2 rounded-full bg-yellow-500 transition-all duration-300';
          strengthText.textContent = 'Fair';
          strengthText.className = 'ml-2 text-sm text-yellow-600';
      } else if (score === 4) {
          strengthBar.className = 'h-2 rounded-full bg-blue-500 transition-all duration-300';
          strengthText.textContent = 'Good';
          strengthText.className = 'ml-2 text-sm text-blue-600';
      } else {
          strengthBar.className = 'h-2 rounded-full bg-green-500 transition-all duration-300';
          strengthText.textContent = 'Strong';
          strengthText.className = 'ml-2 text-sm text-green-600';
      }
      
      // Also check password match
      checkEditPasswordMatch(userId);
  }

  function resetEditCheck(elementId, text) {
      const element = document.getElementById(elementId);
      if (element) {
          element.innerHTML = '<span class="mr-1">⬜</span>' + text;
          element.className = 'flex items-center text-gray-600';
      }
  }

  function updateEditCheck(elementId, isValid, text) {
      const element = document.getElementById(elementId);
      if (element) {
          if (isValid) {
              element.innerHTML = '<span class="mr-1 text-green-500">✓</span>' + text;
              element.className = 'flex items-center text-green-600';
          } else {
              element.innerHTML = '<span class="mr-1 text-red-500">✗</span>' + text;
              element.className = 'flex items-center text-red-600';
          }
      }
  }

  function checkEditPasswordMatch(userId) {
      const password = document.getElementById('editPassword' + userId).value;
      const confirmPassword = document.getElementById('editPasswordConfirm' + userId).value;
      const messageElement = document.getElementById('editPasswordMatch' + userId);
      
      if (!messageElement) return;
      
      if (password === '' && confirmPassword === '') {
          messageElement.textContent = '';
          messageElement.className = 'text-sm mt-1';
          return;
      }
      
      if (confirmPassword === '') {
          messageElement.textContent = 'Please confirm password';
          messageElement.className = 'text-sm mt-1 text-yellow-600';
          return;
      }
      
      if (password === confirmPassword) {
          messageElement.textContent = 'Passwords match ✓';
          messageElement.className = 'text-sm mt-1 text-green-600';
      } else {
          messageElement.textContent = 'Passwords do not match ✗';
          messageElement.className = 'text-sm mt-1 text-red-600';
      }
  }

  function togglePasswordVisibility(inputId, buttonId) {
      const input = document.getElementById(inputId);
      const button = document.getElementById(buttonId);
      
      if (input && button) {
          if (input.type === 'password') {
              input.type = 'text';
              button.textContent = '🙈';
          } else {
              input.type = 'password';
              button.textContent = '👁️';
          }
      }
  }

  // Password generation functions
  function generatePassword(length = 14) {
      const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      const lower = "abcdefghijklmnopqrstuvwxyz";
      const numbers = "0123456789";
      const symbols = "!@#$%^&*()_+-=[]{}|;:,.<>?";

      const allChars = upper + lower + numbers + symbols;

      let password = [
          upper[randomIndex(upper)],
          lower[randomIndex(lower)],
          numbers[randomIndex(numbers)],
          symbols[randomIndex(symbols)]
      ];

      for (let i = password.length; i < length; i++) {
          password.push(allChars[randomIndex(allChars)]);
      }

      // Shuffle the password array
      password = shuffleArray(password);

      const passwordField = document.getElementById('generatedPassword');
      passwordField.value = password.join('');
      passwordField.type = 'text';
      
      // Update toggle button
      const toggleBtn = document.getElementById('addToggle');
      if (toggleBtn) toggleBtn.textContent = '🙈';
      
      // Trigger strength check
      checkAddPasswordStrength();
      
      // Also update confirm field if empty
      const confirmField = document.getElementById('confirmPassword');
      if (!confirmField.value) {
          confirmField.value = password.join('');
          confirmField.type = 'text';
          const confirmToggleBtn = document.getElementById('addConfirmToggle');
          if (confirmToggleBtn) confirmToggleBtn.textContent = '🙈';
          checkAddPasswordMatch();
      }
  }

  function generateEditPassword(userId, length = 14) {
      const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      const lower = "abcdefghijklmnopqrstuvwxyz";
      const numbers = "0123456789";
      const symbols = "!@#$%^&*()_+-=[]{}|;:,.<>?";

      const allChars = upper + lower + numbers + symbols;

      let password = [
          upper[randomIndex(upper)],
          lower[randomIndex(lower)],
          numbers[randomIndex(numbers)],
          symbols[randomIndex(symbols)]
      ];

      for (let i = password.length; i < length; i++) {
          password.push(allChars[randomIndex(allChars)]);
      }

      // Shuffle the password array
      password = shuffleArray(password);

      const passwordField = document.getElementById('editPassword' + userId);
      passwordField.value = password.join('');
      passwordField.type = 'text';
      
      // Update toggle button
      const toggleBtn = document.getElementById('editToggle' + userId);
      if (toggleBtn) toggleBtn.textContent = '🙈';
      
      // Trigger strength check
      checkEditPasswordStrength(userId);
      
      // Also update confirm field if empty
      const confirmField = document.getElementById('editPasswordConfirm' + userId);
      if (!confirmField.value) {
          confirmField.value = password.join('');
          confirmField.type = 'text';
          const confirmToggleBtn = document.getElementById('editConfirmToggle' + userId);
          if (confirmToggleBtn) confirmToggleBtn.textContent = '🙈';
          checkEditPasswordMatch(userId);
      }
  }

  function randomIndex(str) {
      return crypto.getRandomValues(new Uint32Array(1))[0] % str.length;
  }

  function shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]];
      }
      return array;
  }

  // Form validation
  document.getElementById('addUserForm')?.addEventListener('submit', function(event) {
      const password = document.getElementById('generatedPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      if (password !== confirmPassword) {
          event.preventDefault();
          alert('Passwords do not match. Please confirm your password.');
          return false;
      }
      
      if (password.length > 0 && password.length < 8) {
          event.preventDefault();
          alert('Password must be at least 8 characters long.');
          return false;
      }
      
      return true;
  });

  // Add validation for each edit form
  document.querySelectorAll('[id^="editUserForm"]').forEach(form => {
      form.addEventListener('submit', function(event) {
          const userId = this.id.replace('editUserForm', '');
          const password = document.getElementById('editPassword' + userId)?.value;
          const confirmPassword = document.getElementById('editPasswordConfirm' + userId)?.value;
          
          // Only validate if password is being changed
          if (password && password.length > 0) {
              if (password !== confirmPassword) {
                  event.preventDefault();
                  alert('Passwords do not match. Please confirm your password.');
                  return false;
              }
              
              if (password.length < 8) {
                  event.preventDefault();
                  alert('Password must be at least 8 characters long.');
                  return false;
              }
          }
          
          return true;
      });
  });

  function confirmDelete() {
      return confirm('Are you sure you want to delete this user? This action cannot be undone.');
  }
  
  function confirmDeleteTeam() {
      return confirm('Are you sure you want to delete this team? This action cannot be undone.');
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
      document.querySelectorAll('[id$="Modal"]').forEach(modal => {
          if (event.target === modal) {
              modal.classList.add('hidden');
          }
      });
  }

  // Initialize password fields when modals open
  document.addEventListener('DOMContentLoaded', function() {
      // Initialize add user modal
      checkAddPasswordStrength();
      checkAddPasswordMatch();
      
      // Initialize edit modals (will be triggered when opened)
      document.querySelectorAll('[id^="editUserModal"]').forEach(modal => {
          const userId = modal.id.replace('editUserModal', '');
          // Set up event listener for when modal opens
          const btn = document.querySelector(`[data-modal-target="editUserModal${userId}"]`);
          if (btn) {
              btn.addEventListener('click', () => {
                  setTimeout(() => {
                      checkEditPasswordStrength(userId);
                      checkEditPasswordMatch(userId);
                  }, 100);
              });
          }
      });
  });
</script>

<style>
/* Modal animations */
[id$="Modal"] > div {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Scrollable containers */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f7fafc;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 4px;
}

/* Password strength bar styling */
[id^="strengthBar"], [id^="editStrengthBar"] {
    transition: width 0.3s ease, background-color 0.3s ease;
}

/* Make modal responsive */
@media (max-width: 768px) {
    .max-w-lg {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
    
    [id^="addCriteria"], [id^="editCriteria"] {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection