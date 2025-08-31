<?php

Router->addRoute("GET", "/", "index.php", "index");
Router->addRoute("GET|POST", "/404", "errors/400.php", "404");
Router->addRoute("GET|POST", "/400", "errors/400.php", "400");
Router->addRoute("GET|POST", "/500", "errors/500.php", "500");

Router->addRoute("POST", "/translations-api", "translations/api.php", "translations-api");

// Authentication
Router->addRoute("GET", "/auth/login", "auth/login.php", "auth-login");
Router->addRoute("POST", "/auth/login", "auth/login-action.php", "auth-login-action");
Router->addRoute("GET", "/auth/register", "auth/register.php", "auth-register");
Router->addRoute("POST", "/auth/register", "auth/register-action.php", "auth-register-action");
Router->addRoute("GET", "/auth/register/complete", "auth/register-complete.php", "auth-register-complete");
Router->addRoute("GET", "/auth/verify-email", "auth/verify-email.php", "auth-verify-email");
Router->addRoute("GET", "/auth/verify-email/complete", "auth/verify-email-complete.php", "auth-verify-email-complete");
Router->addRoute("GET", "/auth/password-recovery/request", "auth/recovery-request.php", "auth-recovery-request");
Router->addRoute("POST", "/auth/password-recovery/request", "auth/recovery-request-action.php", "auth-recovery-request-action");
Router->addRoute("GET", "/auth/password-recovery/request/complete", "auth/recovery-request-complete.php", "auth-recovery-request-complete");
Router->addRoute("GET", "/auth/password-recovery/reset", "/auth/recovery-reset.php", "auth-recovery-reset");
Router->addRoute("POST", "/auth/password-recovery/reset", "/auth/recovery-reset-action.php", "auth-recovery-reset-action");
Router->addRoute("GET", "/auth/password-recovery/reset/complete", "/auth/recovery-reset-complete.php", "auth-recovery-reset-complete");
Router->addRoute("GET", "/auth/logout", "auth/logout.php", "auth-logout");

Router->addRoute("GET", "/admin", "admin/index.php", "admin");

Router->addRoute("GET", "/admin/services", "admin/services/index.php", "services");
Router->addRoute("POST", "/admin/services/table", "admin/services/table.php", "services-table");
Router->addRoute("GET", "/admin/services/edit", "admin/services/edit.php", "services-create");
Router->addRoute("GET", "/admin/services/edit/{i:service}", "admin/services/edit.php", "services-edit");
Router->addRoute("POST", "/admin/services/save", "admin/services/save.php", "services-save");
Router->addRoute("GET", "/admin/services/delete/{i:service}", "admin/services/delete.php", "services-delete");

Router->addRoute("GET", "/admin/notifications", "admin/notifications/index.php", "notifications");

Router->addRoute("GET", "/service/{s:slug}", "services/index.php", "services-report");

// Error routes
Router->setError400Route(Router->generate("400"));
Router->setError404Route(Router->generate("404"));
