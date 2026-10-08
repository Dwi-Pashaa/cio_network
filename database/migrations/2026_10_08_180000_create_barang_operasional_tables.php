<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipe_barang_operasionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('nama_tipe');
            $table->boolean('has_mac_address')->default(false);
            $table->boolean('has_serial_number')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('organization_id', 'fk_tipe_bo_org')->references('id')->on('organization')->onDelete('cascade');
        });

        Schema::create('barang_operasionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('tipe_barang_id');
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');
            $table->string('merk')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('satuan')->default('Unit');
            $table->integer('total_stok')->default(0);
            $table->text('spesifikasi')->nullable();
            $table->timestamps();

            $table->foreign('organization_id', 'fk_bo_org')->references('id')->on('organization')->onDelete('cascade');
            $table->foreign('tipe_barang_id', 'fk_bo_tipe')->references('id')->on('tipe_barang_operasionals')->onDelete('cascade');
        });

        Schema::create('user_barang_operasionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('barang_operasional_id');
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->foreign('organization_id', 'fk_user_bo_org')->references('id')->on('organization')->onDelete('cascade');
            $table->foreign('user_id', 'fk_user_bo_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('barang_operasional_id', 'fk_user_bo_barang')->references('id')->on('barang_operasionals')->onDelete('cascade');
        });

        Schema::create('transfer_barang_operasionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('kode_transaksi');
            $table->unsignedBigInteger('barang_operasional_id');
            $table->unsignedBigInteger('pengirim_id')->nullable();
            $table->unsignedBigInteger('penerima_id');
            $table->integer('jumlah');
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_transfer');
            $table->timestamps();

            $table->foreign('organization_id', 'fk_trf_bo_org')->references('id')->on('organization')->onDelete('cascade');
            $table->foreign('barang_operasional_id', 'fk_trf_bo_barang')->references('id')->on('barang_operasionals')->onDelete('cascade');
            $table->foreign('pengirim_id', 'fk_trf_bo_pengirim')->references('id')->on('users')->onDelete('set null');
            $table->foreign('penerima_id', 'fk_trf_bo_penerima')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_barang_operasionals');
        Schema::dropIfExists('user_barang_operasionals');
        Schema::dropIfExists('barang_operasionals');
        Schema::dropIfExists('tipe_barang_operasionals');
    }
};
