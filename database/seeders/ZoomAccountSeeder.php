<?php

namespace Database\Seeders;

use App\Models\ZoomAccount;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;

class ZoomAccountSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['zoom_accounts']);

        ZoomAccount::create([
            'teacher_id' => 1,
            'account_id' => 'd-VD4ewPT8m1Ha11_jm2jA',
            'client_id' => 'WMuMYv6WQQWM15X4VHBjuw',
            'client_secret' => '8yt9uRG7O46xDCJI8YKFx6dpDX29IF53',
        ]);
    }
}
