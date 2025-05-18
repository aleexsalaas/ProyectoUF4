'api' => [
    \Illuminate\Http\Middleware\HandleCors::class, // <--- asegurarte que esté
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
