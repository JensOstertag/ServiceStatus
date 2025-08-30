<div class="">
    <canvas id="pingReportChart" class="w-full h-48"></canvas>
</div>

<script type="module">
    import * as ServiceReport from "{{ Router->staticFilePath("js/services/report.js") }}";
    ServiceReport.initPing([
        @foreach($reportData as $dayData)
            @foreach($dayData as $data)
                {
                    timestamp: "{{ $data->getCreated()->format("Y-m-d H:i:s") }}",
                    responseTime: {{ $data->getResponseTime() ?? "null" }},
                },
            @endforeach
        @endforeach
    ])
</script>
