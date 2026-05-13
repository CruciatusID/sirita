<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Support\AdminAccess;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    public ?string $targetStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('storyInstagram')
                ->label('Story IG')
                ->icon(Heroicon::OutlinedPhoto)
                ->url(fn (): string => route('admin.posts.story', $this->record), true)
                ->visible(fn (): bool => $this->record->status === 'published' && filled($this->record->slug)),
            Action::make('viewPortal')
                ->label('Lihat di Portal')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (): string => route('posts.show', $this->record), true)
                ->visible(fn (): bool => $this->record->status === 'published' && filled($this->record->slug)),
            DeleteAction::make(),
        ];
    }

    public function saveDraft(): void
    {
        $this->targetStatus = 'draft';

        $this->save();
    }

    public function saveChanges(): void
    {
        $this->targetStatus = (! AdminAccess::hasAnyRole(AdminAccess::EDITORIAL) && $this->record->status === 'published')
            ? 'review'
            : null;

        $this->save();
    }

    public function submitForReview(): void
    {
        $this->targetStatus = 'review';

        $this->save();
    }

    public function publish(): void
    {
        $this->targetStatus = AdminAccess::hasAnyRole(AdminAccess::EDITORIAL) ? 'published' : 'review';

        $this->save();
    }

    public function reject(): void
    {
        $this->targetStatus = AdminAccess::hasAnyRole(AdminAccess::EDITORIAL) ? 'rejected' : null;

        $this->save();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();
        $isEditorial = AdminAccess::hasAnyRole(AdminAccess::EDITORIAL);
        $status = $this->targetStatus ?? $this->record->status;

        if (! $isEditorial && ! in_array($status, ['draft', 'review'], true)) {
            $status = $this->record->status === 'published' ? 'review' : $this->record->status;
        }

        $data['status'] = $status;

        if ($status === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        if ($user && $isEditorial && (int) $this->record->user_id !== (int) $user->id) {
            $data['editor_user_id'] = $user->id;
        } else {
            $data['editor_user_id'] ??= $this->record->editor_user_id;
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return match ($this->targetStatus) {
            'draft' => 'Berita berhasil disimpan sebagai draft',
            'review' => 'Berita berhasil dikirim untuk review',
            'published' => 'Berita berhasil diterbitkan',
            'rejected' => 'Berita berhasil ditolak',
            default => 'Perubahan berita berhasil disimpan',
        };
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResourceUrl();
    }

    protected function getFormActions(): array
    {
        $isEditorial = AdminAccess::hasAnyRole(AdminAccess::EDITORIAL);
        $status = $this->record->status;

        if (! $isEditorial) {
            return [
                Action::make('saveDraft')
                    ->label('Simpan Draft')
                    ->action('saveDraft')
                    ->visible(fn (): bool => in_array($status, ['draft', 'rejected'], true))
                    ->keyBindings(['mod+s']),
                Action::make('saveChanges')
                    ->label($status === 'published' ? 'Kirim Perubahan untuk Review' : 'Simpan Perubahan')
                    ->action('saveChanges')
                    ->visible(fn (): bool => in_array($status, ['review', 'published'], true))
                    ->keyBindings(['mod+s']),
                Action::make('submitForReview')
                    ->label('Kirim untuk Review')
                    ->action('submitForReview')
                    ->color('warning')
                    ->visible(fn (): bool => in_array($status, ['draft', 'rejected'], true)),
                $this->getCancelFormAction(),
            ];
        }

        return [
            Action::make('saveChanges')
                ->label('Simpan Perubahan')
                ->action('saveChanges')
                ->keyBindings(['mod+s']),
            Action::make('submitForReview')
                ->label('Kirim ke Review')
                ->action('submitForReview')
                ->color('warning')
                ->visible(fn (): bool => $status !== 'review'),
            Action::make('publish')
                ->label('Terbitkan')
                ->action('publish')
                ->color('success')
                ->visible(fn (): bool => $status !== 'published'),
            Action::make('reject')
                ->label('Tolak')
                ->action('reject')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $status !== 'rejected'),
            Action::make('saveDraft')
                ->label('Kembalikan ke Draft')
                ->action('saveDraft')
                ->color('gray')
                ->visible(fn (): bool => $status !== 'draft'),
            $this->getCancelFormAction(),
        ];
    }
}
