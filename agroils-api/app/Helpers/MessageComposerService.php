<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class MessageComposerService
{
    /**
     * Compile a final template message string by parsing and replacing dynamic payload variables.
     *
     * @param string $templateName The unique database name identifier (e.g., 'agroils_erp_otp')
     * @param array $variables Key-value pairs matching placeholders (e.g., ['name' => 'John', 'otp_code' => '123456'])
     * @return string Fully structured and parsed text ready for delivery
     */
    public static function compile(string $templateName, array $variables = []): string
    {
        // 1. Fetch template record directly from the database
        $template = DB::table('whatsapp_templates')
            ->where('name', $templateName)
            ->first();

        // 2. Fallback: If template is missing from data layer, prevent silent failure
        if (!$template) {
            throw new \InvalidArgumentException("System error: WhatsApp template [{$templateName}] does not exist.");
        }

        $messageBody = $template->message;

        // 3. Loop through variables and replace placeholders like {variable_name}
        foreach ($variables as $key => $value) {
            $placeholder = '{' . $key . '}';
            $messageBody = str_replace($placeholder, (string) $value, $messageBody);
        }

        return $messageBody;
    }
}
