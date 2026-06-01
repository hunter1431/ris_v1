<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MySQLService
{
    public function select(string $sql, array $bindings = []): array
    {
        return DB::select($sql, $bindings);
    }

    public function statement(string $sql, array $bindings = []): bool
    {
        return DB::statement($sql, $bindings);
    }

    public function execStoredProc(string $name, array $params = []): array
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        return DB::select("CALL {$name}({$placeholders})", $params);
    }
}
