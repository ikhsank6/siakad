{{-- Immediate theme application to prevent FOUC (Flash of Unstyled Content) --}}
<script>
    (function () {
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') document.documentElement.classList.add('dark');
    })();
</script>