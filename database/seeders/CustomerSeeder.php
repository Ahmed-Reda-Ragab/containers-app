<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample customers
        $customers = [
            [
                'name' => 'John Smith',
                'phone' => '+1 (555) 123-4567',
                'email' => 'john.smith@email.com',
                'address' => '123 Main Street, New York, NY 10001',
                'company_name' => 'Smith Construction LLC',
                'notes' => 'Regular customer, prefers monthly billing',
            ],
            [
                'name' => 'Sarah Johnson',
                'phone' => '+1 (555) 987-6543',
                'email' => 'sarah.j@buildcorp.com',
                'address' => '456 Oak Avenue, Los Angeles, CA 90210',
                'company_name' => 'BuildCorp Industries',
                'notes' => 'Large volume customer, needs frequent dumping',
            ],
            [
                'name' => 'Mike Wilson',
                'phone' => '+1 (555) 456-7890',
                'email' => 'mike.wilson@email.com',
                'address' => '789 Pine Road, Chicago, IL 60601',
                'company_name' => null,
                'notes' => 'Individual contractor, occasional service',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create sample containers if they don't exist
        if (Container::count() === 0) {
            $containers = [
                [
                    'code' => 'CONT001',
                    'type' => 'dry',
                    'size' => '12 Yards',
                    'status' => 'available',
                    'description' => 'Standard dry container for general waste',
                ],
                [
                    'code' => 'CONT002',
                    'type' => 'reefer',
                    'size' => '20 Yards',
                    'status' => 'available',
                    'description' => 'Refrigerated container for temperature-sensitive materials',
                ],
                [
                    'code' => 'CONT003',
                    'type' => 'tank',
                    'size' => '15 Yards',
                    'status' => 'available',
                    'description' => 'Tank container for liquid waste',
                ],
                [
                    'code' => 'CONT004',
                    'type' => 'high_cube',
                    'size' => '40 Yards',
                    'status' => 'available',
                    'description' => 'High cube container for oversized items',
                ],
                [
                    'code' => 'CONT005',
                    'type' => 'flat_rack',
                    'size' => '30 Yards',
                    'status' => 'available',
                    'description' => 'Flat rack container for heavy machinery',
                ],
            ];

            foreach ($containers as $containerData) {
                Container::create($containerData);
            }
        }
    }
}
