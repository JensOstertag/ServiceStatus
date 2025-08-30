@component("components.shells.landing")
    <div class="flex mb-8 items-center">
        <h1 class="w-full">
            {{ $service->getName() }}
        </h1>

        <div class="w-full h-64">
            <canvas id="uptimeChart" class="h-full"></canvas>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="mb-4">{{ t("HTTP monitoring") }}</h2>
        @include("components.monitoring.report.http", [
            "reportData" => $httpReports
        ])
    </div>

    <div class="">
        <h2 class="mb-4">{{ t("Ping monitoring") }}</h2>
        @include("components.monitoring.report.ping", [
            "reportData" => $pingReports
        ])
    </div>

    <script type="module">
        import * as UptimeReport from "{{ Router->staticFilePath("js/services/report.js") }}";
        UptimeReport.initUptime(
            {{ $uptime }},
        );
    </script>
@endcomponent
