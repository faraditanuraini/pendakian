<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTransactionsColumns extends Migration
{
    public function up()
    {
        $fields = [
            'nama_ketua' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'jumlah_anggota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'status_kehadiran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ];

        // Tambahkan kolom nama_ketua, jumlah_anggota, dan status_kehadiran ke tabel transactions
        $this->forge->addColumn('transactions', $fields);
    }

    public function down()
    {
        // Hapus kolom yang ditambahkan
        $this->forge->dropColumn('transactions', ['nama_ketua', 'jumlah_anggota']);
    }
}
