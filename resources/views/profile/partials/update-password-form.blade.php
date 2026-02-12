<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a strong password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            
            <!-- Password Requirements -->
            <div class="mt-2 text-xs text-gray-600 space-y-1">
                <p class="font-medium">Password must contain:</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li id="req-length" class="text-gray-500">At least 10 characters</li>
                    <li id="req-uppercase" class="text-gray-500">One uppercase letter</li>
                    <li id="req-lowercase" class="text-gray-500">One lowercase letter</li>
                    <li id="req-number" class="text-gray-500">One number</li>
                    <li id="req-special" class="text-gray-500">One special character (!@#$%^&*)</li>
                </ul>
            </div>
            
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('update_password_password').addEventListener('input', function(e) {
            const password = e.target.value;
            
            // Check length (10 characters)
            const lengthReq = document.getElementById('req-length');
            if (password.length >= 10) {
                lengthReq.classList.remove('text-gray-500');
                lengthReq.classList.add('text-green-600', 'font-medium');
            } else {
                lengthReq.classList.remove('text-green-600', 'font-medium');
                lengthReq.classList.add('text-gray-500');
            }
            
            // Check uppercase
            const uppercaseReq = document.getElementById('req-uppercase');
            if (/[A-Z]/.test(password)) {
                uppercaseReq.classList.remove('text-gray-500');
                uppercaseReq.classList.add('text-green-600', 'font-medium');
            } else {
                uppercaseReq.classList.remove('text-green-600', 'font-medium');
                uppercaseReq.classList.add('text-gray-500');
            }
            
            // Check lowercase
            const lowercaseReq = document.getElementById('req-lowercase');
            if (/[a-z]/.test(password)) {
                lowercaseReq.classList.remove('text-gray-500');
                lowercaseReq.classList.add('text-green-600', 'font-medium');
            } else {
                lowercaseReq.classList.remove('text-green-600', 'font-medium');
                lowercaseReq.classList.add('text-gray-500');
            }
            
            // Check number
            const numberReq = document.getElementById('req-number');
            if (/[0-9]/.test(password)) {
                numberReq.classList.remove('text-gray-500');
                numberReq.classList.add('text-green-600', 'font-medium');
            } else {
                numberReq.classList.remove('text-green-600', 'font-medium');
                numberReq.classList.add('text-gray-500');
            }
            
            // Check special character
            const specialReq = document.getElementById('req-special');
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                specialReq.classList.remove('text-gray-500');
                specialReq.classList.add('text-green-600', 'font-medium');
            } else {
                specialReq.classList.remove('text-green-600', 'font-medium');
                specialReq.classList.add('text-gray-500');
            }
        });
    </script>
</section>
