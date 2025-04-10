<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for Yalapeo website';

    public function handle()
    {
        $this->info('Generating sitemap...');
        
        $sitemap = Sitemap::create();

        // หน้าหลัก
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // หน้าเกี่ยวกับเรา
        $sitemap->add(Url::create('/เกี่ยวกับเรา')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.8));

        // ข่าวสาร
        $sitemap->add(Url::create('/news')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));

        // กระดานสนทนา
        $sitemap->add(Url::create('/webboard')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8));

        // เอกสาร
        $sitemap->add(Url::create('/page/กฎหมายระเบียบที่เกี่ยวข้อง')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        // ติดต่อเรา
        $sitemap->add(Url::create('/ติดต่อเรา')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.7));

        // บริการ e-service
        // $sitemap->add(Url::create('/services')
        //     ->setLastModificationDate(Carbon::yesterday())
        //     ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        //     ->setPriority(0.8));
            
        // TikTok Videos
        $sitemap->add(Url::create('/tiktokvideo')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.7));

        $sitemapPath = base_path('sitemap.xml');
        $sitemap->writeToFile($sitemapPath);

        $this->info('Sitemap generated successfully at: ' . $sitemapPath);
    }
}