<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class Helper
{
    public function __construct() {
        //
    }

    /**
     * Function decorator to make atomic transactions
     * 
     * @param Function $callback
     * @return void
     */
    public static function atomic_transaction ($callback) {
        try {
            DB::beginTransaction();
            $callback();
            DB::commit();
        } catch (\PDOException $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
