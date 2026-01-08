@extends('layouts.app')

@section('title', 'CHED Reports')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md" x-data="{ activeTab: 'formA' }">
    <h2 class="text-2xl font-bold mb-4">Generate CHED Reports</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <input type="number" name="year" value="{{ request('year') }}" placeholder="School Year" class="border p-2 rounded">
        <select name="year_level" class="border rounded px-3 py-2">
            <option value="">All Year Levels</option>
            @foreach(['1st Year','2nd Year','3rd Year','4th Year','Alumni'] as $level)
                <option value="{{ $level }}" {{ request('year_level')==$level ? 'selected' : '' }}>{{ $level }}</option>
            @endforeach
        </select>
        <select name="sport" class="border p-2 rounded">
            <option value="">All Sports</option>
            @foreach($sports as $sport)
                <option value="{{ $sport->sport_id }}" {{ request('sport') == $sport->sport_id ? 'selected' : '' }}>{{ $sport->sport_name }}</option>
            @endforeach
        </select>
        <select name="status" class="border p-2 rounded">
            <option value="">All Status</option>
            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Injured" {{ request('status') == 'Injured' ? 'selected' : '' }}>Injured</option>
            <option value="Graduated" {{ request('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
        </select>
        <button type="submit" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded border">
            <h4 class="font-semibold text-blue-900">Total Athletes</h4>
            <p class="text-2xl font-bold">{{ $athletes->count() }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded border">
            <h4 class="font-semibold text-green-900">Total Coaches</h4>
            <p class="text-2xl font-bold">{{ $coaches->count() }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded border">
            <h4 class="font-semibold text-purple-900">With Scholarships</h4>
            <p class="text-2xl font-bold">{{ $athletes->where('scholarship_status', '!=', null)->where('scholarship_status', '!=', 'Non-scholar')->count() }}</p>
        </div>
        <div class="bg-orange-50 p-4 rounded border">
            <h4 class="font-semibold text-orange-900">Active Sports</h4>
            <p class="text-2xl font-bold">{{ $sports->count() }}</p>
        </div>
    </div>

    <!-- Excel-like Tabs -->
    <div class="mb-4">
        <div class="border-b">
            <nav  class="-mb-px flex space-x-8 overflow-x-auto py-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                <button type="button" 
                        @click="activeTab = 'formA'"
                        :class="activeTab === 'formA' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM A: Institutional Info
                </button>
                <button type="button" 
                        @click="activeTab = 'formB'"
                        :class="activeTab === 'formB' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM B: Sports Programs
                </button>
                <button type="button" 
                        @click="activeTab = 'formC'"
                        :class="activeTab === 'formC' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM C: Student-Athletes
                </button>
                <button type="button" 
                        @click="activeTab = 'formD'"
                        :class="activeTab === 'formD' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM D: Sports Personnel
                </button>
                <button type="button" 
                        @click="activeTab = 'formE'"
                        :class="activeTab === 'formE' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM E: Budget & Expenditure
                </button>
                <button type="button" 
                        @click="activeTab = 'formF'"
                        :class="activeTab === 'formF' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM F: Non-Varsity School-Based Sports Organizations
                </button>
                <button type="button" 
                        @click="activeTab = 'formG'"
                        :class="activeTab === 'formG' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    FORM G: Feedback
                </button>
            </nav>
        </div>
    </div>

    <!-- FORM A: Institutional Information -->
    <div x-show="activeTab === 'formA'">
        <div class="bg-red-50 border border-red-200 rounded p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">CHED Form A: Institutional Information</h3>
                    <p class="mt-2 text-sm text-red-700">Please complete the institutional information below. This data will be included in all CHED report exports.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('reports.save-institutional') }}">
            @csrf

            <div class="overflow-x-auto bg-white border rounded-lg shadow-sm">
                <table class="min-w-[3000px] w-full border-collapse text-sm text-gray-800 text-center">
                    <thead>
                        <!-- ITEM CODE / COLUMN CODES -->
                        <tr class="bg-red-900 text-white text-xs uppercase">
                            <th class="border p-2">ITEM CODE</th>
                            <th class="border p-2">A1-HEI-1</th>
                            <th class="border p-2">A1-HEI-2</th>
                            <th class="border p-2">A1-HEI-3</th>
                            <th class="border p-2">A1-HEI-4</th>
                            <th class="border p-2">A1-HEI-5</th>
                            <th class="border p-2">A1-HEI-6</th>
                            <th class="border p-2">A1-HEI-7</th>
                            <th class="border p-2">A1-HEI-8</th>
                            <th class="border p-2">A1-HEI-9</th>
                            <th class="border p-2">A1-HEI-10</th>
                            <th class="border p-2">A1-HEI-11</th>
                            <th class="border p-2">A1-HEI-12</th>
                            <th class="border p-2">A2-WELL-1</th>
                            <th class="border p-2">A2-WELL-2</th>
                            <th class="border p-2">A2-WELL-3</th>
                            <th class="border p-2">A3-FACILITY-1</th>
                            <th class="border p-2">A3-FACILITY-2</th>
                            <th class="border p-2">A3-FACILITY-3</th>
                            <th class="border p-2">A3-FACILITY-4</th>
                            <th class="border p-2">A3-FACILITY-5</th>
                            <th class="border p-2">A3-FACILITY-6</th>
                            <th class="border p-2">A3-FACILITY-7</th>
                            <th class="border p-2">A3-FACILITY-8</th>
                            <th class="border p-2">A3-FACILITY-9</th>
                            <th class="border p-2">A3-FACILITY-10</th>
                            <th class="border p-2">A3-FACILITY-11</th>
                            <th class="border p-2">A3-FACILITY-12</th>
                        </tr>

                        <!-- DESCRIPTION ROW -->
                        <tr class="bg-red-800 text-white text-[11px]">
                            <th class="border p-2">Description</th>
                            <th class="border p-2">Name of HEI</th>
                            <th class="border p-2">HEI Campus (if applicable)</th>
                            <th class="border p-2">Address of HEI</th>
                            <th class="border p-2">Name of HEI President</th>
                            <th class="border p-2">Name of Sports Director</th>
                            <th class="border p-2">
                                E-mail of Sports Director<br>
                                <small class="font-normal italic">(Input only one e-mail address in this field)</small>
                            </th>
                            <th class="border p-2">
                                Alternate E-mail of Sports Director<br>
                                <small class="font-normal italic">(Input only one e-mail address in this field)</small>
                            </th>
                            <th class="border p-2">Mobile No. of Sports Director</th>
                            <th class="border p-2">Name of Contact Person for RA 11180 Sports Reporting</th>
                            <th class="border p-2">
                                E-mail of Contact Person for RA 11180 Sports Reporting<br>
                                <small class="font-normal italic">(Input only one e-mail address in this field)</small>
                            </th>
                            <th class="border p-2">
                                Alternate E-mail of Contact Person for RA 11180 Sports Reporting<br>
                                <small class="font-normal italic">(Input only one e-mail address in this field)</small>
                            </th>
                            <th class="border p-2">Contact No. of Contact Person for RA 11180 Sports Reporting</th>
                            <th class="border p-2">Does HEI hold Departmental Intramurals within the campus?</th>
                            <th class="border p-2">Does HEI hold Interdepartmental Intramurals within the campus?</th>
                            <th class="border p-2">Does HEI hold Inter-campus Intramurals?</th>
                            <th class="border p-2">With Gymnasium? w/in HEI</th>
                            <th class="border p-2">With Multi-purpose Hall For Indoor Sports?</th>
                            <th class="border p-2">With Quadrangle?</th>
                            <th class="border p-2">With Athletes Dormitory?</th>
                            <th class="border p-2">With Swimming Pool?</th>
                            <th class="border p-2">With Track Oval?</th>
                            <th class="border p-2">With Dance Studio?</th>
                            <th class="border p-2">With Tennis Court?</th>
                            <th class="border p-2">With Football field?</th>
                            <th class="border p-2">With Boxing Ring?</th>
                            <th class="border p-2">Dedicated Sports Medical Facility</th>
                            <th class="border p-2">Other Training Venues and Athletic Facilities</th>
                        </tr>

                        <!-- INPUT GUIDE ROW -->
                        <tr class="bg-red-700 text-white text-[10px]">
                            <th class="border p-2">Input Guide</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">E-Mail</th>
                            <th class="border p-2">E-Mail</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">E-Mail</th>
                            <th class="border p-2">E-Mail</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Input Text Separated by Commas (e.g. Diving facility, Archery Range, etc.)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="odd:bg-gray-50 even:bg-white hover:bg-red-50">
                            <!-- ITEM CODE -->
                            <td class="border p-1 font-medium">A-INST-1</td>

                            <!-- A1-HEI FIELDS -->
                            <td class="border p-1">
                                <input type="text" name="hei_name" value="{{ old('hei_name', $institutionalData['hei_name'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="text" name="hei_campus" value="{{ old('hei_campus', $institutionalData['hei_campus'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm">
                            </td>
                            <td class="border p-1">
                                <textarea name="hei_address" rows="2" class="w-full border rounded p-1 text-sm" required>{{ old('hei_address', $institutionalData['hei_address'] ?? '') }}</textarea>
                            </td>
                            <td class="border p-1">
                                <input type="text" name="hei_president" value="{{ old('hei_president', $institutionalData['hei_president'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="text" name="sports_director_name" value="{{ old('sports_director_name', $institutionalData['sports_director_name'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="email" name="sports_director_email" value="{{ old('sports_director_email', $institutionalData['sports_director_email'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="email" name="sports_director_alt_email" value="{{ old('sports_director_alt_email', $institutionalData['sports_director_alt_email'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm">
                            </td>
                            <td class="border p-1">
                                <input type="tel" name="sports_director_mobile" value="{{ old('sports_director_mobile', $institutionalData['sports_director_mobile'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="text" name="contact_person_name" value="{{ old('contact_person_name', $institutionalData['contact_person_name'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="email" name="contact_person_email" value="{{ old('contact_person_email', $institutionalData['contact_person_email'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>
                            <td class="border p-1">
                                <input type="email" name="contact_person_alt_email" value="{{ old('contact_person_alt_email', $institutionalData['contact_person_alt_email'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm">
                            </td>
                            <td class="border p-1">
                                <input type="tel" name="contact_person_mobile" value="{{ old('contact_person_mobile', $institutionalData['contact_person_mobile'] ?? '') }}" 
                                       class="w-full border rounded p-1 text-sm" required>
                            </td>

                            <!-- A2-WELL FIELDS -->
                            <td class="border p-1">
                                <select name="departmental_intramurals" class="w-full border rounded p-1 text-sm">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('departmental_intramurals', $institutionalData['departmental_intramurals'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('departmental_intramurals', $institutionalData['departmental_intramurals'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>
                            <td class="border p-1">
                                <select name="interdepartmental_intramurals" class="w-full border rounded p-1 text-sm">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('interdepartmental_intramurals', $institutionalData['interdepartmental_intramurals'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('interdepartmental_intramurals', $institutionalData['interdepartmental_intramurals'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>
                            <td class="border p-1">
                                <select name="intercampus_intramurals" class="w-full border rounded p-1 text-sm">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('intercampus_intramurals', $institutionalData['intercampus_intramurals'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('intercampus_intramurals', $institutionalData['intercampus_intramurals'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>

                            <!-- A3-FACILITY FIELDS -->
                            @php
                                $facilities = [
                                    'gymnasium' => 'A3-FACILITY-1',
                                    'multipurpose_hall' => 'A3-FACILITY-2', 
                                    'quadrangle' => 'A3-FACILITY-3',
                                    'athletes_dormitory' => 'A3-FACILITY-4',
                                    'swimming_pool' => 'A3-FACILITY-5',
                                    'track_oval' => 'A3-FACILITY-6',
                                    'dance_studio' => 'A3-FACILITY-7',
                                    'tennis_court' => 'A3-FACILITY-8',
                                    'football_field' => 'A3-FACILITY-9',
                                    'boxing_ring' => 'A3-FACILITY-10',
                                    'sports_medical_facility' => 'A3-FACILITY-11'
                                ];
                            @endphp

                            @foreach($facilities as $key => $code)
                            <td class="border p-1">
                                <select name="facilities[{{ $key }}]" class="w-full border rounded p-1 text-sm">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('facilities.' . $key, $institutionalData['facilities'][$key] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('facilities.' . $key, $institutionalData['facilities'][$key] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>
                            @endforeach

                            <!-- A3-FACILITY-12: Other Facilities -->
                            <td class="border p-1">
                                <textarea name="other_facilities" rows="2" class="w-full border rounded p-1 text-sm" 
                                          placeholder="e.g., Diving facility, Archery Range, etc.">{{ old('other_facilities', $institutionalData['other_facilities'] ?? '') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Add this after the save button in Form A section -->
            <div class="mt-4 flex justify-end space-x-4">
                <a href="{{ route('reports.export-form', ['form' => 'A', 'format' => 'pdf']) }}" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('reports.export-form', ['form' => 'A', 'format' => 'xlsx']) }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                Export Excel
                </a>
            </div>

            <div class="mt-4 text-right">
                <button type="submit" class="bg-red-900 hover:bg-red-800 text-white px-6 py-2 rounded shadow">Save CHED Form A Report</button>
            </div>
            
        </form>
    </div>

    <!-- FORM B: Sports Programs -->
    <div x-show="activeTab === 'formB'" x-data="{
        newRowCount: 0,
        addNewRow() {
            const template = this.$refs.newRowTemplate;
            const newRow = template.content.cloneNode(true);
            const tbody = this.$refs.tbody;
            const newIndex = tbody.children.length + 1;
            
            // Update row number and item code
            newRow.querySelector('td:first-child').textContent = newIndex;
            newRow.querySelector('td:nth-child(2)').textContent = 'B1-SPORT-' + newIndex;
            
            // Update all name attributes with new index
            const inputs = newRow.querySelectorAll('[name]');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace('NEW_INDEX', 'new_' + this.newRowCount);
                input.setAttribute('name', newName);
            });
            
            tbody.appendChild(newRow);
            this.newRowCount++;
            
            // Update total records display
            this.updateTotalRecords();
        },
        updateTotalRecords() {
            const totalRecords = this.$refs.tbody.children.length;
            if (this.$refs.totalRecords) {
                this.$refs.totalRecords.textContent = totalRecords;
            }
        }
    }">
        <div class="bg-green-50 border border-green-200 rounded p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">CHED Form B: Sports Programs Report</h3>
                    <p class="mt-2 text-sm text-green-700">Please fill out the following form according to the official CHED B1–B5 column structure.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('reports.save-sports-programs') }}">
            @csrf

            <div class="overflow-x-auto bg-white border rounded-lg shadow-sm">
                <table class="min-w-[2600px] w-full border-collapse text-sm text-gray-800 text-center">
                    <thead>
                        <!-- ITEM CODE / COLUMN CODES -->
                        <tr class="bg-amber-900 text-white text-xs uppercase">
                            <th class="border p-2">#</th>
                            <th class="border p-2">ITEM CODE</th>
                            <th class="border p-2">B1-SPORTS-1</th>
                            <th class="border p-2">B1-SPORTS-2</th>
                            <th class="border p-2">B2-ASSOC-1</th>
                            <th class="border p-2">B2-ASSOC-2</th>
                            <th class="border p-2">B2-ASSOC-3</th>
                            <th class="border p-2">B2-ASSOC-3</th>
                            <th class="border p-2">B2-ASSOC-4</th>
                            <th class="border p-2">B3-LEAGUE-1</th>
                            <th class="border p-2">B3-LEAGUE-2</th>
                            <th class="border p-2">B3-LEAGUE-3</th>
                            <th class="border p-2">B3-LEAGUE-4</th>
                            <th class="border p-2">B3-LEAGUE-5</th>
                            <th class="border p-2">B3-LEAGUE-6</th>
                            <th class="border p-2">B3-LEAGUE-7</th>
                            <th class="border p-2">B3-LEAGUE-8</th>
                            <th class="border p-2">B3-LEAGUE-9</th>
                            <th class="border p-2">B3-LEAGUE-10</th>
                            <th class="border p-2">B4-WELL-1</th>
                            <th class="border p-2">B4-WELL-2</th>
                            <th class="border p-2">B4-WELL-3</th>
                            <th class="border p-2">B5-CPD-1</th>
                            <th class="border p-2">B5-CPD-2</th>
                            <th class="border p-2">B5-CPD-3</th>
                            <th class="border p-2">B5-CPD-4</th>
                            <th class="border p-2">B5-CPD-5</th>
                            <th class="border p-2">B5-CPD-6</th>
                            <th class="border p-2">B5-CPD-7</th>
                        </tr>

                        <!-- DESCRIPTION ROW -->
                        <tr class="bg-amber-800 text-white text-[11px]">
                            <th class="border p-2"></th>
                            <th class="border p-2">Description</th>
                            <th class="border p-2">Existing Sports in HEI</th>
                            <th class="border p-2">Sex (Sports Category)</th>
                            <th class="border p-2">Participates in SCUAA</th>
                            <th class="border p-2">Participates in ALCUA</th>
                            <th class="border p-2">Participates in PRISAA</th>
                            <th class="border p-2">Participates in Philippine National Games</th>
                            <th class="border p-2">Participates In Other Associations</th>
                            <th class="border p-2">Active in Association-Based/Intercollegiate Leagues (AY 2022-23)?</th>
                            <th class="border p-2">No. of Association-Based Leagues</th>
                            <th class="border p-2">Active in Provincial Leagues?</th>
                            <th class="border p-2">No. of Provincial Leagues</th>
                            <th class="border p-2">Active in Regional Leagues?</th>
                            <th class="border p-2">No. of Regional Leagues</th>
                            <th class="border p-2">Active in National Leagues?</th>
                            <th class="border p-2">No. of National Leagues</th>
                            <th class="border p-2">Active in International Leagues?</th>
                            <th class="border p-2">No. of International Leagues</th>
                            <th class="border p-2">Holds Departmental Intramurals?</th>
                            <th class="border p-2">Holds Interdepartment Intramurals?</th>
                            <th class="border p-2">Has Health/Fitness Program?</th>
                            <th class="border p-2">In-House Coaches Training?</th>
                            <th class="border p-2">Regional Coaches Training?</th>
                            <th class="border p-2">National Coaches Training?</th>
                            <th class="border p-2">In-House Technical Officiating?</th>
                            <th class="border p-2">Regional Technical Officiating?</th>
                            <th class="border p-2">National Technical Officiating?</th>
                            <th class="border p-2">Capacity-Building Seminar (2019+)?</th>
                        </tr>

                        <!-- INPUT GUIDE ROW -->
                        <tr class="bg-amber-700 text-white text-[10px]">
                            <th class="border p-2"></th>
                            <th class="border p-2">Input Guide</th>
                            <th class="border p-2">Select Sport</th>
                            <th class="border p-2">Men/Women/Mixed</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Input text (comma-separated)</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                            <th class="border p-2">Yes/No</th>
                        </tr>
                    </thead>

                    <tbody x-ref="tbody">
                        @foreach($sportsPrograms as $index => $sportsProgram)
                            @php
                                $sp = $sportsProgram->toArray();
                                $sportId = $sportsProgram->sport_id ?? 'new_' . $index;
                            @endphp
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-yellow-50">
                                <td class="border p-1">{{ $index + 1 }}</td>

                                <!-- ITEM CODE -->
                                <td class="border p-1">
                                    {{ $sportsProgram->sport->sport_code ?? ('B1-SPORT-' . ($index+1)) }}
                                </td>

                                <!-- B1-SPORTS-1: Sport selection -->
                                <td class="border p-1">
                                    <select name="sports_programs[{{ $index }}][sport_id]" class="w-full border rounded p-1 text-sm">
                                        <option value="">Select Sport</option>
                                        @foreach($sports as $sOpt)
                                            <option value="{{ $sOpt->sport_id }}" 
                                                {{ (int)old('sports_programs.' . $index . '.sport_id', $sportsProgram->sport_id) === (int)$sOpt->sport_id ? 'selected' : '' }}>
                                                {{ $sOpt->sport_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- Hidden field to preserve ID for updates -->
                                    <input type="hidden" name="sports_programs[{{ $index }}][id]" value="{{ $sportsProgram->id ?? '' }}">
                                </td>

                                <!-- B1-SPORTS-2: Category -->
                                <td class="border p-1">
                                    <select name="sports_programs[{{ $index }}][category]" class="w-full border rounded p-1 text-sm">
                                        <option value="">Select</option>
                                        <option value="Men" {{ old('sports_programs.' . $index . '.category', $sp['category'] ?? '') == 'Men' ? 'selected' : '' }}>Men</option>
                                        <option value="Women" {{ old('sports_programs.' . $index . '.category', $sp['category'] ?? '') == 'Women' ? 'selected' : '' }}>Women</option>
                                        <option value="Mixed" {{ old('sports_programs.' . $index . '.category', $sp['category'] ?? '') == 'Mixed' ? 'selected' : '' }}>Mixed</option>
                                    </select>
                                </td>

                                <!-- B2-ASSOC-1..B2-ASSOC-4 -->
                                <td class="border p-1">
                                    <select name="sports_programs[{{ $index }}][assoc_1]" class="w-full border rounded p-1 text-sm">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('sports_programs.' . $index . '.assoc_1', $sp['assoc_1'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('sports_programs.' . $index . '.assoc_1', $sp['assoc_1'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>

                                <td class="border p-1">
                                    <select name="sports_programs[{{ $index }}][assoc_2]" class="w-full border rounded p-1 text-sm">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('sports_programs.' . $index . '.assoc_2', $sp['assoc_2'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('sports_programs.' . $index . '.assoc_2', $sp['assoc_2'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>

                                <td class="border p-1">
                                    <select name="sports_programs[{{ $index }}][assoc_3a]" class="w-full border rounded p-1 text-sm">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('sports_programs.' . $index . '.assoc_3a', $sp['assoc_3a'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('sports_programs.' . $index . '.assoc_3a', $sp['assoc_3a'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>

                                <td class="border p-1">
                                    <select name="sports_programs[{{ $index }}][assoc_3b]" class="w-full border rounded p-1 text-sm">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('sports_programs.' . $index . '.assoc_3b', $sp['assoc_3b'] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('sports_programs.' . $index . '.assoc_3b', $sp['assoc_3b'] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>

                                <!-- B2-ASSOC-4: TEXT AREA -->
                                <td class="border p-1">
                                    <textarea name="sports_programs[{{ $index }}][assoc_other]" rows="2" class="w-full border rounded text-sm p-1" placeholder="e.g., Association 1, Association 2">{{ old('sports_programs.' . $index . '.assoc_other', $sp['assoc_other'] ?? '') }}</textarea>
                                </td>

                                <!-- B3-LEAGUE-1..10 -->
                                @for($i = 1; $i <= 10; $i++)
                                    @php
                                        $isActive = $i % 2 == 1;
                                        $activeName = 'league_active_' . ceil($i/2);
                                        $countName = 'league_count_' . ceil($i/2);
                                    @endphp

                                    @if($isActive)
                                        <td class="border p-1">
                                            <select name="sports_programs[{{ $index }}][{{ $activeName }}]" class="w-full border rounded p-1 text-sm">
                                                <option value="">Select</option>
                                                <option value="Yes" {{ old('sports_programs.' . $index . '.' . $activeName, $sp[$activeName] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ old('sports_programs.' . $index . '.' . $activeName, $sp[$activeName] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </td>
                                    @else
                                        <td class="border p-1">
                                            <input type="number" min="0" name="sports_programs[{{ $index }}][{{ $countName }}]"
                                                value="{{ old('sports_programs.' . $index . '.' . $countName, $sp[$countName] ?? '') }}"
                                                class="w-20 border rounded text-center text-sm" />
                                        </td>
                                    @endif
                                @endfor

                                <!-- B4-WELL-1..3 -->
                                @for($i = 1; $i <= 3; $i++)
                                    <td class="border p-1">
                                        <select name="sports_programs[{{ $index }}][well_{{ $i }}]" class="w-full border rounded p-1 text-sm">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ old('sports_programs.' . $index . '.well_' . $i, $sp['well_' . $i] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ old('sports_programs.' . $index . '.well_' . $i, $sp['well_' . $i] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </td>
                                @endfor

                                <!-- B5-CPD-1..7 -->
                                @for($i = 1; $i <= 7; $i++)
                                    <td class="border p-1">
                                        <select name="sports_programs[{{ $index }}][cpd_{{ $i }}]" class="w-full border rounded p-1 text-sm">
                                            <option value="">Select</option>
                                            <option value="Yes" {{ old('sports_programs.' . $index . '.cpd_' . $i, $sp['cpd_' . $i] ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ old('sports_programs.' . $index . '.cpd_' . $i, $sp['cpd_' . $i] ?? '') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </td>
                                @endfor

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Add Row Button and Total Records -->
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Total Records: <span x-ref="totalRecords" x-text="$refs.tbody?.children.length || 0"></span>
                </div>
                <button type="button" 
                        @click="addNewRow()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Sport
                </button>
            </div>

            <!-- Save Button -->
            <div class="mt-4 text-right">
                <button type="submit" class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-2 rounded shadow">Save CHED Form B Report</button>
            </div>
            <!-- Add this after the save button in Form B section -->
            <div class="mt-4 flex justify-end space-x-4">
                <a href="{{ route('reports.export-form', ['form' => 'B', 'format' => 'pdf']) }}" 
                class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('reports.export-form', ['form' => 'B', 'format' => 'xlsx']) }}" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
            </div>
        </form>

        <!-- Hidden Template for New Rows -->
        <template x-ref="newRowTemplate">
            <tr class="odd:bg-gray-50 even:bg-white hover:bg-yellow-50">
                <td class="border p-1">1</td>
                <td class="border p-1">B1-SPORT-1</td>
                
                <!-- B1-SPORTS-1: Sport selection -->
                <td class="border p-1">
                    <select name="new_sports[NEW_INDEX][sport_id]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select Sport</option>
                        @foreach($sports as $sOpt)
                            <option value="{{ $sOpt->sport_id }}">{{ $sOpt->sport_name }}</option>
                        @endforeach
                    </select>
                </td>

                <!-- B1-SPORTS-2: Category -->
                <td class="border p-1">
                    <select name="new_sports[NEW_INDEX][category]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                        <option value="Mixed">Mixed</option>
                    </select>
                </td>

                <!-- B2-ASSOC fields -->
                <td class="border p-1">
                    <select name="new_sports[NEW_INDEX][assoc_1]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_sports[NEW_INDEX][assoc_2]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_sports[NEW_INDEX][assoc_3a]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_sports[NEW_INDEX][assoc_3b]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <textarea name="new_sports[NEW_INDEX][assoc_other]" rows="2" class="w-full border rounded text-sm p-1" placeholder="e.g., Association 1, Association 2"></textarea>
                </td>

                <!-- B3-LEAGUE fields -->
                @for($i = 1; $i <= 5; $i++)
                    <td class="border p-1">
                        <select name="new_sports[NEW_INDEX][league_active_{{ $i }}]" class="w-full border rounded p-1 text-sm">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </td>
                    <td class="border p-1">
                        <input type="number" min="0" name="new_sports[NEW_INDEX][league_count_{{ $i }}]" value="0" class="w-20 border rounded text-center text-sm" />
                    </td>
                @endfor

                <!-- B4-WELL fields -->
                @for($i = 1; $i <= 3; $i++)
                    <td class="border p-1">
                        <select name="new_sports[NEW_INDEX][well_{{ $i }}]" class="w-full border rounded p-1 text-sm">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </td>
                @endfor

                <!-- B5-CPD fields -->
                @for($i = 1; $i <= 7; $i++)
                    <td class="border p-1">
                        <select name="new_sports[NEW_INDEX][cpd_{{ $i }}]" class="w-full border rounded p-1 text-sm">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </td>
                @endfor
            </tr>
        </template>
    </div>

    <!-- FORM C: Student-Athletes Table -->
    <div x-show="activeTab === 'formC'" x-data="{
        newRowCount: 0,
        addNewRow() {
            const template = this.$refs.newRowTemplate;
            const newRow = template.content.cloneNode(true);
            const tbody = this.$refs.tbody;
            const newIndex = tbody.children.length + 1;
            
            // Update row number
            newRow.querySelector('td:first-child').textContent = newIndex;
            
            // Update all name attributes with new index
            const inputs = newRow.querySelectorAll('[name]');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace('NEW_INDEX', 'new_' + this.newRowCount);
                input.setAttribute('name', newName);
            });
            
            tbody.appendChild(newRow);
            this.newRowCount++;
            
            // Update total records display
            this.updateTotalRecords();
        },
        updateTotalRecords() {
            const totalRecords = this.$refs.tbody.children.length;
            if (this.$refs.totalRecords) {
                this.$refs.totalRecords.textContent = totalRecords;
            }
        }
    }">
        <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">CHED Form C: Profile and Benefits of Student-Athletes</h3>
                    <p class="mt-2 text-sm text-blue-700">This form displays student-athlete profiles and benefits information. The C2-BEN fields are editable for updates.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('reports.save-student-athletes') }}">
            @csrf

            <div class="overflow-x-auto bg-white border rounded-lg shadow-sm">
                <table class="min-w-[3200px] w-full border-collapse text-sm text-gray-800 text-center">
                    <thead>
                        <!-- ITEM CODE / COLUMN CODES -->
                        <tr class="bg-blue-900 text-white text-xs uppercase">
                            <th class="border p-2">#</th>
                            <th class="border p-2">C1-PROF-1</th>
                            <th class="border p-2">C1-PROF-2</th>
                            <th class="border p-2">C1-PROF-3</th>
                            <th class="border p-2">C1-PROF-4</th>
                            <th class="border p-2">C1-PROF-5</th>
                            <th class="border p-2">C1-PROF-6</th>
                            <th class="border p-2">C1-PROF-7</th>
                            <th class="border p-2">C1-PROF-8</th>
                            <th class="border p-2">C1-PROF-9</th>
                            <th class="border p-2">C1-PROF-10</th>
                            <th class="border p-2">C1-PROF-11</th>
                            <th class="border p-2">C1-PROF-12</th>
                            <th class="border p-2">C1-PROF-13</th>
                            <th class="border p-2">C2-BEN-1</th>
                            <th class="border p-2">C2-BEN-2</th>
                            <th class="border p-2">C2-BEN-3</th>
                            <th class="border p-2">C2-BEN-4</th>
                            <th class="border p-2">C2-BEN-5</th>
                            <th class="border p-2">C2-BEN-6</th>
                            <th class="border p-2">C2-BEN-7</th>
                            <th class="border p-2">C2-BEN-8</th>
                            <th class="border p-2">C2-BEN-9</th>
                            <th class="border p-2">C2-BEN-10</th>
                            <th class="border p-2">C2-BEN-11</th>
                        </tr>

                        <!-- DESCRIPTION ROW -->
                        <tr class="bg-blue-800 text-white text-[11px]">
                            <th class="border p-2">Description</th>
                            <th class="border p-2">Name of Student</th>
                            <th class="border p-2">Age</th>
                            <th class="border p-2">
                                Sports Program<br>
                                <small class="font-normal italic">
                                    (NOTE: Drop-down choices for this column will reflect on sports inputted in Sheet B. - Sports Programs. Complete Sheet B first.)
                                </small>
                            </th>
                            <th class="border p-2">
                                Sex<br>
                                <small class="font-normal italic">(Athlete)</small>
                            </th>
                            <th class="border p-2">Academic Course</th>
                            <th class="border p-2">Highest Level of Competition Participation</th>
                            <th class="border p-2">Highest Accomplishment as an Athlete</th>
                            <th class="border p-2">
                                If with accomplishment in international competition, please indicate name of international competition
                            </th>
                            <th class="border p-2">
                                Has attended Special Training and Seminars outside School for their sport<br>
                                <small class="font-normal italic">(Regional)</small>
                            </th>
                            <th class="border p-2">
                                Has attended Special Training and Seminars outside School for their sport<br>
                                <small class="font-normal italic">(National)</small>
                            </th>
                            <th class="border p-2">
                                Has attended Special Training and Seminars outside School for their sport<br>
                                <small class="font-normal italic">(International)</small>
                            </th>
                            <th class="border p-2">
                                Frequency of Training<br>
                                <small class="font-normal italic">(No. of days in a week)</small>
                            </th>
                            <th class="border p-2">No. of training hours per day of training</th>
                            <th class="border p-2">Athlete's Scholarship Status</th>
                            <th class="border p-2">Athlete's Monthly Living Allowance from HEI</th>
                            <th class="border p-2">Athlete's Board and Lodging support from the HEI</th>
                            <th class="border p-2">Athlete's Medical Insurance support from the HEI</th>
                            <th class="border p-2">Athlete's Training Uniforms from the HEI</th>
                            <th class="border p-2">
                                Average Allowance Per<br>
                                Tournament/Competition<br>
                                <small class="font-normal italic">(aside from Monthly Living Allowance)</small>
                            </th>
                            <th class="border p-2">Sponsorship of playing uniforms from the HEI</th>
                            <th class="border p-2">Sponsorship of playing gears from the HEI</th>
                            <th class="border p-2">Athlete is excused from his/her academic obligations during competitions</th>
                            <th class="border p-2">HEI provides Athlete with a Flexible Academic Schedule</th>
                            <th class="border p-2">Athlete is provided academic tutorials and/or other interventions by HEI</th>
                        </tr>

                        <!-- INPUT GUIDE ROW -->
                        <tr class="bg-blue-700 text-white text-[10px]">
                            <th class="border p-2">Input Guide</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select from List</th>
                        </tr>
                    </thead>

                    <tbody x-ref="tbody">
                        @foreach($athletes as $index => $athlete)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-blue-50" data-id="{{ $athlete->athlete_id }}">
                                <!-- ITEM CODE (Row Number) -->
                                <td class="border p-1">{{ $index + 1 }}</td>

                                <!-- C1-PROF FIELDS (Non-editable) -->
                                <td class="border p-1 bg-gray-100">{{ $athlete->full_name }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->age }}</td>
                                <td class="border p-1 bg-gray-100">{{ optional($athlete->sport)->sport_name }}</td>
                                <td class="border p-1 bg-gray-100">{{ ucfirst($athlete->gender) }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->academic_course }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->highest_competition_level }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->highest_accomplishment }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->international_competition_name }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->training_seminars_regional ? 'Yes' : 'No' }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->training_seminars_national ? 'Yes' : 'No' }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->training_seminars_international ? 'Yes' : 'No' }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->training_frequency_days }}</td>
                                <td class="border p-1 bg-gray-100">{{ $athlete->training_hours_per_day }}</td>

                                <!-- C2-BEN FIELDS (Editable) -->
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][scholarship_status]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->scholarship_status == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->scholarship_status == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Partial Subsidy" {{ $athlete->scholarship_status == 'Partial Subsidy' ? 'selected' : '' }}>Partial Subsidy</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <input type="number" name="athletes[{{ $athlete->athlete_id }}][monthly_living_allowance]" 
                                           value="{{ $athlete->monthly_living_allowance }}" 
                                           class="w-24 border rounded p-1 text-sm text-center">
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][board_lodging_support]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->board_lodging_support == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->board_lodging_support == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Partial Subsidy" {{ $athlete->board_lodging_support == 'Partial Subsidy' ? 'selected' : '' }}>Partial Subsidy</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][medical_insurance_support]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->medical_insurance_support == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->medical_insurance_support == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Partial Subsidy" {{ $athlete->medical_insurance_support == 'Partial Subsidy' ? 'selected' : '' }}>Partial Subsidy</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][training_uniforms_support]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->training_uniforms_support == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->training_uniforms_support == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Partial Subsidy" {{ $athlete->training_uniforms_support == 'Partial Subsidy' ? 'selected' : '' }}>Partial Subsidy</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <input type="number" name="athletes[{{ $athlete->athlete_id }}][average_tournament_allowance]" 
                                           value="{{ $athlete->average_tournament_allowance }}" 
                                           class="w-24 border rounded p-1 text-sm text-center">
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][playing_uniforms_sponsorship]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->playing_uniforms_sponsorship == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->playing_uniforms_sponsorship == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Partial Subsidy" {{ $athlete->playing_uniforms_sponsorship == 'Partial Subsidy' ? 'selected' : '' }}>Partial Subsidy</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][playing_gears_sponsorship]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->playing_gears_sponsorship == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->playing_gears_sponsorship == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Partial Subsidy" {{ $athlete->playing_gears_sponsorship == 'Partial Subsidy' ? 'selected' : '' }}>Partial Subsidy</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][excused_from_academic_obligations]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->excused_from_academic_obligations == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->excused_from_academic_obligations == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][flexible_academic_schedule]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->flexible_academic_schedule == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->flexible_academic_schedule == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>
                                <td class="border p-1">
                                    <select name="athletes[{{ $athlete->athlete_id }}][academic_tutorials_support]" class="w-full border rounded p-1 text-sm">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ $athlete->academic_tutorials_support == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $athlete->academic_tutorials_support == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Add Row Button and Total Records -->
            <!-- Save Button -->
            <!-- Add this after the save button in Form C section -->
            <div class="mt-4 flex justify-end space-x-4">
                <a href="{{ route('reports.export-form', ['form' => 'C', 'format' => 'pdf']) }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('reports.export-form', ['form' => 'C', 'format' => 'xlsx']) }}" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
            </div>
        </form>

        <!-- Hidden Template for New Rows -->
        <template x-ref="newRowTemplate">
            <tr class="odd:bg-gray-50 even:bg-white hover:bg-blue-50">
                <td class="border p-1">1</td>

                <!-- C1-PROF FIELDS (Editable for new rows) -->
                <td class="border p-1">
                    <input type="text" name="new_athletes[NEW_INDEX][full_name]" class="w-full border rounded p-1 text-sm" placeholder="Full Name">
                </td>
                <td class="border p-1">
                    <input type="number" name="new_athletes[NEW_INDEX][age]" class="w-20 border rounded p-1 text-sm text-center" placeholder="Age">
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][sport_id]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select Sport</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->sport_id }}">{{ $sport->sport_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][gender]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_athletes[NEW_INDEX][academic_course]" class="w-full border rounded p-1 text-sm" placeholder="Academic Course">
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][highest_competition_level]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Local">Local</option>
                        <option value="Regional">Regional</option>
                        <option value="National">National</option>
                        <option value="International">International</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][highest_accomplishment]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Champion">Champion</option>
                        <option value="Runner-up">Runner-up</option>
                        <option value="Finalist">Finalist</option>
                        <option value="Participant">Participant</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_athletes[NEW_INDEX][international_competition_name]" class="w-full border rounded p-1 text-sm" placeholder="International Competition">
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][training_seminars_regional]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][training_seminars_national]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][training_seminars_international]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="number" name="new_athletes[NEW_INDEX][training_frequency_days]" class="w-20 border rounded p-1 text-sm text-center" placeholder="Days">
                </td>
                <td class="border p-1">
                    <input type="number" name="new_athletes[NEW_INDEX][training_hours_per_day]" class="w-20 border rounded p-1 text-sm text-center" placeholder="Hours">
                </td>

                <!-- C2-BEN FIELDS (Editable) -->
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][scholarship_status]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Partial Subsidy">Partial Subsidy</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="number" name="new_athletes[NEW_INDEX][monthly_living_allowance]" value="0" class="w-24 border rounded p-1 text-sm text-center">
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][board_lodging_support]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Partial Subsidy">Partial Subsidy</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][medical_insurance_support]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Partial Subsidy">Partial Subsidy</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][training_uniforms_support]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Partial Subsidy">Partial Subsidy</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="number" name="new_athletes[NEW_INDEX][average_tournament_allowance]" value="0" class="w-24 border rounded p-1 text-sm text-center">
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][playing_uniforms_sponsorship]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Partial Subsidy">Partial Subsidy</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][playing_gears_sponsorship]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Partial Subsidy">Partial Subsidy</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][excused_from_academic_obligations]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][flexible_academic_schedule]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_athletes[NEW_INDEX][academic_tutorials_support]" class="w-full border rounded p-1 text-sm">
                        <option value="">-- Select --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
            </tr>
        </template>
    </div>

    <!-- FORM D: Sports Personnel -->
    <div x-show="activeTab === 'formD'" x-data="{
        newRowCount: 0,
        addNewRow() {
            const template = this.$refs.newRowTemplate;
            const newRow = template.content.cloneNode(true);
            const tbody = this.$refs.tbody;
            const newIndex = tbody.children.length + 1;
            
            // Update row number and item code
            newRow.querySelector('td:first-child').textContent = newIndex;
            newRow.querySelector('td:nth-child(2)').textContent = 'D-PERS-' + newIndex;
            
            // Update all name attributes with new index
            const inputs = newRow.querySelectorAll('[name]');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace('NEW_INDEX', 'new_' + this.newRowCount);
                input.setAttribute('name', newName);
            });
            
            tbody.appendChild(newRow);
            this.newRowCount++;
            
            // Update total records display
            this.updateTotalRecords();
        },
        updateTotalRecords() {
            const totalRecords = this.$refs.tbody.children.length;
            if (this.$refs.totalRecords) {
                this.$refs.totalRecords.textContent = totalRecords;
            }
        }
    }">
        <div class="bg-purple-50 border border-purple-200 rounded p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-purple-800">CHED Form D: Sports Personnel Report</h3>
                    <p class="mt-2 text-sm text-purple-700">Please review the following sports personnel information.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('reports.save-sports-personnel') }}">
            @csrf

            <div class="overflow-x-auto bg-white border rounded-lg shadow-sm">
                <table class="min-w-[3400px] w-full border-collapse text-sm text-gray-800 text-center">
                    <thead>
                        <!-- ITEM CODE / COLUMN CODES -->
                        <tr class="bg-purple-900 text-white text-xs uppercase">
                            <th class="border p-2">#</th>
                            <th class="border p-2">ITEM CODE</th>
                            <th class="border p-2">D1-PROFILE-1</th>
                            <th class="border p-2">D1-PROFILE-2</th>
                            <th class="border p-2">D1-PROFILE-3</th>
                            <th class="border p-2">D1-PROFILE-4</th>
                            <th class="border p-2">D1-PROFILE-5</th>
                            <th class="border p-2">D1-PROFILE-6</th>
                            <th class="border p-2">D1-PROFILE-7</th>
                            <th class="border p-2">D1-PROFILE-8</th>
                            <th class="border p-2">D1-PROFILE-9</th>
                            <th class="border p-2">D2-EXP-1</th>
                            <th class="border p-2">D2-EXP-2</th>
                            <th class="border p-2">D2-EXP-3</th>
                            <th class="border p-2">D2-EXP-4</th>
                            <th class="border p-2">D2-EXP-5</th>
                            <th class="border p-2">D2-EXP-6</th>
                            <th class="border p-2">D3-LIC-1</th>
                            <th class="border p-2">D3-LIC-2</th>
                            <th class="border p-2">D3-LIC-3</th>
                            <th class="border p-2">D3-LIC-4</th>
                            <th class="border p-2">D4-EDUC-1</th>
                            <th class="border p-2">D4-EDUC-2</th>
                            <th class="border p-2">D4-EDUC-3</th>
                            <th class="border p-2">D4-EDUC-4</th>
                        </tr>

                        <!-- DESCRIPTION ROW -->
                        <tr class="bg-purple-800 text-white text-[11px]">
                            <th class="border p-2">Description</th>
                            <th class="border p-2"></th>
                            <th class="border p-2">Name of Sports Personnel</th>
                            <th class="border p-2">Age</th>
                            <th class="border p-2">Sex</th>
                            <th class="border p-2">
                                Sports Program<br>
                                <small class="font-normal italic">
                                    (NOTE: Choices will reflect on sports inputted in Sheet B. - Sports Programs. Complete Sheet B first.)
                                </small>
                            </th>
                            <th class="border p-2">Current Position Title<br><small class="font-normal italic">(Based on Job Contract)</small></th>
                            <th class="border p-2">Sports Program Position<br><small class="font-normal italic">(Assigned Role)</small></th>
                            <th class="border p-2">Employment Status</th>
                            <th class="border p-2">
                                Sports Program Position Salary Per Month<br>
                                <small class="font-normal italic">(Based on Sports Program)</small>
                            </th>
                            <th class="border p-2">
                                Years of Experience as Athletic Personnel<br>
                                <small class="font-normal italic">(not counting playing years, Round up to the nearest year)</small>
                            </th>
                            <th class="border p-2">Was personnel a previous athlete?</th>
                            <th class="border p-2">Highest Level of Competition Participation as an athlete</th>
                            <th class="border p-2">Highest Accomplishment as Athlete</th>
                            <th class="border p-2">
                                If with accomplishment in international competition, please indicate name of international competition
                            </th>
                            <th class="border p-2">Highest Accomplishment as Coach</th>
                            <th class="border p-2">
                                If with accomplishment in international competition, please indicate name of international competition
                            </th>
                            <th class="border p-2">Holds Membership and licenses at the Regional Level</th>
                            <th class="border p-2">Holds Membership and licenses at the National Level</th>
                            <th class="border p-2">Holds Membership and licenses at the International Level</th>
                            <th class="border p-2">
                                If with membership and licenses at the International Level, please indicate name of international membership and license
                            </th>
                            <th class="border p-2">Highest Degree Attained</th>
                            <th class="border p-2">Program Name/Course<br><small class="font-normal italic">(Bachelor's Degree)</small></th>
                            <th class="border p-2">Program Name<br><small class="font-normal italic">(Masteral Degree)</small></th>
                            <th class="border p-2">Program Name<br><small class="font-normal italic">(Doctorate Degree)</small></th>
                        </tr>

                        <!-- INPUT GUIDE ROW -->
                        <tr class="bg-purple-700 text-white text-[10px]">
                            <th class="border p-2">Input Guide</th>
                            <th class="border p-2"></th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Male, Female</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">D1-PROFILE-7</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Number</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Select Yes/No</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Select from List</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Text</th>
                            <th class="border p-2">Text</th>
                        </tr>
                    </thead>

                    <tbody x-ref="tbody">
                        @forelse($coaches as $index => $coach)
                        <tr class="odd:bg-gray-50 even:bg-white hover:bg-purple-50">
                            <!-- ITEM CODE (Row Number) -->
                            <td class="border p-1">{{ $index + 1 }}</td>
                            
                            <!-- ITEM CODE (Coach ID or generated code) -->
                            <td class="border p-1">{{ 'D-PERS-' . ($index + 1) }}</td>

                            <!-- D1-PROFILE FIELDS -->
                            <td class="border p-1 bg-gray-100">{{ $coach->full_name }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->age ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->gender ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->sport->sport_name ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->position_title ?? 'Coach' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->assigned_role ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $coach->employment_status == 'Full-time' ? 'bg-green-100 text-green-800' : 
                                       ($coach->employment_status == 'Part-time' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ $coach->employment_status ?? 'Not specified' }}
                                </span>
                            </td>
                            <td class="border p-1 bg-gray-100 text-right">
                                @if($coach->monthly_salary)
                                    ₱{{ number_format($coach->monthly_salary, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border p-1 bg-gray-100">{{ $coach->years_experience ?? '-' }}</td>

                            <!-- D2-EXP FIELDS -->
                            <td class="border p-1 bg-gray-100">{{ $coach->was_previous_athlete ? 'Yes' : 'No' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->highest_competition_level ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->highest_accomplishment_athlete ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->international_competition_name_athlete ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->highest_accomplishment_coach ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->international_competition_name_coach ?? '-' }}</td>

                            <!-- D3-LIC FIELDS -->
                            <td class="border p-1 bg-gray-100">{{ $coach->regional_license ? 'Yes' : 'No' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->national_license ? 'Yes' : 'No' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->international_license ? 'Yes' : 'No' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->international_license_name ?? '-' }}</td>

                            <!-- D4-EDUC FIELDS -->
                            <td class="border p-1 bg-gray-100">{{ $coach->highest_degree ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->bachelors_program ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->masters_program ?? '-' }}</td>
                            <td class="border p-1 bg-gray-100">{{ $coach->doctorate_program ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="25" class="border p-4 text-center text-gray-500">
                                No sports personnel records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Add this after the save button in Form D section -->
            <div class="mt-4 flex justify-end space-x-4">
                <a href="{{ route('reports.export-form', ['form' => 'D', 'format' => 'pdf']) }}" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('reports.export-form', ['form' => 'D', 'format' => 'xlsx']) }}" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
            </div>
        </form>

        <!-- Hidden Template for New Rows -->
        <template x-ref="newRowTemplate">
            <tr class="odd:bg-gray-50 even:bg-white hover:bg-purple-50">
                <td class="border p-1">1</td>
                <td class="border p-1">D-PERS-1</td>

                <!-- D1-PROFILE FIELDS (Editable for new rows) -->
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][full_name]" class="w-full border rounded p-1 text-sm" placeholder="Full Name">
                </td>
                <td class="border p-1">
                    <input type="number" name="new_personnel[NEW_INDEX][age]" class="w-20 border rounded p-1 text-sm text-center" placeholder="Age">
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][gender]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][sport_id]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select Sport</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->sport_id }}">{{ $sport->sport_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][position_title]" class="w-full border rounded p-1 text-sm" placeholder="Position Title">
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][assigned_role]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select Role</option>
                        <option value="Head Coach">Head Coach</option>
                        <option value="Assistant Coach">Assistant Coach</option>
                        <option value="Trainer">Trainer</option>
                        <option value="Sports Director">Sports Director</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][employment_status]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contractual">Contractual</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="number" name="new_personnel[NEW_INDEX][monthly_salary]" value="0" class="w-24 border rounded p-1 text-sm text-center">
                </td>
                <td class="border p-1">
                    <input type="number" name="new_personnel[NEW_INDEX][years_experience]" value="0" class="w-20 border rounded p-1 text-sm text-center">
                </td>

                <!-- D2-EXP FIELDS -->
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][was_previous_athlete]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][highest_competition_level]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Local">Local</option>
                        <option value="Regional">Regional</option>
                        <option value="National">National</option>
                        <option value="International">International</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][highest_accomplishment_athlete]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Champion">Champion</option>
                        <option value="Runner-up">Runner-up</option>
                        <option value="Finalist">Finalist</option>
                        <option value="Participant">Participant</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][international_competition_name_athlete]" class="w-full border rounded p-1 text-sm" placeholder="International Competition">
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][highest_accomplishment_coach]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Champion">Champion</option>
                        <option value="Runner-up">Runner-up</option>
                        <option value="Finalist">Finalist</option>
                        <option value="Participant">Participant</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][international_competition_name_coach]" class="w-full border rounded p-1 text-sm" placeholder="International Competition">
                </td>

                <!-- D3-LIC FIELDS -->
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][regional_license]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][national_license]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][international_license]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][international_license_name]" class="w-full border rounded p-1 text-sm" placeholder="License Name">
                </td>

                <!-- D4-EDUC FIELDS -->
                <td class="border p-1">
                    <select name="new_personnel[NEW_INDEX][highest_degree]" class="w-full border rounded p-1 text-sm">
                        <option value="">Select</option>
                        <option value="Bachelor's">Bachelor's</option>
                        <option value="Master's">Master's</option>
                        <option value="Doctorate">Doctorate</option>
                    </select>
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][bachelors_program]" class="w-full border rounded p-1 text-sm" placeholder="Bachelor's Program">
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][masters_program]" class="w-full border rounded p-1 text-sm" placeholder="Master's Program">
                </td>
                <td class="border p-1">
                    <input type="text" name="new_personnel[NEW_INDEX][doctorate_program]" class="w-full border rounded p-1 text-sm" placeholder="Doctorate Program">
                </td>
            </tr>
        </template>
    </div>

<!-- FORM E: Sports Budget and Expenditure -->
<div x-show="activeTab === 'formE'">
    <!-- Configuration Notice -->
    <div class="bg-purple-50 border border-purple-200 rounded p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-purple-800">Sports Budget and Expenditure</h3>
                <div class="mt-2 text-sm text-purple-700">
                    <p>Provide budget and expenditure data for AY 2022-2023. Input amounts in cells shaded in color.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form E Configuration -->
    <form method="POST" action="{{ route('reports.save-budget-expenditure') }}" class="bg-gray-50 p-6 rounded border" id="budgetForm">
        @csrf
        <h3 class="text-lg font-semibold mb-4 text-gray-900">FORM E: Sports Budget and Expenditure</h3>
        
        <!-- HEI Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Name of HEI</label>
            <input type="text" name="hei_name" value="{{ old('hei_name', $institutionalData['hei_name'] ?? '') }}" 
                   class="w-full md:w-1/2 border rounded p-2 bg-white" readonly>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- SECTION E1: Fund Sources -->
            <div class="lg:col-span-1">
                <div class="bg-blue-50 p-4 rounded border">
                    <h4 class="font-semibold text-blue-900 mb-4">SECTION E1: Fund Sources</h4>
                    
                    <div class="space-y-4">
                        @foreach([
                            'athletic_fee_per_student' => 'Athletic Fee Per Student Per Semester',
                            'collection_athletic_fees' => 'Collection from Athletic Fees/Miscellaneous',
                            'collection_donors' => 'Collection from Donors',
                            'fundraising_income' => 'Fundraising and/or Income Generating Programs',
                            'local_govt_funding' => 'Funding Support from Local Government funds/allocations',
                            'national_govt_funding' => 'Funding Support from National Government funds/allocations',
                            'other_sources' => 'Other Sources Not Included Above'
                        ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="number" 
                                    name="fund_sources[{{ $key }}]" 
                                    value="{{ old('fund_sources.' . $key, $budgetData['fund_sources'][$key] ?? 0) }}"
                                    class="budget-input w-full border rounded p-2 {{ in_array($key, ['collection_athletic_fees', 'collection_donors', 'fundraising_income', 'local_govt_funding', 'national_govt_funding', 'other_sources']) ? 'bg-blue-50' : '' }}"
                                    min="0" step="0.01" placeholder="0.00">
                            </div>
                        @endforeach
                        
                        <!-- Estimated Amount Display -->
                        <div class="mt-4 p-3 bg-blue-100 rounded">
                            <label class="block text-sm font-semibold text-blue-900 mb-1">ESTIMATED AMOUNT</label>
                            <div class="text-xl font-bold text-blue-900" id="totalFundSources">
                                ₱0.00
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION E2: Expenditures -->
            <div class="lg:col-span-1">
                <div class="bg-green-50 p-4 rounded border">
                    <h4 class="font-semibold text-green-900 mb-4">SECTION E2: Expenditures</h4>
                    
                    <div class="space-y-4">
                        <!-- Student Athletes -->
                        <div class="space-y-3">
                            <h5 class="font-medium text-green-800">STUDENT-ATHLETES</h5>
                            @foreach([
                                'scholarships_male' => 'Scholarships & fees for MALE ATHLETES',
                                'scholarships_female' => 'Scholarships & fees for FEMALE ATHLETES',
                                'monthly_allowances' => 'Monthly Living Allowances for all athletes',
                                'training_allowances' => 'Training Allowances (excluding living allowances)',
                                'board_lodging' => 'Board and Lodging for Student Athletes',
                                'training_fees' => 'Training Fees for Sports Camps/Clinics',
                                'medical_expenses' => 'Medical expenses & insurance for athletes',
                                'vitamins_medicines' => 'Vitamins and Medicines for Student Athletes',
                                'other_athlete_expenses' => 'Other expenses for athletes'
                            ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="number" name="expenditures[{{ $key }}]" 
                                       value="{{ old('expenditures.' . $key, $budgetData['expenditures'][$key] ?? 0) }}"
                                       class="budget-input w-full border rounded p-2 bg-green-50"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>

                        <!-- Personnel -->
                        <div class="space-y-3">
                            <h5 class="font-medium text-green-800">PERSONNEL</h5>
                            @foreach([
                                'salary_athletic_director' => 'Salaries for Athletic Director',
                                'salary_head_coaches' => 'Salaries for Head Coaches',
                                'salary_assistant_coaches' => 'Salaries for Assistant Coaches',
                                'salary_trainers' => 'Salaries for Body Conditioning Trainers',
                                'salary_maintenance_staff' => 'Salaries for Maintenance Staff',
                                'salary_other_personnel' => 'Salaries of Other Athletic Personnel',
                                'personnel_uniforms' => 'Personnel Uniforms & Supplies',
                                'personnel_training' => 'Training and Seminar Fees for Personnel',
                                'other_personnel_expenses' => 'Other expenses for Personnel'
                            ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="number" name="expenditures[{{ $key }}]" 
                                       value="{{ old('expenditures.' . $key, $budgetData['expenditures'][$key] ?? 0) }}"
                                       class="budget-input w-full border rounded p-2 bg-green-50"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>

                        <!-- Competitions -->
                        <div class="space-y-3">
                            <h5 class="font-medium text-green-800">COMPETITIONS</h5>
                            @foreach([
                                'competition_fees' => 'Entry/Registration Fees for Competitions',
                                'game_allowances_athletes' => 'Game/Competition Allowances for Athletes',
                                'game_incentives_athletes' => 'Game/Competition Incentives for Athletes',
                                'game_incentives_coaches' => 'Game/Competition Incentives for Coaches',
                                'parade_uniforms' => 'Parade Uniforms for Athletes and Personnel',
                                'game_uniforms' => 'Game Uniforms for Athletes and Personnel',
                                'honorarium_coaches' => 'Honorarium for Coaches during Competition',
                                'honorarium_officials' => 'Honorarium for Technical Officials',
                                'honorarium_staff' => 'Honorarium for Other Staff during Competition',
                                'sports_equipment' => 'Sports Equipment & Gadgets for Competitions',
                                'board_lodging_competition' => 'Board and Lodging during Competition',
                                'transportation_competition' => 'Transportation during Competition',
                                'first_aid_competition' => 'First Aid Expenses during Competition',
                                'association_membership' => 'Athletic Association Membership Fees',
                                'other_competition_expenses' => 'Other expenses related to games/competition'
                            ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="number" name="expenditures[{{ $key }}]" 
                                       value="{{ old('expenditures.' . $key, $budgetData['expenditures'][$key] ?? 0) }}"
                                       class="budget-input w-full border rounded p-2 bg-green-50"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>

                        <!-- Intramurals -->
                        <div class="space-y-3">
                            <h5 class="font-medium text-green-800">INTRAMURALS</h5>
                            @foreach([
                                'venue_rental_intramurals' => 'Rental of Game Venues for Intrams',
                                'uniforms_intramurals' => 'Game Uniforms for Intrams',
                                'honorarium_officials_intramurals' => 'Honorarium for Technical Officials for Intrams',
                                'other_intramurals_expenses' => 'Other expenses related to Intrams'
                            ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="number" name="expenditures[{{ $key }}]" 
                                       value="{{ old('expenditures.' . $key, $budgetData['expenditures'][$key] ?? 0) }}"
                                       class="budget-input w-full border rounded p-2 bg-green-50"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>

                        <!-- Facilities -->
                        <div class="space-y-3">
                            <h5 class="font-medium text-green-800">FACILITIES</h5>
                            @foreach([
                                'facility_renovation' => 'Renovation/Upgrading of Facilities',
                                'sports_equipment_acquisition' => 'Acquisition of Sports Equipment',
                                'maintenance_supplies' => 'Supplies for maintenance of facilities',
                                'other_facility_expenses' => 'Other expenses related to facilities'
                            ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                <input type="number" name="expenditures[{{ $key }}]" 
                                       value="{{ old('expenditures.' . $key, $budgetData['expenditures'][$key] ?? 0) }}"
                                       class="budget-input w-full border rounded p-2 bg-green-50"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            @endforeach
                        </div>

                        <!-- Estimated Expenditure Display -->
                        <div class="mt-4 p-3 bg-green-100 rounded">
                            <label class="block text-sm font-semibold text-green-900 mb-1">ESTIMATED AMOUNT OF EXPENDITURE</label>
                            <div class="text-xl font-bold text-green-900" id="totalExpenditures">
                                ₱0.00
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="lg:col-span-1">
                <div class="bg-amber-50 p-4 rounded border">
                    <h4 class="font-semibold text-amber-900 mb-4">SUMMARY</h4>
                    
                    <div class="space-y-4">
                        <div class="text-sm text-amber-800 mb-4">
                            This section shows the estimated budget and expenditures. Please check if inputs are correct.
                        </div>

                        <!-- Budget Summary -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-white rounded border">
                                <span class="font-medium text-gray-700">Estimated Budget:</span>
                                <span class="font-bold text-lg text-blue-900" id="estimatedBudget">
                                    ₱0.00
                                </span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-white rounded border">
                                <span class="font-medium text-gray-700">Estimated Expenditures:</span>
                                <span class="font-bold text-lg text-green-900" id="estimatedExpenditures">
                                    ₱0.00
                                </span>
                            </div>

                            <!-- Balance -->
                            <div class="flex justify-between items-center p-3 bg-white rounded border" id="balanceContainer">
                                <span class="font-medium text-gray-700">Balance:</span>
                                <span class="font-bold text-lg" id="balanceAmount">
                                    ₱0.00
                                </span>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="mt-6 space-y-3">
                            <h5 class="font-medium text-amber-800">Expenditure Distribution</h5>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Student-Athletes:</span>
                                    <span id="athleteTotal">₱0.00</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Personnel:</span>
                                    <span id="personnelTotal">₱0.00</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Competitions:</span>
                                    <span id="competitionTotal">₱0.00</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Intramurals:</span>
                                    <span id="intramuralTotal">₱0.00</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Facilities:</span>
                                    <span id="facilityTotal">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="mt-6 bg-amber-900 hover:bg-amber-800 text-white px-6 py-3 rounded font-medium">
            Save Budget and Expenditure Information
        </button>
        <!-- Add this after the save button in Form E section -->
        <div class="mt-4 flex justify-end space-x-4">
            <a href="{{ route('reports.export-form', ['form' => 'E', 'format' => 'pdf']) }}" 
            class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
            <a href="{{ route('reports.export-form', ['form' => 'E', 'format' => 'xlsx']) }}" 
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>
    </form>
</div>

<!-- FORM F: Non-Varsity School-Based Sports Organizations -->
<div x-show="activeTab === 'formF'">
    <div class="bg-indigo-50 p-3 border rounded-t">
        <h3 class="font-semibold text-indigo-900">FORM F: Non-Varsity School-Based Sports Organizations</h3>
        <p class="text-sm text-indigo-700">Total Clubs: {{ $nonVarsityClubs->count() }}</p>
    </div>
    
    <!-- Instructions -->
    <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Report Information</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>This report is automatically generated from your existing sports, teams, athletes, coaches, and events data.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM F Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 bg-white text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">ITEM CODE</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">F1-CLUB-1</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">F1-CLUB-2</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">F1-CLUB-3</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">F2-MOD-1</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">F3-ACTIV-1</th>
                    <th class="border border-gray-300 px-2 py-1 text-left font-medium text-gray-700">F3-ACTIV-2</th>
                </tr>
                <tr>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Description</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Sports</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Sports (Sex)</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Sports Club Name</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Name of Designated Club Moderator</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Main Program/Activity</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-xs font-medium text-gray-600">Secondary Program/Activity</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <!-- Actual Data Rows -->
                @forelse($nonVarsityClubs as $index => $club)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $index + 1 }}</td>
                    
                    <!-- F1-CLUB-1: Sports -->
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $club->sport_name }}
                    </td>
                    
                    <!-- F1-CLUB-2: Sports (Sex) -->
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $club->sports_sex }}
                    </td>
                    
                    <!-- F1-CLUB-3: Sports Club Name -->
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $club->sports_club_name }}
                    </td>
                    
                    <!-- F2-MOD-1: Name of Designated Club Moderator -->
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $club->club_moderator }}
                    </td>
                    
                    <!-- F3-ACTIV-1: Main Program/Activity -->
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $club->main_program_activity }}
                    </td>
                    
                    <!-- F3-ACTIV-2: Secondary Program/Activity -->
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $club->secondary_program_activity }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="border border-gray-300 px-3 py-4 text-center text-sm text-gray-500">
                        No non-varsity sports clubs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Export Notice -->
    <div class="mt-4 bg-green-50 border border-green-200 rounded p-4">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-green-800 text-sm">Use the export buttons below to download this report in PDF or CSV format.</span>
        </div>
    </div>
    <!-- Add this after the table in Form F section -->
<div class="mt-4 flex justify-end space-x-4">
    <a href="{{ route('reports.export-form', ['form' => 'F', 'format' => 'pdf']) }}" 
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export PDF
    </a>
    <a href="{{ route('reports.export-form', ['form' => 'F', 'format' => 'xlsx']) }}" 
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export CSV
    </a>
</div>
</div>

<!-- FORM G: Feedback -->
<div x-show="activeTab === 'formG'">
    <div class="bg-purple-50 p-3 border rounded-t">
        <h3 class="font-semibold text-purple-900">FORM G: FEEDBACK</h3>
        <p class="text-sm text-purple-700">Help improve CHED Sports Development</p>
    </div>
    
    <!-- Thank You Message -->
    <div class="bg-blue-50 border border-blue-200 rounded p-6 mb-6">
        <div class="text-center">
            <svg class="h-12 w-12 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Thank you for participating!</h3>
            <p class="text-blue-700">Your insights are valuable for improving the CHED Sports Development program.</p>
        </div>
    </div>

    <!-- Feedback Form -->
    <form method="POST" action="{{ route('reports.save-feedback') }}" class="bg-white p-6 rounded border">
        @csrf
        
        <!-- Question 1 -->
        <div class="mb-8">
            <label class="block text-lg font-medium text-gray-900 mb-4">
                How can these templates be improved?
            </label>
            <textarea 
                name="template_improvements" 
                rows="4"
                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                placeholder="Please share your suggestions for improving the report templates..."
            >{{ old('template_improvements', $feedbackData['template_improvements'] ?? '') }}</textarea>
            @error('template_improvements')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question 2 -->
        <div class="mb-8">
            <label class="block text-lg font-medium text-gray-900 mb-4">
                What other important sports data should be captured/measured?
            </label>
            <textarea 
                name="additional_data" 
                rows="4"
                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                placeholder="What additional sports-related information would be valuable to track?"
            >{{ old('additional_data', $feedbackData['additional_data'] ?? '') }}</textarea>
            @error('additional_data')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question 3 -->
        <div class="mb-8">
            <label class="block text-lg font-medium text-gray-900 mb-4">
                What items were the most difficult to find data on?
            </label>
            <textarea 
                name="difficult_data" 
                rows="4"
                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                placeholder="Which data points were challenging to collect or locate?"
            >{{ old('difficult_data', $feedbackData['difficult_data'] ?? '') }}</textarea>
            @error('difficult_data')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Question 4 -->
        <div class="mb-8">
            <label class="block text-lg font-medium text-gray-900 mb-4">
                What items were the most easiest to find data on? (i.e. had readily available data)
            </label>
            <textarea 
                name="easy_data" 
                rows="4"
                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                placeholder="Which data points were readily available and easy to collect?"
            >{{ old('easy_data', $feedbackData['easy_data'] ?? '') }}</textarea>
            @error('easy_data')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Additional Comments -->
        <div class="mb-8">
            <label class="block text-lg font-medium text-gray-900 mb-4">
                Additional Comments or Suggestions
            </label>
            <textarea 
                name="additional_comments" 
                rows="3"
                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                placeholder="Any other feedback or suggestions you'd like to share..."
            >{{ old('additional_comments', $feedbackData['additional_comments'] ?? '') }}</textarea>
            @error('additional_comments')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Information (Optional) -->
        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-4">Optional Contact Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                    <input 
                        type="text" 
                        name="respondent_name"
                        value="{{ old('respondent_name', $feedbackData['respondent_name'] ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        placeholder="Optional"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        name="respondent_email"
                        value="{{ old('respondent_email', $feedbackData['respondent_email'] ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        placeholder="Optional"
                    >
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit" 
                class="bg-amber-900 hover:bg-amber-800 text-white px-8 py-3 rounded-lg font-medium text-lg transition duration-200"
            >
                Submit Feedback
            </button>
        </div>
    </form>

    <!-- Export Notice -->
    <div class="mt-6 bg-green-50 border border-green-200 rounded p-4">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-green-800 text-sm">Your feedback will be stored and can be exported for review.</span>
        </div>
    </div>
    <!-- Add this after the submit button in Form G section -->
    <div class="mt-4 flex justify-end space-x-4">
        <a href="{{ route('reports.export-form', ['form' => 'G', 'format' => 'pdf']) }}" 
        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export PDF
        </a>
        <a href="{{ route('reports.export-form', ['form' => 'G', 'format' => 'xlsx']) }}" 
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>
</div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
     class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
     class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg">
    {{ session('error') }}
</div>
@endif

@endsection

@section('scripts')
<script>
// Only run budget calculations if we're on Form E and the elements exist
function initializeBudgetCalculations() {
    const budgetInputs = document.querySelectorAll('.budget-input');
    const budgetForm = document.getElementById('budgetForm');
    
    // Only initialize if we're actually on Form E with budget inputs
    if (budgetInputs.length > 0 && budgetForm) {
        function calculateBudgetTotals() {
            // Calculate total fund sources
            const fundInputs = document.querySelectorAll('input[name^="fund_sources"]');
            let totalFunds = 0;
            
            fundInputs.forEach((input) => {
                const value = parseFloat(input.value) || 0;
                totalFunds += value;
            });
            
            // Calculate total expenditures
            const expInputs = document.querySelectorAll('input[name^="expenditures"]');
            let totalExpenditures = 0;
            
            expInputs.forEach((input) => {
                const value = parseFloat(input.value) || 0;
                totalExpenditures += value;
            });
            
            // Update display
            const fundDisplay = document.getElementById('totalFundSources');
            const expDisplay = document.getElementById('totalExpenditures');
            
            if (fundDisplay) {
                fundDisplay.textContent = '₱' + totalFunds.toLocaleString('en-US', { minimumFractionDigits: 2 });
            }
            
            if (expDisplay) {
                expDisplay.textContent = '₱' + totalExpenditures.toLocaleString('en-US', { minimumFractionDigits: 2 });
            }
            
            // Update summary section
            const estimatedBudget = document.getElementById('estimatedBudget');
            const estimatedExpenditures = document.getElementById('estimatedExpenditures');
            
            if (estimatedBudget) {
                estimatedBudget.textContent = '₱' + totalFunds.toLocaleString('en-US', { minimumFractionDigits: 2 });
            }
            if (estimatedExpenditures) {
                estimatedExpenditures.textContent = '₱' + totalExpenditures.toLocaleString('en-US', { minimumFractionDigits: 2 });
            }
            
            // Update balance
            const balance = totalFunds - totalExpenditures;
            const balanceElement = document.getElementById('balanceAmount');
            const balanceContainer = document.getElementById('balanceContainer');
            
            if (balanceElement && balanceContainer) {
                balanceElement.textContent = '₱' + Math.abs(balance).toLocaleString('en-US', { minimumFractionDigits: 2 }) + (balance >= 0 ? '' : ' (Deficit)');
                
                if (balance >= 0) {
                    balanceContainer.classList.remove('border-red-200');
                    balanceContainer.classList.add('border-green-200');
                    balanceElement.classList.remove('text-red-900');
                    balanceElement.classList.add('text-green-900');
                } else {
                    balanceContainer.classList.remove('border-green-200');
                    balanceContainer.classList.add('border-red-200');
                    balanceElement.classList.remove('text-green-900');
                    balanceElement.classList.add('text-red-900');
                }
            }
        }

        // Add event listeners to budget inputs
        budgetInputs.forEach(input => {
            input.addEventListener('input', calculateBudgetTotals);
        });
        
        // Initial calculation
        calculateBudgetTotals();
        
        // Set up observer for Form E tab switching
        const formE = document.querySelector('[x-show="activeTab === \'formE\'"]');
        if (formE) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        if (formE.style.display !== 'none') {
                            setTimeout(calculateBudgetTotals, 100);
                        }
                    }
                });
            });
            observer.observe(formE, { attributes: true });
        }
    }
}

// Only run benefits table code if the benefits table exists
function initializeBenefitsTable() {
    const benefitsTable = document.querySelector('#benefitsTable');
    
    if (benefitsTable) {
        benefitsTable.addEventListener('change', e => {
            if (!e.target.classList.contains('benefit-field')) return;
            const row = e.target.closest('tr');
            
            if (!row || !row.dataset.id) return;
            
            fetch("{{ route('reports.save-benefits') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    athlete_id: row.dataset.id,
                    field: e.target.name,
                    value: e.target.value
                })
            })
            .then(res => res.json())
            .then(data => console.log('Saved successfully:', data))
            .catch(err => console.error('Save failed:', err));
        });
    }
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeBudgetCalculations();
    initializeBenefitsTable();
});
</script>
@endsection