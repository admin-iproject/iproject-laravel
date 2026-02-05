<!-- Right Edge Tabs (Module-Specific - Only show if module defines them) -->
@hasSection('right-tabs')
<div class="fixed right-0 top-1/2 transform -translate-y-1/2 z-40 flex flex-col space-y-2">
    @yield('right-tabs')
</div>
@endif

<!-- Right Slideout Overlay -->
<div id="slideout-overlay" class="slideout-overlay hidden opacity-0"></div>

<!-- Module-Specific Slideout Panels (Only show if module defines them) -->
@yield('slideout-panels')