<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\AdminAccess;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berita berhasil ditambahkan';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! AdminAccess::hasAnyRole(AdminAccess::EDITORIAL) && ! in_array($data['status'] ?? null, ['draft', 'review'], true)) {
            $data['status'] = 'draft';
        }

        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl();
    }

}
