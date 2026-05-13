<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\AdminAccess;
use App\Models\Post;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContributorPostStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected ?string $heading = 'Ringkasan Berita Saya';

    protected ?string $description = 'Pantau naskah yang masih disusun, menunggu review, dan sudah diproses.';

    public static function canView(): bool
    {
        return AdminAccess::hasAnyRole(['Kontributor'])
            && ! AdminAccess::hasAnyRole(AdminAccess::EDITORIAL);
    }

    protected function getStats(): array
    {
        $counts = Post::query()
            ->where('user_id', auth()->id())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            Stat::make('Draft', number_format($counts['draft'] ?? 0))
                ->description('Belum dikirim ke editor')
                ->descriptionColor(($counts['draft'] ?? 0) > 0 ? 'warning' : 'gray')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('warning')
                ->url(PostResource::getUrl('index', ['activeTab' => 'draft'])),
            Stat::make('Menunggu Review', number_format($counts['review'] ?? 0))
                ->description('Sedang menunggu pemeriksaan')
                ->descriptionColor(($counts['review'] ?? 0) > 0 ? 'info' : 'gray')
                ->icon(Heroicon::OutlinedClock)
                ->color('info')
                ->url(PostResource::getUrl('index', ['activeTab' => 'review'])),
            Stat::make('Terbit', number_format($counts['published'] ?? 0))
                ->description('Sudah tampil di portal')
                ->descriptionColor('success')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->url(PostResource::getUrl('index', ['activeTab' => 'published'])),
            Stat::make('Ditolak', number_format($counts['rejected'] ?? 0))
                ->description('Perlu diperbaiki')
                ->descriptionColor(($counts['rejected'] ?? 0) > 0 ? 'danger' : 'gray')
                ->icon(Heroicon::OutlinedExclamationCircle)
                ->color('danger')
                ->url(PostResource::getUrl('index', ['activeTab' => 'rejected'])),
        ];
    }
}
