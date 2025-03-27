@foreach ($users as $user)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap w-full">
            <div class="flex items-center">
                <img class="h-8 w-8 rounded-full"
                    src="{{ $user->photo_profile_url ? asset('storage/' . $user->photo_profile_url) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                    alt="">
                <div class="ml-4 ">
                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $user->username }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $user->email }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <div class="flex flex-wrap gap-1">
                @foreach ($user->roles as $role)
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                        {{ $role->name }}
                    </span>
                @endforeach
            </div>
        </td>

        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.user_authorizations.edit', $user->id) }}"
                class="text-[#637F26] hover:text-[#85A832]">
                <i class="bi bi-pencil"></i>
            </a>
        </td>
    </tr>
@endforeach
