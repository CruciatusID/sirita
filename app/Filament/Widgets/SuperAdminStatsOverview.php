<?php

namespace App\Filament\Widgets;

use App\Filament\Support\AdminAccess;
use App\Models\Banner;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuperAdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected ?string $heading = 'Ringkasan Portal';

    protected ?string $description = 'Ikhtisar konten, performa, dan aset utama SIRITA.';

    public static function canView(): bool
    {
        return AdminAccess::hasAnyRole(['Super Admin']);
    }

    protected function getStats(): array
    {
        $postCounts = Post::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            Stat::make('Total Berita', number_format(Post::query()->count()))
                ->description(number_format($postCounts['published'] ?? 0).' sudah terbit')
                ->descriptionColor('success')
                ->icon(Heroicon::OutlinedNewspaper)
                ->color('primary'),
            Stat::make('Menunggu Review', number_format($postCounts['review'] ?? 0))
                ->description('Perlu keputusan editor/admin')
                ->descriptionColor(($postCounts['review'] ?? 0) > 0 ? 'warning' : 'gray')
                ->icon(Heroicon::OutlinedClock)
                ->color('warning'),
            Stat::make('Draft', number_format($postCounts['draft'] ?? 0))
                ->description('Belum diajukan atau belum siap terbit')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('gray'),
            Stat::make('Total Dibaca', number_format((int) Post::query()->sum('views')))
                ->description('Akumulasi semua berita')
                ->icon(Heroicon::OutlinedEye)
                ->color('info'),
            Stat::make('Interaksi', number_format((int) Post::query()->sum('likes_count') + (int) Post::query()->sum('shares_count')))
                ->description(number_format((int) Post::query()->sum('shares_count')).' dibagikan')
                ->icon(Heroicon::OutlinedShare)
                ->color('success'),
            Stat::make('Aset & Pengguna', number_format(Media::query()->count()).' media')
                ->description(number_format(User::query()->count()).' pengguna, '.number_format(Banner::query()->count()).' banner')
                ->icon(Heroicon::OutlinedPhoto)
                ->color('primary'),
        ];
    }
}
