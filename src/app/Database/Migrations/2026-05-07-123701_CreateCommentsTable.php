<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "text" => [
                "type" => "TEXT",
            ],
            "date" => [
                "type" => "TIMESTAMP",
                "default" => new \CodeIgniter\Database\RawSql("CURRENT_TIMESTAMP"),
            ],
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("date");
        $this->forge->createTable("comments");
    }

    public function down(): void
    {
        $this->forge->dropTable("comments");
    }
}
