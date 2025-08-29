@component("components.shells.console")
    <h1>
        {{ t("Services") }}
    </h1>

    <a id="create-service"
       href="{{ Router->generate("services-create") }}"
       class="{{ TailwindUtil::button() }} gap-2">
        @include("components.icons.plus")
        {{ t("Create service") }}
    </a>

    <div class="overflow-x-auto">
        <table id="services-table" class="stripe" data-table-ajax="{{ Router->generate("services-table") }}">
            <thead>
                <tr>
                    <th>{{ t("Service name") }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contents filled by services/overview.js --}}
            </tbody>
        </table>
    </div>

    <script type="module">
        import * as ServicesOverview from "{{ Router->staticFilePath("js/services/overview.js") }}";
        ServicesOverview.init({
            "Search...": "{{ t("Search...") }}",
            "Loading...": "{{ t("Loading...") }}",
            "No entries": "{{ t("No entries") }}",
            "Back": "{{ t("Back") }}",
            "Next": "{{ t("Next") }}"
        });
    </script>
@endcomponent
