<div class="fixed top-0 w-full h-16 bg-surface-200">
    <header class="flex justify-between items-center h-full px-4 shadow">
        <div class="flex items-center">
            {{-- Open sidebar button --}}
            <button class="cursor-pointer sidebar-toggle">
                @include("icons.menu", [ "class" => "w-6 h-6" ])
            </button>

            {{-- Logo --}}
            <a href="{{ Config->getAppUrl() }}" class="ml-2 font-bold">
                {{ Config->getAppName() }}
            </a>
        </div>

        <div class="">
            {{-- Top Right Elements --}}
        </div>
    </header>
</div>

@component("components.layout.sidebars.console")
    {{-- Sidebar navigation list --}}
    <nav>
        @include("components.layout.sidebars.consolelist")
    </nav>

    {{-- Account info --}}
    @include("components.layout.sidebars.accountinfo")
@endcomponent
