<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Property;
use App\Models\BankAccount;
use App\Models\Booking;
use App\Models\Testimonial;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // USERS
        // =============================================
        User::create([
            'name' => 'Administrator', 'email' => 'admin@nginepyuk.com',
            'username' => 'admin', 'phone' => '6289514392694',
            'role' => 'admin', 'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $users = [
            ['name'=>'User Demo',       'email'=>'user@nginepyuk.com',       'username'=>'userdemo',    'phone'=>'6281234567890'],
            ['name'=>'Rina Andriani',   'email'=>'rina@gmail.com',           'username'=>'rina_andriani','phone'=>'6281398765432'],
            ['name'=>'Budi Santoso',    'email'=>'budi@gmail.com',           'username'=>'budi_santoso', 'phone'=>'6282211223344'],
            ['name'=>'Sari Dewi',       'email'=>'sari@gmail.com',           'username'=>'sari_dewi',    'phone'=>'6283344556677'],
            ['name'=>'Dian Prasetyo',   'email'=>'dian@gmail.com',           'username'=>'dian_p',       'phone'=>'6284455667788'],
            ['name'=>'Maya Kusuma',     'email'=>'maya@gmail.com',           'username'=>'maya_kusuma',  'phone'=>'6285566778899'],
            ['name'=>'Rizky Ramadhan',  'email'=>'rizky@gmail.com',          'username'=>'rizky_r',      'phone'=>'6286677889900'],
            ['name'=>'Dewi Lestari',    'email'=>'dewi.lestari@gmail.com',   'username'=>'dewi_lestari', 'phone'=>'6287788990011'],
            ['name'=>'Hendra Wijaya',   'email'=>'hendra.w@gmail.com',       'username'=>'hendra_w',     'phone'=>'6288899001122'],
            ['name'=>'Fitri Handayani', 'email'=>'fitri.h@gmail.com',        'username'=>'fitri_h',      'phone'=>'6281100112233'],
            ['name'=>'Agus Supriadi',   'email'=>'agus.s@gmail.com',         'username'=>'agus_s',       'phone'=>'6281111223344'],
            ['name'=>'Lina Marlina',    'email'=>'lina.m@gmail.com',         'username'=>'lina_m',       'phone'=>'6281222334455'],
        ];
        foreach ($users as $u) {
            User::create(array_merge($u, [
                'role' => 'user',
                'password' => Hash::make('user123'),
                'email_verified_at' => now(),
            ]));
        }

        // =============================================
        // CATEGORIES
        // =============================================
        $categories = [
            ['name'=>'Hotel',     'slug'=>'hotel',     'icon'=>'building'],
            ['name'=>'Villa',     'slug'=>'villa',     'icon'=>'home'],
            ['name'=>'Resort',    'slug'=>'resort',    'icon'=>'sun'],
            ['name'=>'Kosan',     'slug'=>'kosan',     'icon'=>'door-open'],
            ['name'=>'Kontrakan', 'slug'=>'kontrakan', 'icon'=>'house'],
        ];
        foreach ($categories as $cat) Category::create($cat);

        // =============================================
        // PROPERTIES
        // =============================================
        $properties = [
            [
                'category_id'=>1,'name'=>'Hotel Bintang Lima Jakarta','slug'=>'hotel-bintang-lima-jakarta',
                'description'=>'<p>Hotel Bintang Lima Jakarta adalah simbol kemewahan dan keanggunan di jantung ibu kota. Terletak strategis di Jl. Sudirman, hotel ini menawarkan panorama skyline Jakarta yang memukau dari setiap kamarnya.</p><p>Dengan lebih dari 200 kamar yang dirancang oleh desainer interior kelas dunia, setiap sudut hotel ini menampilkan perpaduan elegan antara sentuhan modern dan warisan budaya Indonesia.</p><p>Fasilitas hotel meliputi kolam renang infinity di lantai 30, pusat kebugaran 24 jam, spa dan wellness center bertaraf internasional, serta 4 restoran pilihan.</p>',
                'address'=>'Jl. Sudirman No. 1','city'=>'Jakarta','province'=>'DKI Jakarta',
                'price_per_night'=>850000,'total_rooms'=>50,'max_guests'=>2,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&q=80','https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80','https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80','https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80','https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','TV','Kolam Renang','Gym','Restoran','Spa','Parkir','Sarapan']),
                'status'=>'active','rating_avg'=>4.8,'rating_count'=>120,
            ],
            [
                'category_id'=>2,'name'=>'Villa Sunset Bali','slug'=>'villa-sunset-bali',
                'description'=>'<p>Villa Sunset Bali adalah surga tersembunyi di Kuta, Bali. Dengan private pool menghadap langsung ke arah matahari terbenam, villa ini menawarkan pengalaman liburan yang tak tertandingi.</p><p>Didesain dengan filosofi arsitektur Bali modern, setiap ruangan menggunakan material alami seperti batu paras, kayu jati, dan bambu. Villa seluas 500m² ini memiliki 3 kamar tidur dengan kamar mandi en-suite.</p><p>Lokasi hanya 5 menit dari Pantai Kuta, 10 menit ke Seminyak, dan 20 menit dari Bandara Ngurah Rai.</p>',
                'address'=>'Jl. Pantai Kuta No. 88','city'=>'Badung','province'=>'Bali',
                'price_per_night'=>1500000,'total_rooms'=>5,'max_guests'=>6,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&q=80','https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&q=80','https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80','https://images.unsplash.com/photo-1604999565976-8913ad2ddb7c?w=800&q=80','https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80']),
                'facilities'=>json_encode(['Private Pool','WiFi','AC','Dapur','BBQ Area','Butler','Parkir','Sarapan']),
                'status'=>'active','rating_avg'=>4.9,'rating_count'=>85,
            ],
            [
                'category_id'=>3,'name'=>'Resort Alam Bandung','slug'=>'resort-alam-bandung',
                'description'=>'<p>Resort Alam Bandung berlokasi di pegunungan Lembang, menawarkan ketenangan dan kesegaran udara pegunungan yang luar biasa. Dikelilingi kebun teh hijau dan hutan pinus.</p><p>Terdiri dari 20 villa bungalow yang tersebar di area seluas 5 hektar. Tersedia berbagai aktivitas outdoor seperti trekking, berkuda, outbound, dan flying fox.</p>',
                'address'=>'Jl. Lembang No. 45','city'=>'Bandung','province'=>'Jawa Barat',
                'price_per_night'=>650000,'total_rooms'=>20,'max_guests'=>4,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80','https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80','https://images.unsplash.com/photo-1615880484746-a134be9a6ecf?w=800&q=80','https://images.unsplash.com/photo-1622547748225-3fc4abd2cca0?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','Kolam Renang','Restoran','Spa','Taman','Parkir','Sarapan','Gym']),
                'status'=>'active','rating_avg'=>4.7,'rating_count'=>200,
            ],
            [
                'category_id'=>4,'name'=>'Kosan Premium Depok','slug'=>'kosan-premium-depok',
                'description'=>'<p>Kosan Premium Depok hadir sebagai solusi hunian modern bagi mahasiswa dan profesional muda. Berlokasi hanya 300 meter dari gerbang Universitas Indonesia.</p><p>Setiap kamar dirancang dengan konsep studio minimalis berukuran 4x5 meter, dilengkapi ranjang single premium, meja belajar ergonomis, lemari built-in, dan kamar mandi dalam.</p>',
                'address'=>'Jl. Margonda Raya No. 200','city'=>'Depok','province'=>'Jawa Barat',
                'price_per_night'=>150000,'total_rooms'=>15,'max_guests'=>1,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&q=80','https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80','https://images.unsplash.com/photo-1586105251261-72a756497a11?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','Kamar Mandi Dalam','Laundry','Parkir Motor','Dapur Bersama']),
                'status'=>'active','rating_avg'=>4.5,'rating_count'=>45,
            ],
            [
                'category_id'=>1,'name'=>'Hotel Grand Yogyakarta','slug'=>'hotel-grand-yogyakarta',
                'description'=>'<p>Hotel Grand Yogyakarta berdiri megah di Jl. Malioboro, lokasi paling ikonik di Yogyakarta. Bergaya arsitektur Jawa klasik dengan sentuhan kontemporer.</p><p>Tersedia 30 kamar dalam tipe Deluxe, Superior, dan Suite. Semua kamar ber-AC dengan dekorasi batik handmade dan kamar mandi shower.</p>',
                'address'=>'Jl. Malioboro No. 100','city'=>'Yogyakarta','province'=>'DI Yogyakarta',
                'price_per_night'=>500000,'total_rooms'=>30,'max_guests'=>2,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1496417263034-38ec4f0b665a?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1496417263034-38ec4f0b665a?w=800&q=80','https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80','https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&q=80','https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','TV','Restoran','Spa','Parkir','Sarapan','Laundry']),
                'status'=>'active','rating_avg'=>4.6,'rating_count'=>150,
            ],
            [
                'category_id'=>5,'name'=>'Kontrakan Modern Surabaya','slug'=>'kontrakan-modern-surabaya',
                'description'=>'<p>Kontrakan Modern Surabaya menawarkan konsep hunian bulanan yang nyaman dan terjangkau di kawasan strategis Surabaya Timur. Cocok untuk pasangan muda dan keluarga kecil.</p>',
                'address'=>'Jl. Raya Semampir No. 55','city'=>'Surabaya','province'=>'Jawa Timur',
                'price_per_night'=>180000,'total_rooms'=>12,'max_guests'=>3,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80','https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','Parkir','CCTV','Taman','AC','Kamar Mandi Dalam']),
                'status'=>'active','rating_avg'=>4.3,'rating_count'=>28,
            ],
            [
                'category_id'=>1,'name'=>'Hotel Santika Semarang','slug'=>'hotel-santika-semarang',
                'description'=>'<p>Hotel Santika Semarang hadir di pusat kota dengan akses mudah ke Kawasan Kota Lama dan Simpang Lima. Tersedia 45 kamar dengan pilihan Deluxe, Superior, dan Suite.</p>',
                'address'=>'Jl. Pandanaran No. 116','city'=>'Semarang','province'=>'Jawa Tengah',
                'price_per_night'=>420000,'total_rooms'=>45,'max_guests'=>2,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80','https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80','https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','TV','Sarapan','Restoran','Kolam Renang','Gym','Parkir']),
                'status'=>'active','rating_avg'=>4.5,'rating_count'=>88,
            ],
            [
                'category_id'=>2,'name'=>'Villa Puncak Hijau Bogor','slug'=>'villa-puncak-hijau-bogor',
                'description'=>'<p>Villa Puncak Hijau menawarkan kesegaran pegunungan Puncak dengan pemandangan kebun teh yang menghijau. Setiap villa dilengkapi 2 kamar tidur, ruang tamu, dapur kecil, dan teras outdoor.</p>',
                'address'=>'Jl. Raya Puncak KM 87','city'=>'Bogor','province'=>'Jawa Barat',
                'price_per_night'=>950000,'total_rooms'=>4,'max_guests'=>8,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&q=80','https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','Dapur','BBQ Area','Parkir','Taman','TV','AC']),
                'status'=>'active','rating_avg'=>4.7,'rating_count'=>52,
            ],
            [
                'category_id'=>3,'name'=>'Resort Pantai Lombok','slug'=>'resort-pantai-lombok-indah',
                'description'=>'<p>Resort Pantai Lombok berlokasi langsung di tepi pantai Senggigi dengan pasir putih bersih dan air laut biru jernih. Tersedia water sports center, diving, snorkeling, dan island hopping ke Gili Trawangan.</p>',
                'address'=>'Jl. Raya Senggigi No. 1','city'=>'Lombok','province'=>'NTB',
                'price_per_night'=>780000,'total_rooms'=>25,'max_guests'=>3,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1439130490301-25e322d88054?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1439130490301-25e322d88054?w=800&q=80','https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','Private Beach','Kolam Renang','Restoran','Diving','Snorkeling','Parkir']),
                'status'=>'active','rating_avg'=>4.8,'rating_count'=>134,
            ],
            [
                'category_id'=>4,'name'=>'Kosan Elite BSD Tangerang','slug'=>'kosan-elite-bsd-tangerang',
                'description'=>'<p>Kosan Elite BSD hadir sebagai solusi hunian premium bagi profesional muda di kawasan BSD City. Kamar berukuran 4x6 meter fully furnished dengan WiFi gigabit 24 jam dan CCTV.</p>',
                'address'=>'Jl. BSD Raya Utama No. 5','city'=>'Tangerang Selatan','province'=>'Banten',
                'price_per_night'=>200000,'total_rooms'=>20,'max_guests'=>1,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1586105251261-72a756497a11?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1586105251261-72a756497a11?w=800&q=80','https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','Kamar Mandi Dalam','Laundry','Parkir Motor','Dapur Bersama','CCTV']),
                'status'=>'active','rating_avg'=>4.4,'rating_count'=>31,
            ],
            [
                'category_id'=>1,'name'=>'Hotel Grand Makassar','slug'=>'hotel-grand-makassar',
                'description'=>'<p>Hotel Grand Makassar berdiri megah di kawasan bisnis Makassar, menawarkan kemewahan bintang empat dengan sentuhan budaya Sulawesi. Lokasi strategis dekat Pantai Losari.</p>',
                'address'=>'Jl. Jenderal Sudirman No. 24','city'=>'Makassar','province'=>'Sulawesi Selatan',
                'price_per_night'=>550000,'total_rooms'=>60,'max_guests'=>2,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80','https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80','https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','TV','Kolam Renang','Restoran','Gym','Parkir','Sarapan']),
                'status'=>'active','rating_avg'=>4.6,'rating_count'=>110,
            ],
            [
                'category_id'=>2,'name'=>'Villa Royal Malang','slug'=>'villa-royal-malang',
                'description'=>'<p>Villa Royal Malang berlokasi di Batu, Malang. Villa 3 kamar tidur dengan private pool, taman luas, dan area BBQ. Kapasitas hingga 10 orang.</p>',
                'address'=>'Jl. Raya Selecta No. 12','city'=>'Batu','province'=>'Jawa Timur',
                'price_per_night'=>1200000,'total_rooms'=>3,'max_guests'=>10,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80','https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80','https://images.unsplash.com/photo-1604999565976-8913ad2ddb7c?w=800&q=80']),
                'facilities'=>json_encode(['Private Pool','WiFi','Dapur','BBQ Area','Parkir','AC','Taman']),
                'status'=>'active','rating_avg'=>4.9,'rating_count'=>43,
            ],
            [
                'category_id'=>1,'name'=>'Hotel Aryaduta Medan','slug'=>'hotel-aryaduta-medan',
                'description'=>'<p>Hotel Aryaduta Medan adalah ikon perhotelan mewah di kota Medan. Tersedia 80 kamar dengan infinity pool di rooftop dan restoran yang menyajikan masakan Batak dan internasional.</p>',
                'address'=>'Jl. Kapten Maulana Lubis No. 8','city'=>'Medan','province'=>'Sumatera Utara',
                'price_per_night'=>680000,'total_rooms'=>80,'max_guests'=>2,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&q=80','https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&q=80','https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','AC','TV','Kolam Renang','Gym','Spa','Restoran','Parkir','Sarapan']),
                'status'=>'active','rating_avg'=>4.7,'rating_count'=>97,
            ],
            [
                'category_id'=>3,'name'=>'Resort Danau Toba','slug'=>'resort-danau-toba',
                'description'=>'<p>Resort Danau Toba menawarkan pengalaman menginap di tepi Danau Toba, danau vulkanik terbesar di dunia. 15 cottage dengan arsitektur Batak tradisional.</p>',
                'address'=>'Jl. Tuktuk Siadong No. 5','city'=>'Samosir','province'=>'Sumatera Utara',
                'price_per_night'=>590000,'total_rooms'=>15,'max_guests'=>4,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1443926818681-717d074a57af?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1443926818681-717d074a57af?w=800&q=80','https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','Restoran','Perahu','Kolam Renang','Parkir','Sarapan','AC']),
                'status'=>'active','rating_avg'=>4.8,'rating_count'=>76,
            ],
            [
                'category_id'=>5,'name'=>'Kontrakan Cozy Bekasi','slug'=>'kontrakan-cozy-bekasi',
                'description'=>'<p>Kontrakan Cozy Bekasi menawarkan hunian nyaman di kawasan Bekasi Barat. Unit 2 kamar tidur dengan ruang tamu, dapur, dan kamar mandi. Sudah dilengkapi furnitur dasar.</p>',
                'address'=>'Jl. Ahmad Yani No. 77','city'=>'Bekasi','province'=>'Jawa Barat',
                'price_per_night'=>160000,'total_rooms'=>8,'max_guests'=>4,
                'thumbnail_url'=>'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&q=80',
                'image_urls'=>json_encode(['https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&q=80','https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80']),
                'facilities'=>json_encode(['WiFi','Parkir','Kamar Mandi Dalam','Dapur']),
                'status'=>'active','rating_avg'=>4.2,'rating_count'=>19,
            ],
        ];
        foreach ($properties as $prop) Property::create($prop);

        // =============================================
        // BANK ACCOUNTS
        // =============================================
        BankAccount::create(['bank_name'=>'BCA',    'account_number'=>'1234567890','account_name'=>'NginepYuk Official']);
        BankAccount::create(['bank_name'=>'Mandiri', 'account_number'=>'0987654321','account_name'=>'NginepYuk Official']);
        BankAccount::create(['bank_name'=>'BRI',     'account_number'=>'1111222233','account_name'=>'NginepYuk Official']);

        // =============================================
        // DUMMY BOOKINGS & TESTIMONIALS
        // =============================================
        $this->seedBookings();
    }

    private function seedBookings(): void
    {
        $bookingData = [
            // [user_id, property_id, checkin_offset_days_ago, nights, rooms, status, payment_method, total_override]
            [2, 1, 60, 3, 1, 'completed',  'midtrans',      null],
            [3, 2, 55, 4, 1, 'completed',  'bank_transfer', null],
            [4, 3, 50, 2, 2, 'completed',  'midtrans',      null],
            [5, 4, 45, 7, 1, 'completed',  'bank_transfer', null],
            [6, 5, 40, 2, 1, 'completed',  'midtrans',      null],
            [7, 2, 35, 5, 1, 'completed',  'bank_transfer', null],
            [8, 1, 30, 1, 2, 'completed',  'midtrans',      null],
            [9, 3, 25, 3, 1, 'completed',  'midtrans',      null],
            [10,6, 20, 2, 1, 'completed',  'bank_transfer', null],
            [11,7, 15, 2, 1, 'completed',  'midtrans',      null],
            [12,8, 12, 3, 1, 'completed',  'bank_transfer', null],
            [2, 9, 10, 2, 1, 'completed',  'midtrans',      null],
            // Confirmed (belum selesai)
            [3, 1, 5,  2, 1, 'confirmed',  'midtrans',      null],
            [4, 2, 4,  3, 1, 'confirmed',  'bank_transfer', null],
            [5, 5, 3,  1, 2, 'confirmed',  'midtrans',      null],
            // Waiting payment
            [6, 3, 2,  2, 1, 'waiting_payment', 'bank_transfer', null],
            [7, 4, 1,  3, 1, 'waiting_payment', 'bank_transfer', null],
            // Pending
            [8, 1, 0,  1, 1, 'pending',    'midtrans',      null],
            [9, 6, 0,  2, 1, 'pending',    'bank_transfer', null],
            // Cancelled
            [10,3, 20, 1, 1, 'cancelled',  'midtrans',      null],
            [11,2, 30, 2, 1, 'cancelled',  'bank_transfer', null],
        ];

        $allUsers = User::where('role','user')->get()->keyBy('id');
        $allProperties = Property::all()->keyBy('id');
        $bookingIds = [];

        foreach ($bookingData as $idx => $bd) {
            [$userId, $propId, $daysAgo, $nights, $rooms, $status, $method] = $bd;

            $prop = $allProperties[$propId] ?? null;
            if (!$prop) continue;

            $checkin   = Carbon::now()->subDays($daysAgo)->startOfDay();
            $checkout  = $checkin->copy()->addDays($nights);
            $subtotal  = $prop->price_per_night * $nights * $rooms;
            $tax       = round($subtotal * 0.11);
            $total     = $subtotal + $tax;

            $code = 'NGINEPYUK' . strtoupper(substr(md5($idx . $userId . $propId . time()), 0, 12));

            $bData = [
                'booking_code'    => $code,
                'user_id'         => $userId,
                'property_id'     => $propId,
                'guest_name'      => $allUsers[$userId]->name ?? 'Tamu Demo',
                'guest_email'     => $allUsers[$userId]->email ?? 'demo@test.com',
                'guest_phone'     => $allUsers[$userId]->phone ?? '081234567890',
                'checkin_date'    => $checkin->format('Y-m-d'),
                'checkout_date'   => $checkout->format('Y-m-d'),
                'nights'          => $nights,
                'rooms'           => $rooms,
                'guests'          => $rooms,
                'price_per_night' => $prop->price_per_night,
                'subtotal'        => $subtotal,
                'tax_amount'      => $tax,
                'total_amount'    => $total,
                'payment_method'  => $method,
                'status'          => $status,
                'midtrans_order_id' => $method === 'midtrans' ? $code : null,
                'expired_at'      => Carbon::now()->addDays(1),
                'created_at'      => Carbon::now()->subDays($daysAgo + 1),
                'updated_at'      => Carbon::now()->subDays(max(0, $daysAgo - 1)),
            ];

            // Add timestamps based on status
            if (in_array($status, ['waiting_payment','paid_unverified','confirmed','completed'])) {
                $bData['paid_at'] = $checkin->copy()->subDays(1)->subHours(2);
                if ($method === 'bank_transfer') {
                    $bData['transfer_proof'] = null;
                    $bData['transfer_uploaded_at'] = $checkin->copy()->subDays(1)->subHour();
                }
            }
            if (in_array($status, ['confirmed','completed'])) {
                $bData['confirmed_at'] = $checkin->copy()->subDays(1);
                $bData['confirmed_by'] = 'Administrator';
            }

            $booking = Booking::create($bData);
            $bookingIds[] = ['id' => $booking->id, 'user_id' => $userId, 'prop_id' => $propId, 'status' => $status];
        }

        // =============================================
        // DUMMY TESTIMONIALS (hanya untuk completed bookings)
        // =============================================
        $testiData = [
            [2, 1, 5, 'Booking sangat mudah dan cepat! Tiket PDF langsung masuk email. Kamar hotelnya persis seperti foto. Pasti pakai NginepYuk lagi!'],
            [3, 2, 5, 'Saya sudah coba beberapa platform booking, tapi NginepYuk yang paling simple. Bayar lewat GoPay langsung confirmed. Villa Balinya amazing!'],
            [4, 3, 5, 'Resort di Bandungnya bagus banget, sesuai ekspektasi. Proses booking cuma 5 menit, bayar transfer langsung dikonfirmasi admin dalam 1 jam. Top!'],
            [5, 4, 5, 'Harga transparan, nggak ada biaya tersembunyi. Admin juga fast response di WhatsApp. Recommend banget buat yang mau cari penginapan murah tapi nyaman!'],
            [6, 5, 5, 'Pertama kali pakai NginepYuk, surprised sama kualitasnya. Hotel Grand Yogya benar-benar recommended, dekat Malioboro dan pelayanannya ramah.'],
            [7, 2, 5, 'Villa Bali-nya luar biasa! Private pool beneran ada dan bersih. Sunrise dan sunsetnya cantik banget. Harga worthit banget sama kualitasnya.'],
            [8, 1, 4, 'Overall bagus, proses booking lancar. Sedikit feedback: foto kamar bisa ditambah lebih banyak. Tapi for the price, worth it banget!'],
            [9, 3, 5, 'Resort Bandung cocok banget buat liburan keluarga. Anak-anak suka banget sama kolam renangnya. Makanan di restorannya enak, masakan Sunda autentik.'],
        ];

        // Map user_id ke booking_id yang completed
        $completedBookingMap = [];
        foreach ($bookingIds as $bi) {
            if ($bi['status'] === 'completed') {
                $completedBookingMap[$bi['user_id']] = $bi;
            }
        }

        foreach ($testiData as $t) {
            [$userId, $propId, $rating, $review] = $t;
            if (!isset($completedBookingMap[$userId])) continue;

            $bi = $completedBookingMap[$userId];
            Testimonial::create([
                'user_id'     => $userId,
                'property_id' => $propId,
                'booking_id'  => $bi['id'],
                'rating'      => $rating,
                'review'      => $review,
                'status'      => 'approved',
                'created_at'  => Carbon::now()->subDays(rand(5, 25)),
            ]);

            // Update property rating
            $avg = Testimonial::where('property_id', $propId)->where('status','approved')->avg('rating');
            $cnt = Testimonial::where('property_id', $propId)->where('status','approved')->count();
            Property::where('id', $propId)->update(['rating_avg' => round($avg, 1), 'rating_count' => $cnt + 50]);
        }
    }
}
