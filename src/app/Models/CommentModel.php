<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = "comments";
    protected $returnType = "array";

    protected $useTimestamps = true;
    protected $createdField = "created_at";
    protected $updatedField = "";

    protected $allowedFields = ["name", "text", "date"]; //date should be passed by client?

    protected $validationRules = [
        "name" => "required|valid_email|max_length[255]",
        "text" => "required|string|max_length[5000]",
        "date" => "required|string|max_length[32]",
    ];

    protected $validationMessages = [
        "name" => [
            "required" => "Укажите email.",
            "valid_email" => "Введите корректный email.",
            "max_length" => "Email не должен превышать 255 символов.",
        ],
        "text" => [
            "required" => "Текст комментария обязателен.",
            "max_length" => "Текст не должен превышать 5000 символов.",
        ],
        "date" => [
            "required" => "Укажите дату.",
            "max_length" => "Дата не должна превышать 32 символа.",
        ],
    ];

    public const SORT_FIELDS = ["id", "created_at"];
    public const SORT_DIRS = ["asc", "desc"];

    public function listSorted(string $sort, string $dir, int $perPage)
    {
        $sort = in_array($sort, self::SORT_FIELDS, true) ? $sort : "id";
        $dir = in_array(strtolower($dir), self::SORT_DIRS, true) ? $dir : "desc";

        $results = $this->orderBy($sort, $dir)->paginate($perPage);
        $pageCount = $this->pager->getPageCount();
        $current = $this->pager->getCurrentPage();

        if ($pageCount > 0 && $current > $pageCount) {
            $results = $this->orderBy($sort, $dir)->paginate($perPage, "default", $pageCount);
        }

        $this->pager->only(["sort", "dir"]);

        return $results;
    }
}
