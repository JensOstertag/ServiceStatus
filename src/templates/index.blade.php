@component("components.shells.landing")
    @foreach($services as $service)
        <div class="mb-8">
            <div class="flex justify-between items-center gap-2">
                <h2 class="mb-4 truncate">
                    {{ $service->getName() }}
                </h2>

                <div class="mb-4 px-2 py-1 rounded-full text-sm truncate
                    @switch($currentStatuses[$service->getId()])
                        @case(ServiceStatus::OPERATIONAL) bg-safe-500 text-safe-900 @break
                        @case(ServiceStatus::HIGH_RESPONSE_TIME) bg-warning-500 text-warning-900 @break
                        @case(ServiceStatus::INTERNAL_ERROR) bg-danger-500 text-danger-900 @break
                        @case(ServiceStatus::NOT_RESPONDING) bg-danger-500 text-danger-900 @break
                        @default bg-surface-500 text-surface-900
                    @endswitch">
                    {{ $currentStatuses[$service->getId()]->getLabelText() }}
                </div>
            </div>

            @include("components.monitoring.report.incidents", [
                "incidentData" => $incidents[$service->getId()] ?? [],
            ])

            <div class="flex justify-end w-full mt-1">
                <a href="{{ Router->generate("services-report", ["slug" => $service->getSlug()]) }}"
                   class="px-2 py-1 rounded-full text-sm truncate bg-primary-500 text-primary-900">
                    {{ t("Monitoring insights") }}
                    @include("components.icons.right", ["class" => "inline h-5 w-5"])
                </a>
            </div>
        </div>
    @endforeach

    <script type="module">
        import * as ServiceReport from "{{ Router->staticFilePath("js/services/report.js") }}";
        ServiceReport.initIncidents();
    </script>
@endcomponent
