<?php

return [

    'title' => 'Masuk',

    'heading' => 'Masuk',

    'actions' => [

        'register' => [
            'before' => 'atau',
            'label' => 'daftar akun baru',
        ],

        'request_password_reset' => [
            'label' => 'Lupa kata sandi?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Alamat Email',
        ],

        'password' => [
            'label' => 'Kata Sandi',
        ],

        'remember' => [
            'label' => 'Ingat saya',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Masuk',
            ],

        ],

    ],

    'multi_factor' => [

        'heading' => 'Verifikasi identitas Anda',

        'subheading' => 'Untuk melanjutkan masuk, Anda perlu memverifikasi identitas Anda.',

        'form' => [

            'provider' => [
                'label' => 'Bagaimana Anda ingin memverifikasi?',
            ],

            'actions' => [

                'authenticate' => [
                    'label' => 'Konfirmasi masuk',
                ],

            ],

        ],

    ],

    'messages' => [

        'failed' => 'Email atau kata sandi salah, atau akun belum terdaftar.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Terlalu banyak percobaan masuk',
            'body' => 'Silakan coba lagi dalam :seconds detik.',
        ],

    ],

];
