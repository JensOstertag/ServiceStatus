@component("components.shells.auth")
    <p class="mb-2">
        {{ t("Already have an account?") }}
        <a class="text-primary-500 hover:text-primary-600 transition-all"
           href="{{ Router->generate("auth-login") }}">
            {{ t("Click here to log in.") }}
        </a>
    </p>

    <form method="post" action="{{ Router->generate("auth-register-action") }}">
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
                   required
                   @if(isset($email)) value="{{ $email }}" @endif>
        </div>

        <div class="{{ TailwindUtil::inputGroup() }} mb-2">
            <label class="{{ TailwindUtil::$inputLabel }}"
                   for="username"
                   data-required>
                {{ t("Username") }}
            </label>
            <input class="{{ TailwindUtil::$input }}"
                   type="text"
                   name="username"
                   id="username"
                   placeholder="{{ t("Username") }}"
                   required
                   pattern="(?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,15}"
                   @if(isset($username)) value="{{ $username }}" @endif>
        </div>

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
            {{ t("Register") }}
        </button>
    </form>

    <p class="text-sm text-surface-500">
        {{ t("Upon registration, you will receive an email with a link. Please open that link to verify your email address and to get access to your account.") }}
    </p>

    <script type="module">
        import * as PasswordStrength from "{{ Router->staticFilePath("js/auth/PasswordStrength.js") }}";
        PasswordStrength.init("password");
    </script>
@endcomponent
