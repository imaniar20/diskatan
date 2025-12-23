<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTmpNews extends Command
{
    protected $signature = 'tmp:clean-news';
    protected $description = 'Hapus file tmp/news yang lebih dari 1 hari';

    public function handle()
    {
        $files = Storage::disk('public')->files('tmp/news');
        $now = Carbon::now();

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(
                Storage::disk('public')->lastModified($file)
            );

            if ($lastModified->diffInHours($now) >= 24) {
                Storage::disk('public')->delete($file);
                $this->info("Deleted: {$file}");
            }
        }

        $this->info('Cleanup tmp/news selesai');
    }
}
