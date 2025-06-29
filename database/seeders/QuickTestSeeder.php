<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Order;
use App\Models\Review;
use Carbon\Carbon;

class QuickTestSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing test data
        Order::where('client_id', function($query) {
            $query->select('id')->from('users')->where('email', 'client@test.com');
        })->delete();

        Service::where('provider_id', function($query) {
            $query->select('id')->from('users')->where('email', 'provider@test.com');
        })->delete();

        // Get or create test users
        $client = User::where('email', 'client@test.com')->first();
        if (!$client) {
            $client = User::create([
                'name' => 'John Smith',
                'email' => 'client@test.com',
                'password' => bcrypt('password'),
                'phone' => '+966501234567',
                'address' => 'Riyadh, Saudi Arabia'
            ]);
            $client->assignRole('client');
        }

        $provider = User::where('email', 'provider@test.com')->first();
        if (!$provider) {
            $provider = User::create([
                'name' => 'Ahmed Hassan',
                'email' => 'provider@test.com',
                'password' => bcrypt('password'),
                'phone' => '+966507654321',
                'address' => 'Jeddah, Saudi Arabia'
            ]);
            $provider->assignRole('provider');
        }

        // Create a test service
        $service = Service::create([
            'provider_id' => $provider->id,
            'title' => 'House Cleaning Service',
            'description' => 'Professional house cleaning service including dusting, vacuuming, mopping, and bathroom cleaning.',
            'price' => 80.00,
            'category' => 'Cleaning',
            'location' => 'Riyadh',
            'availability' => json_encode(['monday' => true, 'tuesday' => true, 'wednesday' => true, 'thursday' => true, 'friday' => true]),
            'is_active' => true
        ]);

        // Create test orders with different statuses
        $orders = [
            [
                'title' => 'Weekly House Cleaning',
                'description' => 'Need regular house cleaning service for my apartment. 2 bedrooms, 1 bathroom, kitchen and living room.',
                'status' => Order::STATUS_PENDING,
                'scheduled_date' => Carbon::now()->addDays(2),
                'location' => 'Riyadh, Al Olaya District',
                'contact_phone' => '+966501234567',
                'contact_email' => 'client@test.com',
                'client_notes' => 'Please bring your own cleaning supplies. I prefer eco-friendly products.',
                'provider_notes' => null,
                'completed_date' => null
            ],
            [
                'title' => 'Kitchen Sink Repair',
                'description' => 'Kitchen sink is clogged and water is not draining properly. Need urgent repair.',
                'status' => Order::STATUS_ACCEPTED,
                'scheduled_date' => Carbon::now()->addDay(),
                'location' => 'Jeddah, Al Hamra District',
                'contact_phone' => '+966501234567',
                'contact_email' => 'client@test.com',
                'client_notes' => 'Available anytime between 9 AM and 6 PM',
                'provider_notes' => 'Order accepted. Will arrive tomorrow at 10 AM with necessary tools.',
                'completed_date' => null
            ],
            [
                'title' => 'Electrical Outlet Installation',
                'description' => 'Need to install 3 new electrical outlets in the living room for TV and entertainment system.',
                'status' => Order::STATUS_IN_PROGRESS,
                'scheduled_date' => Carbon::now()->addDays(3),
                'location' => 'Dammam, Al Faisaliyah District',
                'contact_phone' => '+966501234567',
                'contact_email' => 'client@test.com',
                'client_notes' => 'Please ensure all outlets are properly grounded and meet safety standards.',
                'provider_notes' => 'Started work on electrical installation. All materials purchased and work in progress.',
                'completed_date' => null
            ],
            [
                'title' => 'Deep Carpet Cleaning',
                'description' => 'Professional carpet cleaning needed for living room and bedroom carpets.',
                'status' => Order::STATUS_COMPLETED,
                'scheduled_date' => Carbon::now()->subDays(5),
                'location' => 'Riyadh, Al Malaz District',
                'contact_phone' => '+966501234567',
                'contact_email' => 'client@test.com',
                'client_notes' => 'Carpets are heavily stained, need special treatment for coffee and food stains.',
                'provider_notes' => 'Work completed successfully. All stains removed and carpets look like new.',
                'completed_date' => Carbon::now()->subDays(3)
            ],
            [
                'title' => 'Bathroom Renovation',
                'description' => 'Complete bathroom renovation including new tiles, fixtures, and plumbing.',
                'status' => Order::STATUS_REJECTED,
                'scheduled_date' => Carbon::now()->addDays(7),
                'location' => 'Jeddah, Al Zahra District',
                'contact_phone' => '+966501234567',
                'contact_email' => 'client@test.com',
                'client_notes' => 'Need complete bathroom makeover with modern design.',
                'provider_notes' => 'Unable to take this project due to current workload. Recommend contacting specialized renovation company.',
                'completed_date' => null
            ]
        ];

        foreach ($orders as $orderData) {
            Order::create([
                'client_id' => $client->id,
                'provider_id' => $provider->id,
                'service_id' => $service->id,
                'title' => $orderData['title'],
                'description' => $orderData['description'],
                'price' => $service->price,
                'status' => $orderData['status'],
                'scheduled_date' => $orderData['scheduled_date'],
                'location' => $orderData['location'],
                'contact_phone' => $orderData['contact_phone'],
                'contact_email' => $orderData['contact_email'],
                'client_notes' => $orderData['client_notes'],
                'provider_notes' => $orderData['provider_notes'],
                'completed_date' => $orderData['completed_date']
            ]);
        }

        // Create a review for the completed order
        $completedOrder = Order::where('status', Order::STATUS_COMPLETED)->first();
        if ($completedOrder) {
            Review::create([
                'order_id' => $completedOrder->id,
                'client_id' => $client->id,
                'provider_id' => $provider->id,
                'rating' => 5,
                'comment' => 'Excellent service! The carpet cleaning was thorough and professional. Highly recommend this provider.',
                'is_public' => true
            ]);
        }

        $this->command->info('Quick test data created successfully!');
        $this->command->info('Client: client@test.com / password');
        $this->command->info('Provider: provider@test.com / password');
        $this->command->info('Created: 1 service, 5 orders with different statuses, 1 review');
    }
}
