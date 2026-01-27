<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PageSection;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active products
        $products = Product::active()->get();
        
        // Prepare products data for JavaScript
        $productsData = [];
        foreach ($products as $product) {
            $images = [];
            if ($product->images && count($product->images) > 0) {
                $images = $product->images;
            } elseif ($product->image) {
                $images = [$product->image];
            }
            
            $productsData[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description ?? '',
                'basePrice' => (float)$product->base_price,
                'image' => $product->image ?? '',
                'images' => $images,
                'sizes' => $product->sizes ?? [],
                'flavors' => $product->flavors ?? [],
            ];
        }
        
        // Get page sections
        $sections = [
            'hero_title' => PageSection::getSection('home', 'hero_title', 'MAKE LIFE BEAUTIFUL'),
            'hero_subtitle' => PageSection::getSection('home', 'hero_subtitle', 'Özel günlerinizi unutulmaz kılacak, el yapımı pastalar ve tatlılar'),
            'hero_image' => PageSection::getSection('home', 'hero_image', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1920&q=80'),
            'top_picks_title' => PageSection::getSection('home', 'top_picks_title', 'TOP PICKS'),
            'top_picks_subtitle' => PageSection::getSection('home', 'top_picks_subtitle', 'En çok tercih edilen özel tasarım pastalarımız'),
            'flavours_title' => PageSection::getSection('home', 'flavours_title', 'OUR FLAVOURS'),
            'flavours_subtitle' => PageSection::getSection('home', 'flavours_subtitle', 'Benzersiz lezzet kombinasyonlarımızı keşfedin'),
            'about_title' => PageSection::getSection('home', 'about_title', 'WE MAKE CAKES ONLY WITH LOVE'),
            'about_content_1' => PageSection::getSection('home', 'about_content_1', 'PAULINE olarak, her pastayı bir sanat eseri gibi özenle hazırlıyoruz.'),
            'about_content_2' => PageSection::getSection('home', 'about_content_2', 'Deneyimli pastacı ekibimiz, geleneksel tarifleri modern dokunuşlarla birleştirerek, her damak tadına hitap eden benzersiz lezzetler yaratıyor.'),
            'about_image' => PageSection::getSection('home', 'about_image', 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&q=80'),
        ];
        
        return view('home', compact('products', 'sections', 'productsData'));
    }
}
