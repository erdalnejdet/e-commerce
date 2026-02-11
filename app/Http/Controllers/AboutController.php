<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Get about page sections
        $sections = [
            'title' => PageSection::getSection('home', 'about_title', 'WE MAKE CAKES ONLY WITH LOVE'),
            'content_1' => PageSection::getSection('home', 'about_content_1', 'PAULINE olarak, her pastayı bir sanat eseri gibi özenle hazırlıyoruz.'),
            'content_2' => PageSection::getSection('home', 'about_content_2', 'Deneyimli pastacı ekibimiz, geleneksel tarifleri modern dokunuşlarla birleştirerek, her damak tadına hitap eden benzersiz lezzetler yaratıyor.'),
            'image' => PageSection::getSection('home', 'about_image', 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&q=80'),
        ];

        return view('about', compact('sections'));
    }
}
