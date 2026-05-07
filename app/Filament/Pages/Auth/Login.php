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
            ->label('NIM / Email')
            ->placeholder('Masukkan NIM atau Email')
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

    /**
     * Override to also try NIP if NIM login fails.
     */
    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If we tried NIM and it failed, try NIP
            $data = $this->form->getState();
            $loginField = $data['email'];

            if (preg_match('/^\d+$/', $loginField)) {
                $credentials = [
                    'nip' => $loginField,
                    'password' => $data['password'],
                ];

                $authGuard = \Filament\Facades\Filament::auth();

                if ($authGuard->attempt($credentials, $data['remember'] ?? false)) {
                    $user = $authGuard->user();

                    if ($user instanceof \Filament\Models\Contracts\FilamentUser &&
                        !$user->canAccessPanel(\Filament\Facades\Filament::getCurrentOrDefaultPanel())) {
                        $authGuard->logout();
                        throw $e;
                    }

                    session()->regenerate();
                    return app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class);
                }
            }

            throw $e;
        }
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
