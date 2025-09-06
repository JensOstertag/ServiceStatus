<div class="w-full md:w-1/2">
    <div class="{{ TailwindUtil::inputGroup() }} mb-2">
        <input id="monitoring-http-enabled"
               name="monitoringSettings[HTTP][enabled]"
               type="checkbox"
               value="1"
               class="{{ TailwindUtil::$checkbox }}"
               @if(!empty($monitoringSettings)) checked @endif>
        <label for="monitoring-http-enabled" class="{{ TailwindUtil::$inputLabel }}">
            {{ t("HTTP monitoring") }}
        </label>
    </div>
    <div class="w-full mb-2 hidden" id="monitoring-http-details">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="monitoring-http-endpoint" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Endpoint") }}
            </label>
            <input id="monitoring-http-endpoint"
                   name="monitoringSettings[HTTP][endpoint]"
                   type="text"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($monitoringSettings) ? $monitoringSettings->getEndpoint() : "" }}"
                   placeholder="{{ t("Endpoint") }}"
                   maxlength="256"
                   {{-- required added by script --}}>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="monitoring-http-expectedResponseCode" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Expected response code") }}
            </label>
            <input id="monitoring-http-expectedResponseCode"
                   name="monitoringSettings[HTTP][expectedResponseCode]"
                   type="number"
                   step="1"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($monitoringSettings) ? $monitoringSettings->getExpectationFromArray("expectedResponseCode", 200) : "200" }}"
                   placeholder="{{ t("Expected response code") }}"
                   {{-- required added by script --}}>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="monitoring-http-maxResponseTime" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Maximum response time") }}
            </label>
            <input id="monitoring-http-maxResponseTime"
                   name="monitoringSettings[HTTP][maxResponseTime]"
                   type="number"
                   step="1"
                   min="0"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($monitoringSettings) ? $monitoringSettings->getExpectationFromArray("maxResponseTime", "") : "200" }}"
                   placeholder="{{ t("Maximum response time") }}"
                   {{-- required added by script --}}>
        </div>
    </div>
</div>
