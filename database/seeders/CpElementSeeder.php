<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\CpElement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CpElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $elements = [
            [
                'name' => 'Nilai Agama dan Budi Pekerti',
                'description' => 'Mengembangkan sikap spiritual, moral, dan akhlak mulia pada anak melalui pembiasaan perilaku baik, pengenalan nilai keagamaan, serta sikap saling menghargai dalam kehidupan sehari-hari.',
            ],
            [
                'name' => 'Jati Diri',
                'description' => 'Mendukung anak dalam mengenali dan menghargai dirinya sendiri, termasuk identitas, emosi, kepercayaan diri, kemandirian, serta kemampuan bersosialisasi dengan lingkungan sekitar.',
            ],
            [
                'name' => 'Dasar-dasar Literasi',
                'description' => 'Mengembangkan kemampuan awal literasi anak seperti menyimak, berbicara, mengenal simbol, huruf, dan kata, serta menumbuhkan minat terhadap membaca dan berkomunikasi.',
            ],
            [
                'name' => 'Matematika',
                'description' => 'Menstimulasi kemampuan berpikir logis dan numerik anak melalui pengenalan konsep bilangan, pola, bentuk, ukuran, serta pemecahan masalah sederhana dalam kehidupan sehari-hari.',
            ],
            [
                'name' => 'Sains, Teknologi, Rekayasa, dan Seni (STEAM)',
                'description' => 'Mengembangkan rasa ingin tahu, kreativitas, dan kemampuan berpikir kritis anak melalui eksplorasi sains, penggunaan teknologi sederhana, kegiatan rekayasa, dan ekspresi seni.',
            ],
        ];

        foreach ($elements as $element) {
            CpElement::firstOrCreate(
                ['name' => $element['name']],
                ['description' => $element['description']]
            );
        }
    }
}
