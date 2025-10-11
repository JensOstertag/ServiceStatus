@component("shells.console", [
    "title" => t("Services")
])
    <h1 class="mb-2">
        @if(!empty($service))
            {{ t("Edit service \$\$name\$\$", ["name" => $service->getName()]) }}
        @else
            {{ t("Create service") }}
        @endif
    </h1>

    <form method="post" action="{{ Router->generate("services-save") }}" autocomplete="off">
        @if(!empty($service))
            <input type="hidden" name="service" value="{{ $service->getId() }}">
        @endif

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="name" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Service name") }}
            </label>
            <input id="name"
                   name="name"
                   type="text"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($service) ? $service->getName() : "" }}"
                   placeholder="{{ t("Service name") }}"
                   maxlength="256"
                   required>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="visible"
                   name="visible"
                   type="checkbox"
                   value="1"
                   class="{{ TailwindUtil::$checkbox }}"
                   @if(!empty($service) && $service->getVisible()) checked @endif>
            <label for="visible" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Visible") }}
            </label>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label for="order" class="{{ TailwindUtil::$inputLabel }}" data-required>
                {{ t("Order") }}
            </label>
            <input id="order"
                   name="order"
                   type="number"
                   step="1"
                   class="{{ TailwindUtil::$input }}"
                   value="{{ !empty($service) ? $service->getOrder() : "0" }}"
                   placeholder="{{ t("Order") }}"
                   required>
        </div>

        <div class="flex flex-col md:flex-row md:gap-4">
            @foreach(MonitoringType::cases() as $type)
                @include("ui.monitoring.edit." . strtolower($type->name), [
                    "monitoringSettings" => $monitoringSettings[$type->value] ?? null
                ])
            @endforeach
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("icons.buttonload")
            @include("icons.save")
            {{ t("Save") }}
        </button>

        @if(!empty($service))
            <button type="button"
                    id="delete-service"
                    class="{{ TailwindUtil::button(false, "danger") }} gap-2"
                    data-delete-href="{{ Router->generate("services-delete", ["service" => $service->getId()]) }}">
                @include("icons.buttonload")
                @include("icons.delete")
                {{ t("Delete") }}
            </button>
        @endif
    </form>

    @include("ui.modals.defaultabort")
    <script type="module">
        import * as ServicesEdit from "{{ Router->staticFilePath("js/services/edit.js") }}";
        ServicesEdit.init();
    </script>
@endcomponent
