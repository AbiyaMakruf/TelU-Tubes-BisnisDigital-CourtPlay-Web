<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Court;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $courts = [
            [
                'name'    => 'Gelora Tennis Court 1',
                'type'    => 'tennis',
                'surface' => 'hard',
                'city'    => 'Jakarta',
                'address' => 'Jl. Gelora Bung Karno No.1',
            ],
            [
                'name'    => 'Gelora Tennis Court 2',
                'type'    => 'tennis',
                'surface' => 'hard',
                'city'    => 'Jakarta',
                'address' => 'Jl. Gelora Bung Karno No.1',
            ],
            [
                'name'    => 'Senayan Padel Court A',
                'type'    => 'padel',
                'surface' => 'synthetic',
                'city'    => 'Jakarta',
                'address' => 'Jl. Asia Afrika, Senayan',
            ],
            [
                'name'    => 'BSD Indoor Court',
                'type'    => 'tennis',
                'surface' => 'indoor hard',
                'city'    => 'Tangerang',
                'address' => 'BSD City Sport Center',
            ],
            [
                'name'    => 'Bandung Tennis Court A',
                'type'    => 'tennis',
                'surface' => 'clay',
                'city'    => 'Bandung',
                'address' => 'Jl. Ciumbuleuit No.8',
            ],
            [
                'name'    => 'Padel Arcadia Court 1',
                'type'    => 'padel',
                'surface' => 'synthetic',
                'city'    => 'Jakarta Selatan',
                'address' => 'Arcadia Senopati',
            ],
        ];

        foreach ($courts as $court) {
            Court::create([
                'id'      => Str::uuid(),
                'name'    => $court['name'],
                'type'    => $court['type'],
                'surface' => $court['surface'],
                'city'    => $court['city'],
                'address' => $court['address'],
                'status'  => 'active',
            ]);
        }
    }
}
