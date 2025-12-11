<?php

namespace App\Console\Commands;

use App\Models\OrderReview;
use Illuminate\Console\Command;

class ShowReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:show {--limit=10 : Number of reviews to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show customer reviews and testimonials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $reviews = OrderReview::with('order.service')->latest('submitted_at')->take($limit)->get();

        $this->info('⭐ Customer Reviews & Testimonials');
        $this->line('=====================================');
        $this->line("Showing {$reviews->count()} reviews (latest first)");
        $this->newLine();

        if ($reviews->isEmpty()) {
            $this->warn('No reviews found. Run database seeding first.');
            return;
        }

        foreach ($reviews as $index => $review) {
            // Rating stars
            $stars = str_repeat('⭐', $review->rating);
            $this->line("<comment>" . ($index + 1) . ".</comment> {$stars} <info>({$review->rating}/5)</info>");

            // Headline
            $this->line("<comment>📝</comment> <bold>{$review->headline}</bold>");

            // Message
            $this->line("<comment>💬</comment> {$review->message}");

            // Service info
            $serviceName = $review->order->service->name ?? 'Unknown Service';
            $this->line("<comment>🛍️</comment>  <info>{$serviceName}</info>");

            // Date
            $this->line("<comment>📅</comment> {$review->submitted_at->format('d M Y')} ({$review->submitted_at->diffForHumans()})");

            $this->line(str_repeat('─', 50));
        }

        $this->newLine();
        $this->info('✅ All reviews are public and display on the website!');

        // Show statistics
        $avgRating = OrderReview::avg('rating');
        $totalReviews = OrderReview::count();
        $this->line("📊 <comment>Statistics:</comment> {$totalReviews} reviews, average rating: <info>" . number_format($avgRating, 1) . "/5</info> ⭐");
    }
}
