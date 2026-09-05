<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();
        $this->generateCaptcha();
    }

    public function generateCaptcha(): void
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session([
            'admin_login_captcha_num1' => $num1,
            'admin_login_captcha_num2' => $num2,
            'admin_login_captcha_answer' => $num1 + $num2,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getCaptchaFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getCaptchaFormComponent(): Component
    {
        return TextInput::make('captcha')
            ->label(function (): Htmlable {
                $num1 = session('admin_login_captcha_num1');
                $num2 = session('admin_login_captcha_num2');

                if ($num1 === null || $num2 === null) {
                    $num1 = rand(1, 9);
                    $num2 = rand(1, 9);
                    session([
                        'admin_login_captcha_num1' => $num1,
                        'admin_login_captcha_num2' => $num2,
                        'admin_login_captcha_answer' => $num1 + $num2,
                    ]);
                }

                return new HtmlString("Verifikasi Keamanan: <strong style='color: #2563eb;'>{$num1} + {$num2} = ?</strong>");
            })
            ->placeholder('Tulis hasil hitungan')
            ->numeric()
            ->required()
            ->autocomplete('off')
            ->helperText('Hitung angka di atas untuk verifikasi keamanan (Captcha).');
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        $expected = session('admin_login_captcha_answer');
        $userInput = $data['captcha'] ?? null;

        if ($userInput === null || (int) $userInput !== (int) $expected) {
            $this->generateCaptcha();

            throw ValidationException::withMessages([
                'data.captcha' => 'Hasil perhitungan Captcha salah. Silakan coba kembali.',
            ]);
        }

        // Lanjutkan autentikasi bawaan Filament
        return parent::authenticate();
    }
}
