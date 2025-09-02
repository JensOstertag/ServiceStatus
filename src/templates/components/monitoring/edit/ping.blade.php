<div class="w-full md:w-1/2">
    <div class="{{ TailwindUtil::inputGroup() }} mb-2">
        <input id="monitoring-ping-enabled"
               name="monitoringSettings[PING][enabled]"
               type="checkbox"
               value="1"
               class="{{ TailwindUtil::$checkbox }}"
               @if(!empty($monitoringSettings)) checked @endif>
        <label for="monitoring-ping-enabled" class="{{ TailwindUtil::$inputLabel }}">
            {{ t("Ping monitoring") }}
        </label>
    </div>

    <div class="w-full mb-2 hidden" id="monitoring-ping-details">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="monitoring-http-endpoint" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Endpoint") }}
            </label>
            <input id="monitoring-http-endpoint"
                   name="monitoringSettings[PING][endpoint]"
                   type="text"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($monitoringSettings) ? $monitoringSettings->getEndpoint() : "" }}"
                   placeholder="{{ t("Endpoint") }}"
                   maxlength="256"
                   {{-- required added by script --}}>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="monitoring-http-maxResponseTime" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Maximum response time") }}
            </label>
            <input id="monitoring-http-maxResponseTime"
                   name="monitoringSettings[PING][maxResponseTime]"
                   type="number"
                   step="1"
                   min="0"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($monitoringSettings) ? $monitoringSettings->getExpectationFromArray("maxResponseTime", "") : "50" }}"
                   placeholder="{{ t("Maximum response time") }}"
                   {{-- required added by script --}}>
        </div>
    </div>
</div>
