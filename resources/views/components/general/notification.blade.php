<div class="fixed top-20 right-4 z-50 w-96 space-y-4">
    <!-- Success Message -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg">
            <div class="flex-shrink-0">
                <i class="bi bi-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-green-500 hover:text-green-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error') || $errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg">
            <div class="flex-shrink-0">
                <i class="bi bi-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">
                    {{ session('error') ?? 'Ada yang salah dalam input Anda!' }}
                </p>
            </div>
            <button @click="show = false" class="ml-auto text-red-500 hover:text-red-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif
</div>