<?php

namespace App\Console\Commands;

use App\Services\Mailtrap\MailtrapService;
use Illuminate\Console\Command;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Email address to send test to} {--type=template : Type of email to send (template|payment|welcome)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email using Mailtrap API with templates';

    protected MailtrapService $mailtrapService;

    public function __construct(MailtrapService $mailtrapService)
    {
        parent::__construct();
        $this->mailtrapService = $mailtrapService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'test@example.com';
        $type = $this->option('type');

        $this->info('📧 Testing Mailtrap API email configuration...');
        $this->newLine();

        $this->line("📨 Sending test email to: <comment>{$email}</comment>");
        $this->line("🎨 Email type: <comment>{$type}</comment>");
        $this->line("🔑 API Key: <comment>" . (config('services.mailtrap.api_key') ? 'Set' : 'Not set') . "</comment>");
        $this->line("📝 Template UUID: <comment>" . config('services.mailtrap.template_uuid') . "</comment>");
        $this->newLine();

        try {
            $result = match ($type) {
                'payment' => $this->mailtrapService->sendPaymentSuccess(
                    toEmail: $email,
                    customerName: 'Test Customer',
                    orderNumber: 'DL-TEST-001',
                    amount: 150000
                ),
                'welcome' => $this->mailtrapService->sendWelcomeEmail(
                    toEmail: $email,
                    customerName: 'Test Customer'
                ),
                default => $this->mailtrapService->sendTemplateEmail(
                    toEmail: $email,
                    toName: 'Test Customer',
                    templateVariables: [
                        'name' => 'Test Customer',
                        'message' => 'This is a test email from Deadlineku API!'
                    ]
                )
            };

            $this->info('✅ Test email sent successfully!');
            $this->line("📊 Response: <comment>" . json_encode($result, JSON_PRETTY_PRINT) . "</comment>");
            $this->newLine();
            $this->comment('📬 Check your Mailtrap inbox to verify the email was received.');

        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email:');
            $this->error($e->getMessage());
            $this->newLine();

            $this->comment('🔧 Troubleshooting:');
            $this->comment('1. Check your MAILTRAP_API_KEY in .env file');
            $this->comment('2. Verify your Mailtrap API credentials');
            $this->comment('3. Make sure the template UUID is correct');
            $this->comment('4. Check your Mailtrap account has API access');
            $this->newLine();
            $this->comment('📖 Mailtrap API Guide: https://mailtrap.io/blog/send-emails-with-mailtrap-api/');

            return 1;
        }

        return 0;
    }
}
