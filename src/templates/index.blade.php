@component("components.shells.landing")
    @foreach($services as $service)
        <div class="mb-8">
            <h2 class="mb-4">
                {{ $service->getName() }}
            </h2>

            @include("components.monitoring.report.incidents", [
                "incidentData" => $incidents[$service->getId()] ?? [],
            ])
        </div>
    @endforeach

    <script type="module">
        import * as ServiceReport from "{{ Router->staticFilePath("js/services/report.js") }}";
        ServiceReport.initIncidents();
    </script>
@endcomponent
