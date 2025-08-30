<div class="flex gap-1 md:gap-2">
    @foreach($reportData as $dayData)
        @php
            $dayStatus = ReportService::getHighestStatus($dayData)
        @endphp
        <div class="inline-block w-full h-8 rounded-full
            @if($dayStatus === null) bg-surface-500
            @elseif($dayStatus === ServiceStatus::OPERATIONAL) bg-safe-500
            @elseif($dayStatus === ServiceStatus::HIGH_RESPONSE_TIME) bg-warning-500
            @elseif($dayStatus === ServiceStatus::INTERNAL_ERROR || $dayStatus === ServiceStatus::NOT_RESPONDING) bg-danger-500
            @endif
        "></div>
    @endforeach
</div>
