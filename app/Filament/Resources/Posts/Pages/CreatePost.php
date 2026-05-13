<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\AdminAccess;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    public ?string $targetStatus = 'draft';

    public function createDraft(): void
    {
        $this->targetStatus = 'draft';

        $this->create();
    }

    public function submitForReview(): void
    {
        $this->targetStatus = 'review';

        $this->create();
    }

    public function publish(): void
    {
        $this->targetStatus = AdminAccess::hasAnyRole(AdminAccess::EDITORIAL) ? 'published' : 'review';

        $this->create();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return match ($this->targetStatus) {
            'review' => 'Berita berhasil dikirim untuk review',
            'published' => 'Berita berhasil diterbitkan',
            default => 'Draft berita berhasil disimpan',
        };
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $status = $this->targetStatus ?? 'draft';

        if (! AdminAccess::hasAnyRole(AdminAccess::EDITORIAL) && ! in_array($status, ['draft', 'review'], true)) {
            $status = 'draft';
        }

        $data['status'] = $status;

        if ($status === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('createDraft')
                ->label('Simpan Draft')
                ->action('createDraft')
                ->keyBindings(['mod+s']),
            Action::make('submitForReview')
                ->label('Kirim untuk Review')
                ->action('submitForReview')
                ->color('warning'),
            Action::make('publish')
                ->label('Terbitkan')
                ->action('publish')
                ->color('success')
                ->visible(fn (): bool => AdminAccess::hasAnyRole(AdminAccess::EDITORIAL)),
            $this->getCancelFormAction(),
        ];
    }
}
