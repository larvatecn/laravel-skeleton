<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

/**
 * Class RegionSeeder
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RegionSeeder extends Seeder
{
    /**
     * @var int
     */
    protected $dataChunk = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_file = database_path('data/region.json');
        $json = file_get_contents($json_file);
        $data = json_decode($json, true);
        $ds = [];
        // counters
        $counter = 0;
        foreach ($data as $key => $value) {
            if ($counter % $this->dataChunk == 0) {
                Region::insert($ds);
                $ds = [];
            }
            $ds[] = $value;
            $counter++;
        }
    }
}
