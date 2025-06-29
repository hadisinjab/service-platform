<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $providers = User::role('provider')->get();

        if ($providers->isEmpty()) {
            $this->command->info('No providers found. Creating sample providers...');
            $providers = User::factory(5)->create();
            foreach ($providers as $provider) {
                $provider->assignRole('provider');
            }
        }

        $services = [
            [
                'title' => 'House Cleaning Service',
                'description' => 'Professional house cleaning service including dusting, vacuuming, mopping, and bathroom cleaning.',
                'price' => 80.00,
                'category' => 'Cleaning',
                'location' => 'Riyadh',
                'availability' => json_encode(['monday' => true, 'tuesday' => true, 'wednesday' => true, 'thursday' => true, 'friday' => true])
            ],
            [
                'title' => 'Plumbing Repair',
                'description' => 'Expert plumbing services for repairs, installations, and maintenance.',
                'price' => 120.00,
                'category' => 'Plumbing',
                'location' => 'Jeddah',
                'availability' => json_encode(['monday' => true, 'tuesday' => true, 'wednesday' => true, 'thursday' => true, 'friday' => true, 'saturday' => true])
            ],
            [
                'title' => 'Electrical Installation',
                'description' => 'Professional electrical work including wiring, installations, and repairs.',
                'price' => 150.00,
                'category' => 'Electrical',
                'location' => 'Dammam',
                'availability' => json_encode(['sunday' => true, 'monday' => true, 'tuesday' => true, 'wednesday' => true, 'thursday' => true])
            ],
            [
                'title' => 'Garden Maintenance',
                'description' => 'Complete garden care including trimming, watering, and landscaping.',
                'price' => 60.00,
                'category' => 'Gardening',
                'location' => 'Riyadh',
                'availability' => json_encode(['friday' => true, 'saturday' => true, 'sunday' => true])
            ],
            [
                'title' => 'Carpet Cleaning',
                'description' => 'Deep carpet cleaning and stain removal using professional equipment.',
                'price' => 100.00,
                'category' => 'Cleaning',
                'location' => 'Jeddah',
                'availability' => json_encode(['monday' => true, 'tuesday' => true, 'wednesday' => true, 'thursday' => true, 'friday' => true])
            ],
            [
                'title' => 'AC Maintenance',
                'description' => 'Air conditioning service, maintenance, and repair.',
                'price' => 90.00,
                'category' => 'HVAC',
                'location' => 'Dammam',
                'availability' => json_encode(['sunday' => true, 'monday' => true, 'tuesday' => true, 'wednesday' => true, 'thursday' => true, 'friday' => true])
            ],
            [
                'title' => 'Painting Service',
                'description' => 'Interior and exterior painting with quality materials and professional finish.',
                'price' => 200.00,
                'category' => 'Painting',
                'location' => 'Riyadh',
                'availability' => json_encode(['saturday' => true, 'sunday' => true, 'monday' => true, 'tuesday' => true, 'wednesday' => true])
            ],
            [
                'title' => 'Furniture Assembly',
                'description' => 'Professional furniture assembly and installation service.',
                'price' => 50.00,
                'category' => 'Assembly',
                'location' => 'Jeddah',
                'availability' => json_encode(['friday' => true, 'saturday' => true, 'sunday' => true, 'monday' => true])
            ]
        ];

        foreach ($providers as $provider) {
            $randomServices = array_rand($services, rand(2, 4));
            foreach ($randomServices as $index) {
                Service::create([
                    'provider_id' => $provider->id,
                    'title' => $services[$index]['title'],
                    'description' => $services[$index]['description'],
                    'price' => $services[$index]['price'],
                    'category' => $services[$index]['category'],
                    'location' => $services[$index]['location'],
                    'availability' => $services[$index]['availability'],
                    'is_active' => true
                ]);
            }
        }

        $this->command->info('Services seeded successfully!');
    }
}
