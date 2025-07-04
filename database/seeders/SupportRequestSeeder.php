<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportRequest;
use App\Models\User;

class SupportRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $statuses = ['pending', 'in_progress', 'resolved', 'rejected'];

        $supportRequests = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '0123456789',
                'title' => 'Booking Cancellation Request',
                'message' => 'I need to cancel my tour booking for next week due to an emergency. Please help me with the refund process.',
                'status' => 'pending',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@example.com',
                'phone' => '0987654321',
                'title' => 'Tour Information Inquiry',
                'message' => 'I would like to know more details about the Phu Quoc tour package. What activities are included?',
                'status' => 'in_progress',
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike.wilson@example.com',
                'phone' => '0555666777',
                'title' => 'Payment Issue',
                'message' => 'I tried to pay for my booking but the payment failed. Can you help me resolve this issue?',
                'status' => 'resolved',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '0333444555',
                'title' => 'Beach Access Information',
                'message' => 'I want to know if the beach is accessible for people with disabilities. Are there any special facilities?',
                'status' => 'resolved',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'phone' => '0777888999',
                'title' => 'Weather Concerns',
                'message' => 'I\'m worried about the weather for my upcoming beach visit. What happens if it rains?',
                'status' => 'pending',
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'phone' => '0111222333',
                'title' => 'Group Booking Discount',
                'message' => 'I\'m planning a trip for 15 people. Do you offer any group discounts?',
                'status' => 'in_progress',
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@example.com',
                'phone' => '0444555666',
                'title' => 'Transportation Services',
                'message' => 'Does your tour include transportation from the airport to the beach?',
                'status' => 'resolved',
            ],
            [
                'name' => 'Jennifer Lee',
                'email' => 'jennifer.lee@example.com',
                'phone' => '0666777888',
                'title' => 'Accommodation Request',
                'message' => 'I need accommodation near the beach. Can you recommend any hotels?',
                'status' => 'rejected',
            ],
            [
                'name' => 'Thomas Garcia',
                'email' => 'thomas.garcia@example.com',
                'phone' => '0888999000',
                'title' => 'Equipment Rental',
                'message' => 'Do you rent beach equipment like umbrellas and chairs? What are the prices?',
                'status' => 'pending',
            ],
            [
                'name' => 'Amanda Martinez',
                'email' => 'amanda.martinez@example.com',
                'phone' => '0222333444',
                'title' => 'Safety Information',
                'message' => 'I\'m concerned about safety at the beach. Are there lifeguards on duty?',
                'status' => 'resolved',
            ],
        ];

        foreach ($supportRequests as $index => $requestData) {
            // Randomly assign a user or leave user_id as null for anonymous requests
            $user = $users->random();
            $requestData['user_id'] = $user->id;
            
            SupportRequest::create($requestData);
        }
    }
} 