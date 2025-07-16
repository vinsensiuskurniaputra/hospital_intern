@extends('layouts.auth')

@section('title', 'Class Year Management')

@section('content')
    <div x-data="{ addModal: false }">
        @include('components.general.notification')

        <div class="p-6 space-y-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h1 class="text-2xl text-gray-800 pb-6">Class Year</h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col lg:items-center lg:justify-between gap-4">
                        <div class="flex flex-col lg:flex-row w-full justify-between gap-3">
                            <div class="w-full lg:w-[360px]">
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Search class year..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <button @click="addModal = true"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                <i class="bi bi-plus-lg mr-2"></i>Add Class Year
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class Year</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($classYears as $i => $classYear)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 w-12 whitespace-nowrap text-sm text-gray-900">{{ $i + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $classYear->class_year }}
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2 text-center whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.classYears.edit', $classYear->id) }}"
                                            class="text-[#637F26] hover:text-[#85A832] ml-auto">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.classYears.destroy', $classYear->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this Class Year?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('pages.admin.class_year.add', [
                    'show' => 'addModal',
                ])

                @include('components.general.pagination', ['datas' => $classYears])
            </div>
        </div>
    </div>
@endsection
