<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Pilih cabang untuk mulai bertransaksi.') }}
    </div>

    <form method="POST" action="{{ route('branch.set') }}">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach($branches as $branch)
                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none dark:bg-gray-800">
                    <input type="radio" name="branch_id" value="{{ $branch->id }}" class="sr-only" onchange="this.form.submit()">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">{{ $branch->name }}</span>
                            <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                {{ $branch->vendor->name }}
                            </span>
                            <span class="mt-6 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $branch->code }}</span>
                        </span>
                    </span>
                    <!-- Checked icon placeholder -->
                    <div class="absolute -top-px -right-px h-6 w-6 rounded-tr-lg rounded-bl-lg border-t border-r border-indigo-500 bg-indigo-500 text-white flex items-center justify-center opacity-0 check-icon">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="mt-4 flex items-center justify-end">
            <x-primary-button>
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>

    <style>
        input:checked + span + .check-icon {
            opacity: 1;
        }
        input:checked + span {
            border-color: #6366f1;
            ring: 2px;
            ring-color: #6366f1;
        }
    </style>
</x-guest-layout>
