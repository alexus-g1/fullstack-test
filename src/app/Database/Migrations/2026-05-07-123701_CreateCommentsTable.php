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
                "type" => "VARCHAR", // still dont know why client should choose date but let it be another column.
                "constraint" => 32,
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "default" => new \CodeIgniter\Database\RawSql("CURRENT_TIMESTAMP"), //found this cool thing. Should just work.
            ],
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("created_at"); //let it be index lol.
        $this->forge->createTable("comments");
    }

    public function down(): void
    {
        $this->forge->dropTable("comments");
    }
}
