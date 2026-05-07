<?php

namespace App\Controllers;

use App\Models\CommentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Comments extends BaseController
{
    private const PER_PAGE = 3;

    private CommentModel $model;

    public function __construct()
    {
        $this->model = new CommentModel();
    }

    public function index()
    {
        $params = $this->extractListParams();

        return view("comments/index", [
            "comments" => $this->model->listSorted($params["sort"], $params["dir"], self::PER_PAGE),
            "pager" => $this->model->pager,
            "sort" => $params["sort"],
            "dir" => $params["dir"],
        ]);
    }

    public function list(): ResponseInterface
    {
        $params = $this->extractListParams();

        $comments = $this->model->listSorted($params["sort"], $params["dir"], self::PER_PAGE);

        $html = view("comments/_list", [
            "comments" => $comments,
            "pager" => $this->model->pager,
            "sort" => $params["sort"],
            "dir" => $params["dir"],
        ]);

        return $this->response->setJSON([
            "success" => true,
            "html" => $html,
            "sort" => $params["sort"],
            "dir" => $params["dir"],
            "page" => $this->model->pager->getCurrentPage(),
            "page_count" => $this->model->pager->getPageCount(),
        ]);
    }

    public function create(): ResponseInterface
    {
        $data = [
            "name" => trim((string) $this->request->getPost("name")),
            "text" => trim((string) $this->request->getPost("text")),
            "date" => trim((string) $this->request->getPost("date")),
        ];

        if (!$this->model->insert($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                "success" => false,
                "errors" => $this->model->errors(),
                "csrf_hash" => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            "success" => true,
            "message" => "Комментарий добавлен.",
            "csrf_hash" => csrf_hash(),
        ]);
    }

    public function delete(int $id): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->response->setStatusCode(404)->setJSON([
                "success" => false,
                "message" => "Комментарий не найден.",
                "csrf_hash" => csrf_hash(),
            ]);
        }

        $this->model->delete($id);

        return $this->response->setJSON([
            "success" => true,
            "message" => "Комментарий удалён.",
            "csrf_hash" => csrf_hash(),
        ]);
    }

    private function extractListParams(): array
    {
        $sort = (string) $this->request->getGet("sort");
        $dir = (string) $this->request->getGet("dir");

        return [
            "sort" => in_array($sort, CommentModel::SORT_FIELDS, true) ? $sort : "id",
            "dir" => in_array(strtolower($dir), CommentModel::SORT_DIRS, true) ? strtolower($dir) : "desc",
        ];
    }
}
