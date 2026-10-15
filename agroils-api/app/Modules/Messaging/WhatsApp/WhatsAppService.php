<?php

namespace App\Modules\Messaging\WhatsApp;

use Gowa\Laravel\Facades\Gowa as WhatsApp;

class WhatsAppService
{
    /**
     * Send a transactional WhatsApp text message.
     *
     * @param string $recipientPhone The sanitized number in 260... format
     * @param string $messageBody The pre-compiled corporate text payload
     * @return mixed
     */
    public function sendMessage(string $recipientPhone, string $messageBody)
    {
        return WhatsApp::to($recipientPhone)
            ->text($messageBody)
            ->send();
    }
}
