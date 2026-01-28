<?php

namespace common\helpers;

use Yii;

class BotDetector
{
    /**
     * @param string|null $userAgent
     * @return bool
     */
    public static function isSearchBot(?string $userAgent = null): bool
    {
        $ua = $userAgent ?? (Yii::$app->request->userAgent ?? '');
        if ($ua === '') {
            return false;
        }

        $bots = [
            'AhrefsBot',
            'AcademicBotRTU',
            'Applebot',
            'Baiduspider',
            'Bingbot',
            'DuckDuckBot',
            'FacebookBot',
            'Googlebot',
            'Googlebot-Mobile',
            'Googlebot-Image',
            'Googlebot-News',
            'Googlebot-Video',
            'Google-AdsBot',
            'Google-InspectionTool',
            'LinkedInBot',
            'Mail.RU_Bot',
            'MJ12Bot',
            'PinterestBot',
            'RocketCrawlerbot',
            'SemrushBot',
            'Screaming Frog SEO Spider',
            'SiteAnalyzerbot',
            'Slurp',
            'Twitterbot',
            'YandexBot',
            'YandexImages',
            'GPTBot',
            'ChatGPT-User',
            'PerplexityBot',
            'Claude-Web',
            'ClaudeBot',
            'Claude-User',
            'Claude-SearchBot',
        ];

        foreach ($bots as $bot) {
            if (stripos($ua, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
