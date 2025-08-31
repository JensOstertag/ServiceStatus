@component("components.shells.auth")
    <p class="mb-2">
        {{ t("Please enter your new password.") }}
    </p>

    <form method="post" action="{{ Router->generate("auth-recovery-reset-action") }}">
        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="password"
                   data-required>
                {{ t("Password") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="password"
                   id="password"
                   placeholder="{{ t("Password") }}"
                   required
                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}">
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="password-repeat"
                   data-required>
                {{ t("Password (repeat)") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="password"
                   name="password-repeat"
                   id="password-repeat"
                   placeholder="{{ t("Password (repeat)") }}"
                   required
                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}">
        </div>

        @include("components.auth.passwordstrength")

        <button class="{{ TailwindUtil::button(true) }} w-full">
            {{ t("Change password") }}
        </button>
    </form>

    <script type="module">
        import * as PasswordStrength from "{{ Router->staticFilePath("js/auth/PasswordStrength.js") }}";
        PasswordStrength.init("password");
    </script>
@endcomponent
