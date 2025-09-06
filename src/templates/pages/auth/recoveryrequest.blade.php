@component("shells.auth")
    <p class="mb-2">
        {{ t("Please enter your account's verified email address below. You will then receive an email with instructions about how to recover your password.") }}
    </p>

    <form method="post" action="{{ Router->generate("auth-recovery-request-action") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="email"
                   data-required>
                {{ t("Email") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="email"
                   name="email"
                   id="email"
                   placeholder="{{ t("Email") }}"
                   required>
        </div>

        <button class="{{ TailwindUtil::button(true) }} w-full mb-2">
            {{ t("Send instructions") }}
        </button>

        <a class="text-sm text-surface-500 hover:text-surface-600 transition-all"
           href="{{ Router->generate("auth-login") }}">
            {{ t("Log in instead?") }}
        </a>
    </form>
@endcomponent
