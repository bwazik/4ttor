<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ServiceResponseTrait;

trait DatabaseTransactionTrait
{
    use ServiceResponseTrait;

    protected function executeTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return $this->productionErrorResponse($e);
        }
    }
}
