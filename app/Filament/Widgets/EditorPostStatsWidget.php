<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\AdminAccess;
use App\Models\Post;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EditorPostStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected ?string $heading = 'Ringkasan Editorial';

    protected ?string $description = 'Pantau antrean naskah dan keputusan editorial.';

    public static function canView(): bool
    {
        return AdminAccess::hasAnyRole(['Editor'])
            && ! AdminAccess::hasAnyRole(AdminAccess::CONTENT_MANAGERS);
    }

    protected function getStats(): array
    {
        $counts = Post::query()
            ->where('status', '!=', 'draft')
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            Stat::make('Menunggu Review', number_format($counts['review'] ?? 0))
                ->description('Perlu diperiksa')
                ->descriptionColor(($counts['review'] ?? 0) > 0 ? 'warning' : 'gray')
                ->icon(Heroicon::OutlinedClock)
                ->color('warning')
                ->url(PostResource::getUrl('index', ['activeTab' => 'review'])),
            Stat::make('Terbit', number_format($counts['published'] ?? 0))
                ->description('Sudah tampil di portal')
                ->descriptionColor('success')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->url(PostResource::getUrl('index', ['activeTab' => 'published'])),
            Stat::make('Ditolak', number_format($counts['rejected'] ?? 0))
                ->description('Dikembalikan untuk perbaikan')
                ->descriptionColor(($counts['rejected'] ?? 0) > 0 ? 'danger' : 'gray')
                ->icon(Heroicon::OutlinedExclamationCircle)
                ->color('danger')
                ->url(PostResource::getUrl('index', ['activeTab' => 'rejected'])),
        ];
    }
}
