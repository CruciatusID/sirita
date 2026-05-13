<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Terverifikasi')
                    ->visible(fn ($get): bool => filled($get('email'))),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->same('password_confirmation'),
                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->revealable()
                    ->required(fn (Get $get): bool => filled($get('password')))
                    ->visible(fn (Get $get): bool => filled($get('password')))
                    ->dehydrated(false),
                Select::make('unit_id')
                    ->label('Unit Kerja')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ])
                    ->default('active')
                    ->required(),
                Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),
            ]);
    }
}
