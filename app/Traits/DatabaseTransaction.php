<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Throwable;

trait DatabaseTransaction
{
    protected function executeInTransaction(callable $callback)
    {
        DB::beginTransaction();
        
        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
