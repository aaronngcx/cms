<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish';
    protected $description = 'Publish posts that are scheduled to be published.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $postsToPublish = Post::where('published_at', '<=', $now)
            ->where('status', 'pending')
            ->get();

        foreach ($postsToPublish as $post) {
            $post->status = 'published';
            $post->save();

            $this->info("Published post: {$post->title}");
        }
    }
}
