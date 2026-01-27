<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Şık Çanta Pasta',
                'description' => 'El yapımı fondant detaylar ile özenle hazırlanmış özel tasarım pasta. Çikolata ganaj ve kadife kaplama ile.',
                'base_price' => 850.00,
                'image' => 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                    'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                    'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80'
                ],
                'sizes' => [
                    ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => 850],
                    ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => 1020],
                    ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => 1275]
                ],
                'flavors' => [
                    ['id' => 'nutella', 'name' => 'Nutella', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80'],
                    ['id' => 'tropical', 'name' => 'Tropical', 'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80'],
                    ['id' => 'pistachio', 'name' => 'Pistachio Raspberry', 'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80'],
                    ['id' => 'chocolate', 'name' => 'Chocolate Delight', 'image' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=100&q=80']
                ],
                'badge' => 'Yeni',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Altın Detaylı Pasta',
                'description' => 'Lüks altın varak detayları ile süslenmiş, özel günleriniz için hazırlanmış şık pasta tasarımı.',
                'base_price' => 1200.00,
                'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
                    'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=600&q=80',
                    'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=600&q=80'
                ],
                'sizes' => [
                    ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => 1200],
                    ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => 1440],
                    ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => 1800]
                ],
                'flavors' => [
                    ['id' => 'vanilla', 'name' => 'Vanilla Bean', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80'],
                    ['id' => 'red_velvet', 'name' => 'Red Velvet', 'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80'],
                    ['id' => 'chocolate', 'name' => 'Chocolate Delight', 'image' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=100&q=80']
                ],
                'badge' => 'Popüler',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Çiçek Bahçesi Pasta',
                'description' => 'Renkli şeker çiçekleri ve fondant detayları ile süslenmiş, baharın neşesini yansıtan özel pasta.',
                'base_price' => 950.00,
                'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                    'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80',
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
                    'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=600&q=80'
                ],
                'sizes' => [
                    ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => 950],
                    ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => 1140],
                    ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => 1425]
                ],
                'flavors' => [
                    ['id' => 'strawberry', 'name' => 'Strawberry', 'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80'],
                    ['id' => 'lemon', 'name' => 'Lemon Zest', 'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80'],
                    ['id' => 'raspberry', 'name' => 'Raspberry', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80']
                ],
                'badge' => 'Özel',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Klasik Çikolata Pasta',
                'description' => 'Geleneksel çikolata pasta tarifi ile hazırlanmış, herkesin sevdiği klasik lezzet.',
                'base_price' => 750.00,
                'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80',
                    'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=600&q=80',
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80'
                ],
                'sizes' => [
                    ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => 750],
                    ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => 900],
                    ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => 1125]
                ],
                'flavors' => [
                    ['id' => 'chocolate', 'name' => 'Chocolate Delight', 'image' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=100&q=80'],
                    ['id' => 'dark_chocolate', 'name' => 'Dark Chocolate', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80'],
                    ['id' => 'milk_chocolate', 'name' => 'Milk Chocolate', 'image' => 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=100&q=80']
                ],
                'badge' => 'Trend',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Gökkuşağı Pasta',
                'description' => 'Renkli katmanlar ve vanilya kreması ile özel günlerinize renk katın.',
                'base_price' => 900.00,
                'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                    'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80',
                    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80'
                ],
                'sizes' => [
                    ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => 900],
                    ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => 1080],
                    ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => 1350]
                ],
                'flavors' => [
                    ['id' => 'vanilla', 'name' => 'Vanilla Bean', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80'],
                    ['id' => 'strawberry', 'name' => 'Strawberry', 'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80'],
                    ['id' => 'lemon', 'name' => 'Lemon Zest', 'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80']
                ],
                'badge' => 'Popüler',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Orman Meyveli Pasta',
                'description' => 'Taze orman meyveleri ve hafif krema ile hazırlanmış, doğal lezzetler.',
                'base_price' => 880.00,
                'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=600&q=80',
                    'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80',
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                    'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80'
                ],
                'sizes' => [
                    ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => 880],
                    ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => 1056],
                    ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => 1320]
                ],
                'flavors' => [
                    ['id' => 'berry', 'name' => 'Mixed Berries', 'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80'],
                    ['id' => 'blueberry', 'name' => 'Blueberry', 'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80'],
                    ['id' => 'raspberry', 'name' => 'Raspberry', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80']
                ],
                'badge' => 'Yeni',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('Ürünler başarıyla eklendi!');
    }
}
