@component("components.shells.console", [
    "title" => t("System settings")
])
    <h1 class="mb-2">
        {{ t("System settings") }}
    </h1>

    <form method="post" action="{{ Router->generate("settings-save") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <input id="registrationEnabled"
                   name="registrationEnabled"
                   type="checkbox"
                   class="{{ TailwindUtil::$checkbox }}"
                   value="1"
                   @if($registrationEnabled) checked @endif>
            <label for="registrationEnabled" class="{{ TailwindUtil::$inputLabel }}">
                {{ t("Registration enabled") }}
            </label>
        </div>

        <button type="submit" class="{{ TailwindUtil::button() }} gap-2">
            @include("components.icons.buttonload")
            @include("components.icons.save")
            {{ t("Save") }}
        </button>
    </form>
@endcomponent