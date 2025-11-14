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
        // Ubah struktur tabel 'plan' untuk menghapus kolom item yang dipindahkan ke tabel plan_item
        Schema::table('plan', function (Blueprint $table) {
            // Jika kolom 'jenis_barang_id' ada, hapus agar data model tidak redundan
            if (Schema::hasColumn('plan', 'jenis_barang_id')) $table->dropColumn('jenis_barang_id');
            if (Schema::hasColumn('plan', 'tipe_barang_id')) $table->dropColumn('tipe_barang_id');
            if (Schema::hasColumn('plan', 'nama_barang')) $table->dropColumn('nama_barang');
            if (Schema::hasColumn('plan', 'jumlah_barang')) $table->dropColumn('jumlah_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: kembalikan kolom-kolom yang dihapus supaya migration dapat di-rollback dengan aman
        Schema::table('plan', function (Blueprint $table) {
            $table->bigInteger('jenis_barang_id')->nullable();
            $table->bigInteger('tipe_barang_id')->nullable();
            $table->string('nama_barang', 100)->nullable();
            $table->integer('jumlah_barang')->nullable();
        });
    }
};
