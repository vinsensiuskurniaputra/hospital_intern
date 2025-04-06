<div x-show="{{ $show }}" class="fixed z-50 inset-0" x-cloak>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="{{ $show }} = false"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="{{ $show }}" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                <!-- Close Button -->
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button @click="{{ $show }} = false" class="text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#F5F7F0]">
                        <i class="bi bi-file-earmark-arrow-up text-xl text-[#637F26]"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Import {{ $title }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $description ?? 'Upload your CSV file to import data' }}
                    </p>
                </div>

                <!-- Upload Area -->
                <div x-data="{
                    fileName: null,
                    dragOver: false,
                    showErrors: false,
                    errors: [],
                    fileInput: null,
                    handleDrop(e) {
                        this.dragOver = false;
                        const file = e.dataTransfer.files[0];
                        this.validateAndSetFile(file);
                    },
                    validateAndSetFile(file) {
                        this.errors = [];
                        if (file) {
                            if (!file.name.endsWith('.csv')) {
                                this.errors.push('Please upload a CSV file');
                            }
                            if (file.size > 5 * 1024 * 1024) {
                                this.errors.push('File size should be less than 5MB');
                            }
                            if (this.errors.length === 0) {
                                this.fileName = file.name;
                                this.showErrors = false;
                
                                // Set file to hidden input
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                this.fileInput.files = dataTransfer.files;
                            } else {
                                this.showErrors = true;
                            }
                        }
                    }
                }">
                    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Drop Zone -->
                        <div class="relative" @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false"
                            @drop.prevent="handleDrop($event)">

                            <label
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-lg cursor-pointer"
                                :class="{
                                    'border-[#637F26] bg-[#F5F7F0]/50': dragOver,
                                    'border-gray-300 hover:border-[#637F26] hover:bg-gray-50': !dragOver && !fileName,
                                    'border-green-500 bg-green-50': fileName && !showErrors,
                                    'border-red-500 bg-red-50': showErrors
                                }">

                                <!-- File Selected State -->
                                <template x-if="fileName">
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-check text-3xl"
                                            :class="showErrors ? 'text-red-500' : 'text-green-500'"></i>
                                        <p class="mt-2 text-sm" x-text="fileName"
                                            :class="showErrors ? 'text-red-600' : 'text-green-600'"></p>
                                    </div>
                                </template>

                                <!-- Empty State -->
                                <template x-if="!fileName">
                                    <div class="text-center">
                                        <i class="bi bi-cloud-arrow-up text-3xl text-gray-400"></i>
                                        <p class="mt-2 text-sm text-gray-500">Drag and drop your CSV file here, or click
                                            to browse</p>
                                        <p class="mt-1 text-xs text-gray-400">Maximum file size: 5MB</p>
                                    </div>
                                </template>

                                <input type="file" name="file" class="hidden" accept=".csv" x-ref="fileInput"
                                    x-init="fileInput = $refs.fileInput">
                            </label>

                            <!-- Validation Errors -->
                            <div x-show="showErrors" class="mt-2">
                                <template x-for="error in errors" :key="error">
                                    <p class="text-sm text-red-600" x-text="error"></p>
                                </template>
                            </div>
                        </div>

                        <!-- Template Download -->
                        <div class="flex justify-center">
                            <a href="{{ $template_url ?? '#' }}" class="text-sm text-[#637F26] hover:text-[#85A832]">
                                <i class="bi bi-download mr-1"></i>
                                Download template file
                            </a>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                            <button type="button" @click="{{ $show }} = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832] disabled:opacity-50"
                                :disabled="!fileName || showErrors">
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
