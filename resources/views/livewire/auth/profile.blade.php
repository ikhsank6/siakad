<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>User Profile</flux:breadcrumbs.item>
</x-slot>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-light text-zinc-900 dark:text-white">User Profile</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Central Hub for Personal Customization</p>
    </div>

    <!-- Personal Info Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Personal Info</h2>
        </div>

        <!-- Photo Row -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 w-32">Photo</span>
                <span class="text-sm text-zinc-600 dark:text-zinc-300">150×150px JPEG, PNG Image</span>
            </div>

            <!-- profile photo with delete option -->
            <div class="relative group">
                <label class="cursor-pointer">
                    @if($currentAvatar)
                        <img src="{{ Storage::url($currentAvatar) }}"
                            class="h-14 w-14 rounded-full object-cover ring-2 ring-transparent group-hover:ring-indigo-500 transition-all"
                            alt="Profile photo">
                    @else
                        <div
                            class="h-14 w-14 rounded-full bg-indigo-500 flex items-center justify-center text-lg font-bold text-white ring-2 ring-transparent group-hover:ring-indigo-400 transition-all">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif

                    <!-- Hover overlay -->
                    <div
                        class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>

                    <input type="file" wire:model="avatar" accept="image/*" class="hidden">

                    <!-- Loading indicator -->
                    <div wire:loading wire:target="avatar" class="absolute inset-0 flex items-center justify-center">
                        <div class="h-14 w-14 rounded-full bg-black/60 flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </label>

                <!-- Delete Icon (X) with Tooltip -->
                @if($currentAvatar)
                    <flux:tooltip content="Remove photo" position="top">
                        <button 
                            wire:click.prevent="deleteAvatar"
                            class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-0.5 shadow-sm transition-transform hover:scale-110"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </flux:tooltip>
                @endif
            </div>
        </div>
        @error('avatar')
            <div class="px-6 py-2 bg-red-50 dark:bg-red-900/20">
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror

        <!-- Name Row -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between" x-data="{ editing: false }">
            <div class="flex items-center gap-8 flex-1">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 w-32">Name</span>
                
                <!-- View Mode -->
                <template x-if="!editing">
                    <span class="text-sm text-zinc-900 dark:text-white">{{ $name }}</span>
                </template>
                
                <!-- Edit Mode -->
                <template x-if="editing">
                    <input type="text" wire:model="name" x-ref="nameInput"
                        class="flex-1 max-w-sm px-3 py-2 text-sm bg-zinc-100 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:border-indigo-500 dark:focus:border-indigo-500 text-zinc-900 dark:text-white focus:ring-0 transition-colors outline-none"
                        placeholder="Enter your name">
                </template>
            </div>
            
            <!-- Edit/Save Button -->
            <div class="flex items-center gap-2">
                <div wire:loading wire:target="updateName" class="text-indigo-500">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <!-- Edit Icon -->
                <button x-show="!editing" @click="editing = true; $nextTick(() => $refs.nameInput?.focus())" type="button"
                    class="p-2 text-zinc-400 hover:text-indigo-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>
                
                <!-- Save Icon -->
                <button x-show="editing" x-cloak @click="$wire.updateName(); editing = false" type="button"
                    class="p-2 text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        @error('name')
            <div class="px-6 py-2 bg-red-50 dark:bg-red-900/20">
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror

        <!-- Email Row -->
        <div class="px-6 py-5 flex items-center justify-between" x-data="{ editing: false }">
            <div class="flex items-center gap-8 flex-1">
                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 w-32">Email</span>
                
                <!-- View Mode -->
                <template x-if="!editing">
                    <span class="text-sm text-zinc-900 dark:text-white">{{ $email }}</span>
                </template>
                
                <!-- Edit Mode -->
                <template x-if="editing">
                    <input type="email" wire:model="email" x-ref="emailInput"
                        class="flex-1 max-w-sm px-3 py-2 text-sm bg-zinc-100 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:border-indigo-500 dark:focus:border-indigo-500 text-zinc-900 dark:text-white focus:ring-0 transition-colors outline-none"
                        placeholder="Enter your email">
                </template>
            </div>
            
            <!-- Edit/Save Button -->
            <div class="flex items-center gap-2">
                <div wire:loading wire:target="updateEmail" class="text-indigo-500">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <!-- Edit Icon -->
                <button x-show="!editing" @click="editing = true; $nextTick(() => $refs.emailInput?.focus())" type="button"
                    class="p-2 text-zinc-400 hover:text-indigo-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>
                
                <!-- Save Icon -->
                <button x-show="editing" x-cloak @click="$wire.updateEmail(); editing = false" type="button"
                    class="p-2 text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        @error('email')
            <div class="px-6 py-2 bg-red-50 dark:bg-red-900/20">
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror
    </div>

    <!-- Roles Configuration Card -->
    <div class="mt-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Roles Configuration</h2>
        </div>

        <div class="p-6">
            <!-- Set Default Role Section -->
            <div>
                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-4">Set Default Role (at Login)</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach(auth()->user()->roles as $r)
                        @php $isDefault = $r->pivot->is_default; @endphp
                        <button type="button" wire:click="setDefaultRole({{ $r->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $isDefault ? 'bg-amber-500 text-white shadow-lg' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 border border-dashed border-zinc-300 dark:border-zinc-600' }}">
                            <div class="flex items-center gap-2">
                                @if($isDefault)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                @endif
                                {{ $r->name }}
                            </div>
                        </button>
                    @endforeach
                </div>
                <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-400 italic">This role will be automatically selected the next time you login.</p>
            </div>
        </div>
    </div>
</div>