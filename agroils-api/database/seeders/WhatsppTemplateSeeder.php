<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WhatsppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'agroils_erp_otp',
                'message' => "AGROILS ERP SECURITY NOTICE\n\n" .
                    "Hello {name},\n\n" .
                    "Your verification OTP code for logging into Agroils ERP is:\n" .
                    "*{otp_code}*\n\n" .
                    "This code is valid for the next 10 minutes. If you did not request this verification, please secure your account immediately.\n\n" .
                    "Regards,\n" .
                    "Agroils IT Operations Team",
            ],
            [
                'name' => 'agroils_erp_verified',
                'message' => "PHONE NUMBER VERIFIED\n\n" .
                    "Hello {name},\n\n" .
                    "Your mobile number has been successfully verified on the Agroils ERP platform.\n\n" .
                    "Your account profile is now fully active. You can now access your dashboard safely.\n\n" .
                    "Thank you for keeping your account secure,\n" .
                    "Agroils ERP Support Team",
            ],
            [
                'name' => 'agroils_erp_security_assurance',
                'message' => "YOUR SECURITY IS OUR PRIORITY\n\n" .
                    "Hello {name},\n\n" .
                    "We want to assure you that your data is safe with Agroils ERP. We utilize industry-grade encryption frameworks to safeguard your profile metrics and business operations.\n\n" .
                    "Guidelines to maintain data integrity:\n" .
                    "- Do not share your OTP codes or credentials with any individual, including support personnel.\n" .
                    "- Ensure you terminate your active session when utilizing a shared workstation.\n" .
                    "- Report any unauthorized modifications or suspicious actions immediately.\n\n" .
                    "Our information security systems are fully managed to protect your workflow. Have a productive day.\n\n" .
                    "Warm regards,\n" .
                    "Agroils Cyber Security Team",
            ],
        ];

        foreach ($templates as $template) {
            DB::table('whatsapp_templates')->updateOrInsert(
                ['name' => $template['name']],
                [
                    'template_uuid' => (string) Str::uuid(),
                    'language' => 'en',
                    'created_by' => 'system',
                    'message' => $template['message'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
