<x-guest-layout>
    <form id="registerForm" novalidate>
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <div id="error-name" class="mt-2 text-red-600 text-sm"></div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <div id="error-email" class="mt-2 text-red-600 text-sm"></div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <div id="error-password" class="mt-2 text-red-600 text-sm"></div>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <div id="error-password_confirmation" class="mt-2 text-red-600 text-sm"></div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button type="submit" class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Limpiar mensajes de error
            ['name', 'email', 'password', 'password_confirmation'].forEach(field => {
                document.getElementById('error-' + field).innerText = '';
            });

            const form = e.target;
            const data = new FormData(form);
            const formData = Object.fromEntries(data.entries());

            try {
                const response = await fetch('{{ env("API_URL") }}/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok) {
    window.location.href = "/properties"; // redirigir aquí en vez de a 'home'
} else if (response.status === 422) {
                    // Validación fallida: mostrar errores
                    const errors = result.errors || {};
                    for (const field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            document.getElementById('error-' + field).innerText = errors[field].join(', ');
                        }
                    }
                } else {
                    alert('Error: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
    </script>
</x-guest-layout>
