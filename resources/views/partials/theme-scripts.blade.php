{{-- Alpine Theme Store - for theme state management across Livewire navigations --}}
<script data-navigate-once>
    // Apply theme immediately on every navigation (before Alpine loads)
    (function () {
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.classList.toggle('dark', theme === 'dark');
    })();

    document.addEventListener('alpine:init', () => {
        Alpine.store('theme', {
            value: localStorage.getItem('theme') || 'light',

            init() {
                Alpine.effect(() => {
                    const isDark = this.value === 'dark';
                    document.documentElement.classList.toggle('dark', isDark);
                    localStorage.setItem('theme', this.value);
                });
            },

            get isDark() {
                return this.value === 'dark';
            },

            toggle() {
                this.value = this.isDark ? 'light' : 'dark';
            }
        });

        @if(isset($includeSidebarState) && $includeSidebarState)
            // Sidebar state store (only for app layout)
            Alpine.store('sidebarState', {
                open: true,
                toggle() {
                    this.open = !this.open;
                }
            });
        @endif
    });

    // Ensure stores exist and sync after Livewire navigation
    document.addEventListener('livewire:navigated', () => {
        const storedTheme = localStorage.getItem('theme') || 'light';

        if (!Alpine.store('theme')) {
            Alpine.store('theme', {
                value: storedTheme,
                get isDark() {
                    return this.value === 'dark';
                },
                toggle() {
                    this.value = this.isDark ? 'light' : 'dark';
                    const isDark = this.value === 'dark';
                    document.documentElement.classList.toggle('dark', isDark);
                    localStorage.setItem('theme', this.value);
                }
            });
        } else {
            // Sync store with localStorage
            if (Alpine.store('theme').value !== storedTheme) {
                Alpine.store('theme').value = storedTheme;
            }
        }

        @if(isset($includeSidebarState) && $includeSidebarState)
            if (!Alpine.store('sidebarState')) {
                Alpine.store('sidebarState', {
                    open: true,
                    toggle() {
                        this.open = !this.open;
                    }
                });
            }
        @endif

        // Always ensure DOM class is correct
        const isDark = storedTheme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);
    });
</script>