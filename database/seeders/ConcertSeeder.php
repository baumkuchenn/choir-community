<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConcertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('concerts')->insert([
            [
                'gambar' => 'https://picsum.photos/id/9/2000/1000',
                'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sagittis in mauris porttitor gravida. Integer eu massa vulputate, fermentum justo efficitur, sollicitudin diam.',
                'seating_plan' => 'https://picsum.photos/id/9/2000/1000',
                'syarat_ketentuan' => '1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                       2. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. 
                                       3. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. 
                                       4. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. 
                                       5. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut.',
                'link_ebooklet' => 'https://picsum.photos/id/9/2000/1000',
                'donasi' => 'Tidak',
                'no_rekening' => '80xxxxxxxxxxxxxxx',
                'pemilik_rekening' => 'Ubaya Choir',
                'banks_id' => 1,
                'events_id' => 1,
            ],
            [
                'gambar' => 'https://picsum.photos/id/10/2000/1000',
                'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sagittis in mauris porttitor gravida. Integer eu massa vulputate, fermentum justo efficitur, sollicitudin diam.',
                'seating_plan' => 'https://picsum.photos/id/9/2000/1000',
                'syarat_ketentuan' => '1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                       2. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. 
                                       3. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. 
                                       4. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. 
                                       5. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut.',
                'link_ebooklet' => 'https://picsum.photos/id/9/2000/1000',
                'donasi' => 'Tidak',
                'no_rekening' => '80xxxxxxxxxxxxxxx',
                'pemilik_rekening' => 'Coro Semplice',
                'banks_id' => 1,
                'events_id' => 2,
            ],
            [
                'gambar' => 'https://picsum.photos/id/11/2000/1000',
                'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sagittis in mauris porttitor gravida. Integer eu massa vulputate, fermentum justo efficitur, sollicitudin diam.',
                'seating_plan' => 'https://picsum.photos/id/9/2000/1000',
                'syarat_ketentuan' => '1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                       2. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. 
                                       3. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. 
                                       4. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. 
                                       5. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut.',
                'link_ebooklet' => 'https://picsum.photos/id/9/2000/1000',
                'donasi' => 'Tidak',
                'no_rekening' => '80xxxxxxxxxxxxxxx',
                'pemilik_rekening' => 'Batavia Madrigal Singer',
                'banks_id' => 1,
                'events_id' => 3,
            ],
            [
                'gambar' => 'https://picsum.photos/id/12/2000/1000',
                'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sagittis in mauris porttitor gravida. Integer eu massa vulputate, fermentum justo efficitur, sollicitudin diam.',
                'seating_plan' => 'https://picsum.photos/id/9/2000/1000',
                'syarat_ketentuan' => '1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                       2. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. 
                                       3. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. 
                                       4. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. 
                                       5. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut.',
                'link_ebooklet' => 'https://picsum.photos/id/9/2000/1000',
                'donasi' => 'Tidak',
                'no_rekening' => '80xxxxxxxxxxxxxxx',
                'pemilik_rekening' => 'Chandelier Choir',
                'banks_id' => 1,
                'events_id' => 4,
            ],
            [
                'gambar' => 'https://picsum.photos/id/13/2000/1000',
                'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam sagittis in mauris porttitor gravida. Integer eu massa vulputate, fermentum justo efficitur, sollicitudin diam.',
                'seating_plan' => 'https://picsum.photos/id/9/2000/1000',
                'syarat_ketentuan' => '1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                       2. Proin nec sem lorem. Aenean sed ligula elementum, pellentesque leo ac, molestie metus. 
                                       3. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam erat volutpat. 
                                       4. Curabitur vel nulla dignissim, luctus libero et, vulputate velit. Integer quis nibh condimentum, pellentesque mauris non, congue lorem. Nam malesuada magna vitae mi gravida condimentum. 
                                       5. Proin gravida feugiat auctor. Etiam ut aliquam purus. Nunc sagittis efficitur nunc, at pulvinar elit pretium ut.',
                'link_ebooklet' => 'https://picsum.photos/id/9/2000/1000',
                'donasi' => 'Tidak',
                'no_rekening' => '80xxxxxxxxxxxxxxx',
                'pemilik_rekening' => 'PCU Choir',
                'banks_id' => 1,
                'events_id' => 5,
            ],
        ]);
    }
}
