<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ServiceResponseTrait;

trait DatabaseTransactionTrait
{
    use ServiceResponseTrait;

    protected function executeTransaction(callable $callback, ?string $notFoundMessage = null)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return $this->notFoundErrorResponse($e, $notFoundMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return $this->productionErrorResponse($e);
        }
    }
}
