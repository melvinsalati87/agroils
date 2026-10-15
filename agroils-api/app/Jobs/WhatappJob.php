<?php

namespace App\Jobs;

use App\Helpers\MessageComposerService;
use App\Models\User;
use App\Modules\Messaging\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WhatappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected string $templateName;
    protected array $payloadData;

    /**
     * Create a new job instance.
     *
     * @param User $user The target user recipient model
     * @param string $templateName The unique lookup key for the database template
     * @param array $payloadData Extra variables to inject (e.g., ['otp_code' => '123456'])
     */
    public function __construct(User $user, string $templateName, array $payloadData = [])
    {
        $this->user = $user;
        $this->templateName = $templateName;

        // Ensure the user's name is always part of the available template data attributes
        $this->payloadData = array_merge(['name' => $user->first_name], $payloadData);
    }

    /**
     * Execute the job using method injection for the service layer.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        // 1. Compile the text body using the helper service to parse variable arrays
        $finalText = MessageComposerService::compile($this->templateName, $this->payloadData);

        // 2. Fetch the target destination contact value (formatted cleanly as 260...)
        $recipientPhone = $this->user->mobile_phone_number;

        // 3. Execute transmission via the instance method of your WhatsAppService
        $whatsAppService->sendMessage($recipientPhone, $finalText);
    }
}
