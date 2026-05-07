<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = "comments";

    protected $allowedFields = ["name", "text", "date"]; //date should be passed by client?
}
