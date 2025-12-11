<?php

namespace App\Services\Mailtrap;

use Mailtrap\Api\Sending\Emails;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class MailtrapService
{
    private Emails $client;
    private string $templateUuid;

    public function __construct()
    {
        $this->client = MailtrapClient::initSendingEmails(
            apiKey: config('services.mailtrap.api_key')
        );
        $this->templateUuid = config('services.mailtrap.template_uuid');
    }

    /**
     * Send email using Mailtrap API with template
     */
    public function sendTemplateEmail(
        string $toEmail,
        string $toName,
        array $templateVariables = []
    ): array {
        $email = (new MailtrapEmail())
            ->from(new Address(
                config('services.mailtrap.from_email', 'hello@digital-dev.icu'),
                config('services.mailtrap.from_name', 'Deadlineku')
            ))
            ->to(new Address($toEmail, $toName))
            ->templateUuid($this->templateUuid)
            ->templateVariables(array_merge([
                'company_info_name' => 'Deadlineku',
                'company_info_address' => 'Jakarta, Indonesia',
                'company_info_city' => 'Jakarta',
                'company_info_zip_code' => '12345',
                'company_info_country' => 'Indonesia'
            ], $templateVariables));

        $response = $this->client->send($email);

        return \Mailtrap\Helper\ResponseHelper::toArray($response);
    }

    /**
     * Send payment success notification
     */
    public function sendPaymentSuccess(string $toEmail, string $customerName, string $orderNumber, float $amount): array
    {
        return $this->sendTemplateEmail(
            toEmail: $toEmail,
            toName: $customerName,
            templateVariables: [
                'name' => $customerName,
                'order_number' => $orderNumber,
                'amount' => number_format($amount, 0, ',', '.'),
                'payment_status' => 'Berhasil',
            ]
        );
    }

    /**
     * Send order confirmation
     */
    public function sendOrderConfirmation(string $toEmail, string $customerName, string $orderNumber, string $serviceName): array
    {
        return $this->sendTemplateEmail(
            toEmail: $toEmail,
            toName: $customerName,
            templateVariables: [
                'name' => $customerName,
                'order_number' => $orderNumber,
                'service_name' => $serviceName,
            ]
        );
    }

    /**
     * Send registration welcome email
     */
    public function sendWelcomeEmail(string $toEmail, string $customerName): array
    {
        return $this->sendTemplateEmail(
            toEmail: $toEmail,
            toName: $customerName,
            templateVariables: [
                'name' => $customerName,
                'welcome_message' => 'Selamat bergabung di Deadlineku!',
            ]
        );
    }
}
