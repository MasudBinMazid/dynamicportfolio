<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactInfo;

class ContactInfoSeeder extends Seeder
{
    public function run(): void
    {
        ContactInfo::updateOrCreate(
            ['email' => 'rm.masud420@gmail.com'],
            [
                'phone' => '+8801613013536',
                'address' => 'Dhaka, Bangladesh',
                'github' => 'https://github.com/masudbinmazid',
                'linkedin' => 'https://www.linkedin.com/in/masudbinmazid',
                'twitter' => 'https://twitter.com/masudbinmazid',
                'map_embed' => '<iframe src="https://maps.google.com/..."></iframe>',
                'cv_url' => 'https://example.com/masud-cv.pdf'
            ]
        );
    }
}
