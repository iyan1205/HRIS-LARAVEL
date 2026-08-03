<?php

// Plafon maksimal nominal per level jabatan & jenis benefit.
// Key level HARUS sama persis dengan enum di tabel jabatans (case-sensitive).

return [
    'Direktur' => [
        'Kacamata' => 1_000_000,
        'Vitamin'         => 500_000,
        'MCU'         => 1_500_000,
    ],
    'Manajer' => [
        'Kacamata' => 1_000_000,
        'Vitamin'         => 500_000, 
        'MCU'         => 1_500_000,
    ],
    'SPV' => [
        'Kacamata' => 1_000_000,
        'Vitamin'     => 250_000,
        'MCU'         => 750_000,
    ],
    'Kains' => [
        'Kacamata' => 1_000_000,
        'Vitamin'         => 250_000,
        'MCU'         => 750_000,
    ],
    'Koordinator' => [
        'Kacamata' => 1_000_000,
        'Vitamin'         => 250_000,
        'MCU'         => 750_000,
    ],
    'Staff' => [
        'Kacamata' => 1_000_000,
        'Vitamin'    => 125_000,
        'MCU'        => 375_000,
    ],
];