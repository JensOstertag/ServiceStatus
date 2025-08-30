<div class="flex gap-1 md:gap-2 mb-1">
    @foreach($incidentData as $dayData)
        @php
            $dayStatus = ReportService::getHighestStatus($dayData)
        @endphp
        <div class="incident-tooltip inline-block w-full h-12 rounded-full
            @if($dayStatus === null) bg-surface-500
            @elseif($dayStatus === ServiceStatus::OPERATIONAL) bg-safe-500
            @elseif($dayStatus === ServiceStatus::HIGH_RESPONSE_TIME) bg-warning-500
            @elseif($dayStatus === ServiceStatus::INTERNAL_ERROR || $dayStatus === ServiceStatus::NOT_RESPONDING) bg-danger-500
            @endif
        ">
            <div class="tooltip hidden">
                @foreach($dayData as $incident)
                    <div class="mb-2 last:mb-0">
                        <div class="font-bold">
                            @if($incident->getUntil() === null)
                                {{ t("Ongoing since \$\$from\$\$", [
                                    "from" => $incident->getFrom()->format("Y-m-d H:i:s")
                                ]) }}
                            @else
                                {{ t("\$\$from\$\$ until \$\$until\$\$", [
                                    "from" => $incident->getFrom()->format("Y-m-d H:i:s"),
                                    "until" => $incident->getUntil()->format("Y-m-d H:i:s")
                                ]) }}
                            @endif
                        </div>
                        <div>
                            {{ $incident->getServiceStatusEnum()->getDescription() }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
<div class="flex justify-between w-full">
    <span class="text-sm text-surface-500">{{ t("\$\$count\$\$ days ago", ["count" => ReportService::$DAYS]) }}</span>
    <span class="text-sm text-surface-500">{{ t("Today") }}</span>
</div>
