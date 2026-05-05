<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Support\AdminAccess;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();

        if ($user && $user->hasAnyRole(AdminAccess::EDITORIAL) && (int) $this->record->user_id !== (int) $user->id) {
            $data['editor_user_id'] = $user->id;
        } else {
            $data['editor_user_id'] ??= $this->record->editor_user_id;
        }

        return $data;
    }
}
