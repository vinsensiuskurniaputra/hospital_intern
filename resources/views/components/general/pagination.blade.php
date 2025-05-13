<div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
    <div class="text-sm text-gray-500">
        Menampilkan {{ $datas->firstItem() }} sampai {{ $datas->lastItem() }} dari
        {{ $datas->total() }}
        data
    </div>
    <div class="flex items-center gap-2">
        <!-- Previous Button -->
        @if ($datas->onFirstPage())
            <button class="px-3 py-1 text-sm text-gray-400 disabled:opacity-50" disabled>
                Sebelumnya
            </button>
        @else
            <a href="{{ $datas->previousPageUrl() }}"
                class="px-3 py-1 text-sm text-gray-500 hover:text-gray-600">
                Sebelumnya
            </a>
        @endif

        <!-- Pagination Numbers -->
        <div class="flex gap-2">
            {{-- Tombol First Page --}}
            @if ($datas->currentPage() > 2)
                <a href="{{ $datas->url(1) }}"
                    class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                    1
                </a>
                @if ($datas->currentPage() > 3)
                    <span class="px-3 py-1 text-sm text-gray-500">...</span>
                @endif
            @endif

            {{-- Loop Nomor Halaman (Menampilkan halaman di sekitar halaman aktif) --}}
            @for ($page = max(1, $datas->currentPage() - 1); $page <= min($datas->lastPage(), $datas->currentPage() + 1); $page++)
                @if ($page == $datas->currentPage())
                    <a href="{{ $datas->url($page) }}"
                        class="px-3 py-1 text-sm font-medium text-white bg-[#6B7126] rounded-lg shadow-md">
                        {{ $page }}
                    </a>
                @else
                    <a href="{{ $datas->url($page) }}"
                        class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Tombol Last Page --}}
            @if ($datas->currentPage() < $datas->lastPage() - 1)
                @if ($datas->currentPage() < $datas->lastPage() - 2)
                    <span class="px-3 py-1 text-sm text-gray-500">...</span>
                @endif
                <a href="{{ $datas->url($datas->lastPage()) }}"
                    class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                    {{ $datas->lastPage() }}
                </a>
            @endif
        </div>


        <!-- Next Button -->
        @if ($datas->hasMorePages())
            <a href="{{ $datas->nextPageUrl() }}"
                class="px-3 py-1 text-sm text-gray-500 hover:text-gray-600">
                Selanjutnya
            </a>
        @else
            <button class="px-3 py-1 text-sm text-gray-400 disabled:opacity-50" disabled>
                Selanjutnya
            </button>
        @endif
    </div>
</div>
