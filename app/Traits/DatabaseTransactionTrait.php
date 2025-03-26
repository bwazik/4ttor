<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
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
            return $this->productionErrorResponse($e);
        }
    }
}
