<?php

namespace App\Filament\Support;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class SlugFields
{
    public static function source(TextInput $input): TextInput
    {
        return $input
            ->live(onBlur: true)
            ->afterStateUpdated(function (Get $get, Set $set, ?string $state, ?string $old): void {
                $currentSlug = $get('slug');
                $oldSlug = Str::slug($old ?? '');

                if (filled($currentSlug) && ($currentSlug !== $oldSlug)) {
                    return;
                }

                $set('slug', Str::slug($state ?? ''));
            });
    }

    public static function slug(): TextInput
    {
        return TextInput::make('slug')
            ->label('Slug')
            ->required()
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->dehydrateStateUsing(fn (?string $state): string => Str::slug($state ?? ''));
    }
}
