<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Post;
use App\Models\PostView;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sirita:fabricate-views', function () {
    $this->info('Starting fabrication of post views using real traffic distribution (June/July)...');
    
    $posts = Post::where('views', '>', 0)->get();
    
    $totalFabricated = 0;

    foreach ($posts as $post) {
        $actualViews = $post->views;
        $loggedViews = PostView::where('post_id', $post->id)->count();
        
        $diff = $actualViews - $loggedViews;
        
        if ($diff <= 0) {
            continue;
        }
        
        $this->line("Fabricating {$diff} views for post: {$post->title}");
        
        $publishedAt = $post->published_at ?? Carbon::create(2026, 5, 4, 12, 0, 0);
        $loggingStartDate = Carbon::create(2026, 6, 8, 10, 15, 0);
        $upperLimit = $publishedAt->lt($loggingStartDate) ? $loggingStartDate : now();
        
        $viewsToInsert = [];
        
        for ($i = 0; $i < $diff; $i++) {
            $randomTimestamp = Carbon::createFromTimestamp(
                rand($publishedAt->timestamp, $upperLimit->timestamp)
            );
            
            // 1. Fabricate Referrer based on real June/July statistics:
            // - Direct (null): 99.52%
            // - lm.facebook.com: 0.09%
            // - facebook.com: 0.08%
            // - instagram.com: 0.07%
            // - t.co: 0.06%
            // - google.com: 0.06%
            // - berita.kemenagtanatoraja.id: 0.04%
            // - m.facebook.com: 0.03%
            // - bing.com: 0.03%
            // - l.facebook.com: 0.02%
            $randRef = rand(1, 10000);
            if ($randRef <= 9952) {
                $referrer = null;
            } elseif ($randRef <= 9961) {
                $referrer = 'lm.facebook.com';
            } elseif ($randRef <= 9969) {
                $referrer = 'facebook.com';
            } elseif ($randRef <= 9976) {
                $referrer = 'instagram.com';
            } elseif ($randRef <= 9982) {
                $referrer = 't.co';
            } elseif ($randRef <= 9988) {
                $referrer = 'google.com';
            } elseif ($randRef <= 9992) {
                $referrer = 'berita.kemenagtanatoraja.id';
            } elseif ($randRef <= 9995) {
                $referrer = 'm.facebook.com';
            } elseif ($randRef <= 9998) {
                $referrer = 'bing.com';
            } else {
                $referrer = 'l.facebook.com';
            }
            
            // 2. Fabricate User Agent based on real June/July statistics:
            // - Chrome: 17.69%
            // - Safari: 1.62%
            // - Edge: 1.20%
            // - Firefox: 0.45%
            // - Lainnya (WhatsApp/Mobile Webviews): 79.04%
            $randUA = rand(1, 10000);
            if ($randUA <= 1769) {
                // Chrome
                $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
            } elseif ($randUA <= 1769 + 162) {
                // Safari
                $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';
            } elseif ($randUA <= 1769 + 162 + 120) {
                // Edge
                $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0';
            } elseif ($randUA <= 1769 + 162 + 120 + 45) {
                // Firefox
                $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/121.0';
            } else {
                // Lainnya (WhatsApp/FB Webview, dll)
                $otherUAs = [
                    'WhatsApp/2.23.20.0',
                    'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
                    'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBAV/423.0.0.21.61;FBBV/486241088;FBDV/iPhone13,4;FBMD/iPhone;FBSN/iOS;FBSV/16.5;FBSS/3;FBTI/phone;FBCR/T-Mobile;FBOP/5;FBRV/487332219]',
                    'Mozilla/5.0 (Linux; Android 13; SM-A536B Build/TP1A.220624.014; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/114.0.5735.196 Mobile Safari/537.36 OPR/75.0.3978.72439',
                ];
                $userAgent = $otherUAs[array_rand($otherUAs)];
            }
            
            $viewsToInsert[] = [
                'post_id' => $post->id,
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => $userAgent,
                'referrer' => $referrer,
                'created_at' => $randomTimestamp,
            ];
        }
        
        if (count($viewsToInsert) > 0) {
            foreach (array_chunk($viewsToInsert, 200) as $chunk) {
                PostView::insert($chunk);
            }
        }
        
        $totalFabricated += $diff;
    }
    
    $this->info("Completed! Total {$totalFabricated} views fabricated.");
})->purpose('Fabricate post views logs in the post_views table based on cumulative posts.views column values');
