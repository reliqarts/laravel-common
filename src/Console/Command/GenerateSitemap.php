<?php
/**
 * @noinspection PhpMissingFieldTypeInspection
 */

declare(strict_types=1);

namespace ReliqArts\Console\Command;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

final class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'common:sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap (via laravel-sitemap)';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        SitemapGenerator::create((string)config('app.url'))
            ->writeToFile(public_path('sitemap.xml'));
    }
}
