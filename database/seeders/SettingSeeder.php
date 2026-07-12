<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $settings = [
                [
                    "key" => "site_name",
                    "value" => "Product Catalog",
                    "type" => "text",
                ],
                [
                    "key" => "site_logo",
                    "value" => null,
                    "type" => "file",
                ],
                [
                    "key" => "site_favicon",
                    "value" => null,
                    "type" => "file",
                ],
                [
                    "key" => "whatsapp_number",
                    "value" => "12345678",
                    "type" => "phone",
                ],
                [
                    "key" => "website_url",
                    "value" => "http://localhost:8081",
                    "type" => "url",
                ],
                [
                    "key" => "whatsapp_message",
                    "value" => "Hello, I would like to inquire about your products.",
                    "type" => "text",
                ],
            
        ];
        foreach ($settings as $setting) {
            $model = Setting::firstOrNew(['key' => $setting['key']]);

            if (! $model->exists) {
                $model->value = $setting['value'];
            }

            $model->type = $setting['type'];
            $model->save();
        }

    }
}
