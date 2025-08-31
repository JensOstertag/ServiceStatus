<div class="">
    <canvas id="pingReportChart" class="w-full h-48"></canvas>
</div>

<script type="module">
    import * as ServiceReport from "{{ Router->staticFilePath("js/services/report.js") }}";
    @php
        $monitoringSettings = null;
        $firstDate = null;
        $lastDate = null;
    @endphp
    ServiceReport.initPing(
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
    )
</script>
