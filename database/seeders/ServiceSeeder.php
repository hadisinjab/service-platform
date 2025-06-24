<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = User::role('provider')->get();

        if ($providers->isEmpty()) {
            $this->command->info('No providers found. Creating a provider first...');
            $provider = User::create([
                'name' => 'John Provider',
                'email' => 'provider@example.com',
                'password' => bcrypt('password'),
            ]);
            $provider->assignRole('provider');
            $providers = collect([$provider]);
        }

        $services = [
            [
                'title' => 'House Cleaning Service',
                'description' => 'Professional house cleaning service including dusting, vacuuming, mopping, and bathroom cleaning. Perfect for busy families who need a clean home.',
                'price' => 80.00,
                'category' => 'cleaning',
                'location' => 'New York, NY',
                'status' => 'active',
            ],
            [
                'title' => 'Plumbing Repair',
                'description' => 'Expert plumbing services for repairs, installations, and maintenance. Licensed and insured plumbers available 24/7 for emergencies.',
                'price' => 120.00,
                'category' => 'plumbing',
                'location' => 'Los Angeles, CA',
                'status' => 'active',
            ],
            [
                'title' => 'Electrical Installation',
                'description' => 'Professional electrical services including wiring, installations, repairs, and safety inspections. Certified electricians with years of experience.',
                'price' => 150.00,
                'category' => 'electrical',
                'location' => 'Chicago, IL',
                'status' => 'active',
            ],
            [
                'title' => 'Landscaping Design',
                'description' => 'Beautiful landscape design and maintenance services. From garden design to lawn care, we create outdoor spaces you\'ll love.',
                'price' => 200.00,
                'category' => 'landscaping',
                'location' => 'Miami, FL',
                'status' => 'active',
            ],
            [
                'title' => 'Carpet Cleaning',
                'description' => 'Deep carpet cleaning service using professional equipment. Removes stains, odors, and allergens for a fresh, clean carpet.',
                'price' => 90.00,
                'category' => 'cleaning',
                'location' => 'Houston, TX',
                'status' => 'active',
            ],
            [
                'title' => 'HVAC Maintenance',
                'description' => 'Complete HVAC system maintenance, repair, and installation services. Keep your home comfortable year-round with our expert technicians.',
                'price' => 180.00,
                'category' => 'hvac',
                'location' => 'Phoenix, AZ',
                'status' => 'active',
            ],
            [
                'title' => 'Window Installation',
                'description' => 'Professional window installation and replacement services. Energy-efficient windows to improve your home\'s comfort and value.',
                'price' => 300.00,
                'category' => 'construction',
                'location' => 'Denver, CO',
                'status' => 'active',
            ],
            [
                'title' => 'Pet Grooming',
                'description' => 'Professional pet grooming services including bathing, trimming, and styling. Your pets deserve the best care and attention.',
                'price' => 60.00,
                'category' => 'pet_care',
                'location' => 'Seattle, WA',
                'status' => 'active',
            ],
        ];

        foreach ($services as $serviceData) {
            $provider = $providers->random();
            Service::create(array_merge($serviceData, [
                'provider_id' => $provider->id,
            ]));
        }

        $this->command->info('Services seeded successfully!');
    }
}
