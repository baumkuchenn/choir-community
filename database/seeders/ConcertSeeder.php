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
            [
                'gambar' => 'events/eventId_6.jpg',
                'deskripsi' => '🌺𝐔𝐁𝐀𝐘𝐀 𝐂𝐇𝐎𝐈𝐑 𝐏𝐑𝐄𝐒𝐄𝐍𝐓𝐒: 𝐀 𝐑𝐄𝐂𝐈𝐓𝐀𝐋 𝐂𝐎𝐍𝐂𝐄𝐑𝐓 𝟐𝟎𝟐𝟓🌺\r\nWe\'re thrilled to invite you to witness something extraordinary! Join us for this special recital concert as we prepare for the Andrea O. Veneracion International Choral Festival in Manila!\r\n📅 Saturday, March 15th, 2025\r\n📍 Multifunction Hall Teknobiologi, UBAYA Tenggilis\r\n⏰ Two sessions to choose from:\r\n   • 1st Session: 12:00 - 14:25 \r\n   • 2nd Session: 15:00 - 17:15\r\nThis isn\'t just a concert—it\'s your chance to be part of our international journey! Every note we sing carries your support with us to Manila.\r\nRSVP: 08113279797 (Admin)\r\nCome bloom with us! 🎵✨\r\n#UBAYAChoir #KabinetSUVARNA #RecitalConcert2025',
                'seating_plan' => 'seating_plans/eventId_6.jpg',
                'syarat_ketentuan' => '1. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. \r\n2. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\r\n3. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. \r\n4. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'ebooklet' => 'tidak',
                'donasi' => 'tidak',
                'no_rekening' => '0882647020',
                'pemilik_rekening' => 'Beatrice Clearesta Supriyadi',
                'berita_acara' => 'Tiket Recital Concert_Nama pemesan',
                'banks_id' => 1,
                'events_id' => 6,
                'status' => 'published',
            ],
        ]);
    }
}
