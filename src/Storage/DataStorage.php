<?php
// я бы переименовал папку Storage в Repository. namespace App\Repository;
namespace App\Storage;

use App\Model;
// мне вот так по-вкусу
//use App\Model\Task;
//use App\Model\Project;

//можно создать абстрактный репозиторий и в нем заполнить pdo, чтобы не делать это у наследников
class DataStorage// class ProjectRepository
{
    private const LIMIT = 10;

    /**
     * @var \PDO 
     */
    public $pdo;
    //private \PDO $pdo;

    public function __construct()//прокинуть dsn в аргумент
    {
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user');
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId) //добавим типизации getProjectById(int $projectId): Project
    {
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId);// если бы был типизированный параметр то можно и не кастить $projectId

//        я бы так сделал
//        $stmt = $this->pdo->prepare('SELECT * FROM project WHERE id = :id');
//        $stmt->bindValue(':id', (int)$projectId, \PDO::PARAM_INT);
//        $stmt->execute();

        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row);
        }

        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id
     * @param int $limit
     * @param int $offset
     * public function getTasksByProjectId(int $project_id,int $limit = self::LIMIT,int $offset = 0):array|[]Task
     */
    public function getTasksByProjectId(int $project_id, $limit, $offset)
    {
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?");
        // $stmt = $this->pdo->prepare("SELECT * FROM task WHERE project_id = $project_id LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);

//        я бы так сделал
//        $stmt = $this->pdo->prepare('SELECT * FROM task WHERE project_id = :project_id LIMIT :limit OFFSET :offset');
//        $stmt->bindValue(':project_id', $projectId, \PDO::PARAM_INT);
//        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
//        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
//        $stmt->execute();

        $tasks = [];
        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = new Model\Task($row);
        }

        return $tasks;
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     *
     * типизация параметров и ответа
     * для строгости $data должна иметь тип Task, а то в $data может лежать всё что угодно и приведет к падению sql запроса
     */
    public function createTask(array $data, $projectId)
    {
        $data['project_id'] = $projectId;

        /**
         * не нравятся эти два перебора(ключей => значений), лучше  сделать так
         * $stmt = $this->pdo->prepare('INSERT INTO task (`project_id`, `title`, `status`) VALUES (:project_id, :title, :status)');
         * $stmt->bindValue(':project_id', $projectId, \PDO::PARAM_INT);
         * $stmt->bindValue(':title', $data->getTitle(), \PDO::PARAM_STR);
         * $stmt->bindValue(':status', $data->getStatus(), \PDO::PARAM_STR);
         * $stmt->execute();
         */
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($v) {
            return is_string($v) ? '"' . $v . '"' : $v;// двойные ковычки на одинарные
        }, $data));

        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)");
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn();// $data['id'] = $this->pdo->query('SELECT LAST_INSERT_ID()')->fetchColumn();

        return new Model\Task($data);
    }
}
