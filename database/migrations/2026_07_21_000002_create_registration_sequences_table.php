<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 10)->unique();
            $table->bigInteger('last_number')->default(0);
            $table->timestamps();
        });

        $maxCustomer = DB::table('customers')
            ->whereNotNull('uuid')
            ->where('uuid', 'like', 'CSTMR%')
            ->orderBy('uuid', 'desc')
            ->value('uuid');

        $maxPendaftaran = DB::table('pendaftaran')
            ->whereNotNull('kode')
            ->where('kode', 'like', 'CSTMR%')
            ->orderBy('kode', 'desc')
            ->value('kode');

        $maxNumber = 0;
        foreach ([$maxCustomer, $maxPendaftaran] as $code) {
            if ($code && preg_match('/\d+/', $code, $m)) {
                $num = (int) $m[0];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        DB::table('registration_sequences')->insert([
            'prefix'      => 'CSTMR',
            'last_number' => $maxNumber,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_sequences');
    }
};
