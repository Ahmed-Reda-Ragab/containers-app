<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\User;
use App\Models\Payment;
use App\Models\ContractContainerFill;
use App\Models\Container;
use App\Models\Type;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample customer if none exists
        $customer = Customer::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Hussein Mohammed Radwan',
                'phone' => '0581262826',
                'email' => 'customer@example.com',
                'address' => 'Dammam, Saudi Arabia',
                'company_name' => 'Hussein Mohammed Radwan Trading Company',
            ]
        );

        // Create a sample user if none exists
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]
        );

        // Create a sample container type if none exists
        $type = Type::firstOrCreate(
            ['name' => '12 Yards'],
            [
                'name' => '12 Yards',
                'description' => '12 Yard Container',
            ]
        );

        // Create a sample container if none exists
        $container = Container::firstOrCreate(
            ['code' => 'CONT-001'],
            [
                'code' => 'CONT-001',
                'type_id' => $type->id,
                'size' => '12 Yards',
                'status' => 'available',
                'description' => '12 Yard Container',
            ]
        );

        // Create a sample contract
        $contract = Contract::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'company_name' => 'Support Lines Company',
            'company_phone' => '(966) 13 8060303',
            'company_email' => 'info@support-lines.com',
            'company_website' => 'support-lines.com',
            'company_pobox' => '6410 Dammam 32272 KSA',
            'quotation_number' => 'CR. 2050062276',
            'quotation_date' => now(),
            'validity_days' => 30,
            'customer_details' => [
                'company_name' => 'Hussein Mohammed Radwan Trading Company',
                'company_name_ar' => 'مؤسسة حسين محمد رضوان التجارية',
                'contact_person' => 'Mr. Ahmed',
                'contact_person_ar' => 'السيد احمد',
                'telephone' => '0',
                'mobile' => '0581262826',
                'city' => 'Dammam',
                'city_ar' => 'الدمام',
                'address' => '0',
                'address_ar' => '0'
            ],
            'container_size' => '12 Yards',
            'price_per_container' => 250,
            'no_of_containers' => 1,
            'monthly_dumping_per_container' => 2,
            'total_dumping' => 2,
            'additional_trips_price' => 250,
            'contract_period_months' => 12,
            'total_monthly_price' => 500,
            'tax_value' => 15,
            'total_monthly_with_tax' => 575,
            'total_yearly_with_tax' => 6900,
            'agreement_terms_ar' => 'Support Lines Co. will provide disposal services and equipment, and the customer agrees to payment and terms.',
            'material_restrictions_ar' => 'Collected material is solid, excluding radioactive, volatile, flammable, explosive, toxic, damaged tires, or hazardous materials.',
            'receiving_terms_ar' => 'Any delivered container will come with a service delivery note/receipt.',
            'notes_ar' => '1. Offer is valid for 30 days from the date of the offer. 2. Prices are subject to change after the specified period.',
            'payment_policy_ar' => 'All payments shall be paid within 7 days from the issuance of the invoice as agreed with the customer.',
            'advance_payment_one_year_discount' => 10,
            'advance_payment_six_months_discount' => 5,
            'manager_name' => 'Rakan M Al-Marzugi',
            'supervisor_mobile' => '0559060303',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'pending',
            'total_price' => 6900,
            'total_payed' => 0
        ]);

        // Create sample payments
        Payment::create([
            'user_id' => $user->id,
            'contract_id' => $contract->id,
            'payed' => 2000,
            'notes' => 'Initial payment',
        ]);

        Payment::create([
            'user_id' => $user->id,
            'contract_id' => $contract->id,
            'payed' => 1500,
            'notes' => 'Second payment',
        ]);

        // Create sample container fills
        ContractContainerFill::create([
            'contract_id' => $contract->id,
            'no' => 1,
            'deliver_id' => $user->id,
            'deliver_at' => now(),
            'container_id' => $container->id,
            'expected_discharge_date' => now()->addDays(30),
            'discharge_date' => null,
            'discharge_id' => null,
            'price' => 250,
            'client_id' => $customer->id,
            'city' => 'Dammam',
            'address' => 'Customer Address, Dammam',
            'notes' => 'Container delivered successfully',
        ]);

        $this->command->info('Sample contract created with ID: ' . $contract->id);
    }
}