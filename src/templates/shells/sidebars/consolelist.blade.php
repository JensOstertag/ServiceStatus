<ul class="flex flex-col gap-2 p-4">
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("admin"),
        "icon" => "icons.dashboard",
        "active" => in_array(Router->getCalledRouteName(), [ "admin" ])
    ])
        {{ t("Dashboard") }}
    @endcomponent
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("services"),
        "icon" => "icons.service",
        "active" => in_array(Router->getCalledRouteName(), [ "services", "services-create", "services-edit" ])
    ])
        {{ t("Services") }}
    @endcomponent
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("notifications"),
        "icon" => "icons.notification",
        "active" => in_array(Router->getCalledRouteName(), [ "notifications" ])
    ])
        {{ t("Notifications") }}
    @endcomponent
    @component("components.layout.sidebars.sidebaritem", [
        "href" => Router->generate("settings"),
        "icon" => "icons.settings",
        "active" => in_array(Router->getCalledRouteName(), [ "settings" ])
    ])
        {{ t("Settings") }}
    @endcomponent
</ul>
