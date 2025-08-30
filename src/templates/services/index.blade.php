@component("components.shells.landing")
    <h1>
        {{ $service->getName() }}
    </h1>

    @include("components.monitoring.report.http", [
        "reportData" => $httpReports
    ])

    @include("components.monitoring.report.ping", [
        "reportData" => $pingReports
    ])
@endcomponent
