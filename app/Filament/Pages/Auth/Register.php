<?php

namespace App\Filament\Pages\Auth;

use App\Models\Unit;
use App\Models\User;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected static ?string $title = 'Daftar Kontributor';

    public function getHeading(): string
    {
        return 'Daftar Kontributor';
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Nama Tampilan')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->alphaDash()
            ->maxLength(255)
            ->unique(User::class, 'username');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->maxLength(255)
            ->unique(User::class, 'email');
    }

    protected function getUnitFormComponent(): Component
    {
        return Select::make('unit_id')
            ->label('Unit Kerja')
            ->options(fn (): array => Unit::query()
                    ->where('is_active', true)
                    ->orderBy('type')
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
            ->searchable()
            ->preload();
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('Password');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->label('Konfirmasi Password');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getUsernameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getUnitFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['status'] = 'active';

        return $data;
    }

    protected function handleRegistration(array $data): Model
    {
        /** @var User $user */
        $user = parent::handleRegistration($data);

        $user->assignRole('Kontributor');

        return $user;
    }

    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return null;
    }
}
