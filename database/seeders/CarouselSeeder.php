<?php

namespace Database\Seeders;

use App\Models\Carousel;
use Illuminate\Database\Seeder;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'image_path' => 'carousel/1.jpg',
            ],
            [
                'id' => 2,
                'image_path' => 'carousel/2.jpg',
                'author' => 'Risen Wang',
                'link' => 'https://unsplash.com/@risennnnn',
            ],
            [
                'id' => 3,
                'image_path' => 'carousel/3.jpg',
                'author' => 'Danielle Cerullo',
                'link' => 'https://unsplash.com/@dncerullo',
            ],
            [
                'id' => 4,
                'image_path' => 'carousel/4.jpg',
                'author' => 'Victor Freitas',
                'link' => 'https://unsplash.com/@victorfreitas',
            ],
            [
                'id' => 5,
                'image_path' => 'carousel/5.jpg',
                'author' => 'Karsten Winegeart',
                'link' => 'https://unsplash.com/@_karsten',
            ],
            [
                'id' => 6,
                'image_path' => 'carousel/6.jpg',
                'author' => 'Graham Mansfield',
                'link' => 'https://unsplash.com/@grahammansfield1',
            ],
            [
                'id' => 7,
                'image_path' => 'carousel/7.jpg',
            ],
        ];

        foreach ($items as $item) {
            Carousel::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
