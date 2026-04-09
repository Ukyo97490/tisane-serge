<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PickupPoint;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Compte admin
        User::create([
            'name'     => 'Admin Tisane Lontan',
            'email'    => 'admin@tisane-lontan.re',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Catégories
        $categories = [
            ['name' => 'Tisanes',  'icon' => '🫖', 'description' => 'Infusions et tisanes artisanales aux plantes de La Réunion',   'sort_order' => 1],
            ['name' => 'Miels',    'icon' => '🍯', 'description' => 'Miels naturels récoltés dans les hauts de La Réunion',           'sort_order' => 2],
            ['name' => 'Sirops',   'icon' => '🧴', 'description' => 'Sirops de fruits et plantes locales, sans conservateurs',        'sort_order' => 3],
            ['name' => 'Plantes',  'icon' => '🌿', 'description' => 'Plantes séchées et fraîches pour vos infusions maison',          'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'icon'        => $cat['icon'],
                'description' => $cat['description'],
                'sort_order'  => $cat['sort_order'],
                'active'      => true,
            ]);
        }

        $tisanes  = Category::where('slug', 'tisanes')->first();
        $miels    = Category::where('slug', 'miels')->first();
        $sirops   = Category::where('slug', 'sirops')->first();
        $plantes  = Category::where('slug', 'plantes')->first();

        // Produits
        $products = [
            // Tisanes
            ['category_id' => $tisanes->id,  'name' => 'Tisane Bois de Cannelle',  'price' => 5.50,  'unit' => 'sachet 50g',  'stock' => 20, 'description' => 'Infusion douce et parfumée au bois de cannelle de La Réunion.', 'benefits' => 'Digestive, apaisante, idéale après les repas.'],
            ['category_id' => $tisanes->id,  'name' => 'Tisane Citronnelle',       'price' => 4.50,  'unit' => 'sachet 50g',  'stock' => 25, 'description' => 'Citronnelle fraîche récoltée à la main, séchée doucement.', 'benefits' => 'Relaxante, aide à l\'endormissement.'],
            ['category_id' => $tisanes->id,  'name' => 'Tisane Longoza',           'price' => 6.00,  'unit' => 'sachet 40g',  'stock' => 15, 'description' => 'Plante endémique de La Réunion, au parfum délicat et floral.', 'benefits' => 'Purifiante, antioxydante.'],
            ['category_id' => $tisanes->id,  'name' => 'Mélange Bien-être',        'price' => 7.00,  'unit' => 'sachet 60g',  'stock' => 18, 'description' => 'Mélange exclusif de plantes locales pour le bien-être quotidien.', 'benefits' => 'Tonique, relaxant, équilibrant.'],

            // Miels
            ['category_id' => $miels->id,    'name' => 'Miel de Letchi',           'price' => 12.00, 'unit' => 'pot 250g',    'stock' => 12, 'description' => 'Miel de fleurs de letchi, récolté en saison dans les vergers réunionnais.', 'benefits' => 'Riche en antioxydants, tonique naturel.'],
            ['category_id' => $miels->id,    'name' => 'Miel Toutes Fleurs',       'price' => 10.00, 'unit' => 'pot 250g',    'stock' => 15, 'description' => 'Un miel généreuse aux arômes variés, reflet de la biodiversité de l\'île.', 'benefits' => 'Antibactérien naturel, renforce l\'immunité.'],
            ['category_id' => $miels->id,    'name' => 'Miel de Vacoa',            'price' => 14.00, 'unit' => 'pot 250g',    'stock' => 8,  'description' => 'Miel rare issu des fleurs de vacoa, spécialité de La Réunion.', 'benefits' => 'Cicatrisant, anti-inflammatoire.'],

            // Sirops
            ['category_id' => $sirops->id,   'name' => 'Sirop de Gingembre',       'price' => 8.00,  'unit' => 'bouteille 25cl', 'stock' => 20, 'description' => 'Sirop de gingembre frais, légèrement épicé, fait maison.', 'benefits' => 'Digestif, stimule la circulation.'],
            ['category_id' => $sirops->id,   'name' => 'Sirop de Caféier',         'price' => 9.50,  'unit' => 'bouteille 25cl', 'stock' => 10, 'description' => 'Sirop artisanal à base de fleurs et fruits de caféier réunionnais.', 'benefits' => 'Energisant, antioxydant.'],
            ['category_id' => $sirops->id,   'name' => 'Sirop Combava Citron',     'price' => 8.50,  'unit' => 'bouteille 25cl', 'stock' => 16, 'description' => 'Association unique du combava et du citron vert de l\'île.', 'benefits' => 'Vitaminé, purifiant, tonique.'],

            // Plantes
            ['category_id' => $plantes->id,  'name' => 'Combava séché',            'price' => 4.00,  'unit' => 'sachet 30g',  'stock' => 30, 'description' => 'Zestes de combava séchés, pour vos infusions et recettes créoles.', 'benefits' => 'Antiseptique, digestif.'],
            ['category_id' => $plantes->id,  'name' => 'Bringelle sauvage',        'price' => 3.50,  'unit' => 'sachet 30g',  'stock' => 22, 'description' => 'Plante médicinale traditionnelle réunionnaise.', 'benefits' => 'Hypoglycémiant, antioxydant.'],
            ['category_id' => $plantes->id,  'name' => 'Badamier feuilles',        'price' => 3.00,  'unit' => 'sachet 30g',  'stock' => 18, 'description' => 'Feuilles de badamier séchées, remède traditionnel créole.', 'benefits' => 'Hépatoprotecteur, anti-inflammatoire.'],
        ];

        foreach ($products as $i => $p) {
            Product::create(array_merge($p, [
                'slug'       => Str::slug($p['name']),
                'active'     => true,
                'sort_order' => $i,
            ]));
        }

        // Points de retrait
        $points = [
            [
                'name'    => 'Marché de Saint-Denis',
                'address' => 'Place du Barachois',
                'city'    => 'Saint-Denis',
                'postal_code' => '97400',
                'description' => 'Stand N°14, près de l\'entrée principale.',
                'slots' => [
                    ['day' => 2, 'open' => '07:00', 'close' => '13:00'],
                    ['day' => 5, 'open' => '07:00', 'close' => '13:00'],
                    ['day' => 6, 'open' => '07:00', 'close' => '14:00'],
                ],
            ],
            [
                'name'    => 'Marché de Saint-Paul',
                'address' => 'Front de mer',
                'city'    => 'Saint-Paul',
                'postal_code' => '97460',
                'description' => 'Grand marché hebdomadaire, emplacement face à la plage.',
                'slots' => [
                    ['day' => 5, 'open' => '06:00', 'close' => '13:00'],
                    ['day' => 6, 'open' => '06:00', 'close' => '13:00'],
                ],
            ],
            [
                'name'    => 'Marché Forain de Saint-Pierre',
                'address' => 'Place Bertin',
                'city'    => 'Saint-Pierre',
                'postal_code' => '97410',
                'description' => 'Marché forain du centre-ville.',
                'slots' => [
                    ['day' => 3, 'open' => '07:00', 'close' => '12:30'],
                    ['day' => 6, 'open' => '07:00', 'close' => '13:00'],
                ],
            ],
        ];

        foreach ($points as $pointData) {
            $point = PickupPoint::create([
                'name'        => $pointData['name'],
                'address'     => $pointData['address'],
                'city'        => $pointData['city'],
                'postal_code' => $pointData['postal_code'],
                'description' => $pointData['description'],
                'active'      => true,
            ]);

            foreach ($pointData['slots'] as $slot) {
                PickupSlot::create([
                    'pickup_point_id' => $point->id,
                    'day_of_week'     => $slot['day'],
                    'open_time'       => $slot['open'],
                    'close_time'      => $slot['close'],
                    'active'          => true,
                ]);
            }
        }
    }
}
