@component("shells.landing", [
    "title" => $service->getName()
])
    <div class="flex flex-col sm:flex-row mb-8 items-center">
        <div class="w-full mb-4">
            <h1 class="mb-1 truncate">
                {{ $service->getName() }}
            </h1>

            <div class="mb-4 px-2 py-1 rounded-full text-sm truncate w-fit
                 @switch($currentStatus)
                     @case(\app\monitoring\ServiceStatus::OPERATIONAL) bg-safe-500 text-safe-900 @break
                     @case(\app\monitoring\ServiceStatus::HIGH_RESPONSE_TIME) bg-warning-500 text-warning-900 @break
                     @case(\app\monitoring\ServiceStatus::INTERNAL_ERROR) bg-danger-500 text-danger-900 @break
                     @case(\app\monitoring\ServiceStatus::NOT_RESPONDING) bg-danger-500 text-danger-900 @break
                     @default bg-surface-500 text-surface-900
                 @endswitch">
                {{ $currentStatus->getLabelText() }}
            </div>
        </div>


        <div class="h-64">
            <canvas id="uptimeChart" class="h-full"></canvas>
        </div>
    </div>

    @foreach(\app\monitoring\MonitoringType::cases() as $type)
        @if(in_array($type, $enabledMonitoringTypes))
            <div class="mb-8">
                <h2 class="mb-4">{{ $type->getName() }}</h2>
                @include("ui.monitoring.report." . strtolower($type->name), [
                    "reportData" => $reports[$type->value] ?? [],
                ])
            </div>
        @endif
    @endforeach

    <script type="module">
        import * as UptimeReport from "{{ Router->staticFilePath("js/services/report.js") }}";
        UptimeReport.initUptime(
            {{ $uptime }}
        );
    </script>
@endcomponent
