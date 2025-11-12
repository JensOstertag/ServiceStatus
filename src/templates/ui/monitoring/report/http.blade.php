<div class="flex gap-0.5 md:gap-2 mb-1">
    @foreach($reportData as $date => $dayData)
        @php
            $dayStatus = \app\monitoring\ReportService::getHighestStatus($dayData)
        @endphp
        <div class="http-tooltip inline-block w-full h-12 rounded-full
            @if($dayStatus === null) bg-surface-500
            @elseif($dayStatus === \app\monitoring\ServiceStatus::OPERATIONAL) bg-safe-500
            @elseif($dayStatus === \app\monitoring\ServiceStatus::HIGH_RESPONSE_TIME) bg-warning-500
            @elseif($dayStatus === \app\monitoring\ServiceStatus::INTERNAL_ERROR || $dayStatus === \app\monitoring\ServiceStatus::NOT_RESPONDING) bg-danger-500
            @endif
        ">
            <div class="tooltip hidden">
                <div class="text-lg font-bold mb-4">
                    {{ $date }}
                </div>
                @php
                    $lastStatus = null;
                    $statusOngoingSince = null;
                    $previousMonitoringResult = null;
                @endphp
                @forelse($dayData as $monitoringResult)
                    @if($lastStatus === null)
                        @php
                            $lastStatus = $monitoringResult->getServiceStatusEnum();
                            $statusOngoingSince = $monitoringResult->getCreated()->format("Y-m-d H:i:s");
                            $previousMonitoringResult = $monitoringResult;
                        @endphp
                    @endif
                    @if($lastStatus !== $monitoringResult->getServiceStatusEnum())
                        <div class="mb-2 last:mb-0">
                            <div class="font-bold">
                                {{ t("\$\$from\$\$ until \$\$until\$\$", [
                                    "from" => $statusOngoingSince,
                                    "until" => $previousMonitoringResult->getCreated()->format("Y-m-d H:i:s")
                                ]) }}
                            </div>
                            <div>
                                {{ $lastStatus->getDescription() }}
                            </div>
                        </div>
                        @php
                            $lastStatus = $monitoringResult->getServiceStatusEnum();
                            $statusOngoingSince = $monitoringResult->getCreated()->format("Y-m-d H:i:s");
                        @endphp
                    @endif
                    @php
                        $previousMonitoringResult = $monitoringResult;
                    @endphp
                @empty
                    <div>
                        <div>
                            {{ t("No data is available for this day.") }}
                        </div>
                    </div>
                @endforelse
                @if($previousMonitoringResult !== null && $lastStatus !== null)
                    <div class="mb-2 last:mb-0">
                        <div class="font-bold">
                            {{ t("\$\$from\$\$ until \$\$until\$\$", [
                                "from" => $statusOngoingSince,
                                "until" => $previousMonitoringResult->getCreated()->format("Y-m-d H:i:s")
                            ]) }}
                        </div>
                        <div>
                            {{ $lastStatus->getDescription() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
<div class="flex justify-between w-full">
    <span class="text-sm text-surface-500">{{ t("\$\$count\$\$ days ago", ["count" => \app\monitoring\ReportService::$DAYS]) }}</span>
    <span class="text-sm text-surface-500">{{ t("Today") }}</span>
</div>

<div class="mt-4">
    <canvas id="httpReportChart" class="w-full h-48"></canvas>
</div>

<script type="module">
    import * as ServiceReport from "{{ Router->staticFilePath("js/services/report.js") }}";
    @php
        $monitoringSettings = null;
        $firstDate = null;
        $lastDate = null;
    @endphp
    ServiceReport.initHttp(
        [
            @foreach($reportData as $date => $dayData)
            @php
                if($firstDate === null) {
                    $firstDate = $date . " 00:00:00";
                }
                $lastDate = $date . " 00:00:00";
            @endphp
            @foreach($dayData as $data)
                @php
                    if($monitoringSettings === null) {
                        $monitoringSettings = $data->getMonitoringSettings();
                    }
                    $parsedCreatedDate = $data->getCreated()->format("Y-m-d H:i:s");
                    if($parsedCreatedDate > $lastDate) {
                        $lastDate = $parsedCreatedDate;
                    }
                @endphp

                {
                    timestamp: "{{ $data->getCreated()->format("Y-m-d H:i:s") }}",
                    responseTime: {{ $data->getResponseTime() ?? "null" }},
                },
                @endforeach
            @endforeach
        ],
        "{{ $firstDate }}",
        "{{ $lastDate }}"
    );
</script>
