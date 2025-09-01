<ul class="flex flex-col gap-2 p-6">
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("index"),
        "icon" => "components.icons.home",
        "active" => in_array(Router->getCalledRouteName(), [ "index" ])
    ])
        {{ t("Home") }}
    @endcomponent
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("admin"),
        "icon" => "components.icons.dashboard",
        "active" => in_array(Router->getCalledRouteName(), [ "admin" ])
    ])
        {{ t("Dashboard") }}
    @endcomponent
</ul>
