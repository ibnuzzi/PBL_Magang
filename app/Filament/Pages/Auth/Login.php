<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use SensitiveParameter;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    protected static string $layout = 'filament.pages.auth.login-layout';

    /**
     * Override the email field to accept NIM/NIP/Email.
     */
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('NIM / NIP / Email')
            ->placeholder('Masukkan NIM, NIP, atau Email')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * Override the password field with custom hint for "Lupa Password?".
     */
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    /**
     * Support login via email, NIM, or NIP.
     */
    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $loginField = $data['email'];
        $credentials = ['password' => $data['password']];

        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginField;
        } elseif (preg_match('/^\d+$/', $loginField)) {
            // Numeric input - could be NIM or NIP
            $credentials['nim'] = $loginField;
        } else {
            $credentials['email'] = $loginField;
        }

        return $credentials;
    }

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (\DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return null;
        }

        $data = $this->form->getState();
        $loginField = $data['email'];
        $password = $data['password'];

        $field = 'email';
        
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (preg_match('/^\d+$/', $loginField)) {
            $field = 'nim'; // Default to checking NIM first
        } else {
            $field = 'email';
        }

        // Check if user exists in database first
        $userQuery = \App\Models\User::where($field, $loginField);
        
        // If it was numeric, it could be NIP if NIM not found
        if ($field === 'nim' && !$userQuery->exists()) {
            $userQuery = \App\Models\User::where('nip', $loginField);
            if ($userQuery->exists()) {
                $field = 'nip';
            }
        }

        if (!$userQuery->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'data.email' => 'Akun belum terdaftar di dalam sistem.',
            ]);
        }

        $credentials = [
            $field => $loginField,
            'password' => $password,
        ];

        $authGuard = \Filament\Facades\Filament::auth();

        // Attempt login
        if (! $authGuard->attempt($credentials, $data['remember'] ?? false)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'data.email' => 'Kata sandi yang Anda berikan salah.',
            ]);
        }

        $user = $authGuard->user();

        if (
            $user instanceof \Filament\Models\Contracts\FilamentUser &&
            ! $user->canAccessPanel(\Filament\Facades\Filament::getCurrentOrDefaultPanel())
        ) {
            $authGuard->logout();
            throw \Illuminate\Validation\ValidationException::withMessages([
                'data.email' => 'Anda tidak memiliki akses ke panel ini.',
            ]);
        }

        session()->regenerate();
        
        return app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class);
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Masuk — SiMagang JTI';
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Selamat Datang Kembali';
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Masuk ke akun Anda untuk mengelola kegiatan magang.';
    }

    protected function getLayoutData(): array
    {
        return [
            ...parent::getLayoutData(),
            'maxWidth' => '100%',
        ];
    }
}
