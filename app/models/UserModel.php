<?php
namespace app\models;
use \DataBase;
use \Model;

class UserModel extends Model
{
    protected $table = "usuarios";
    protected $primaryKey = "id";
    protected $secundaryKey = "email";

    // Propiedades del modelo (evita asignaciones dinámicas no reconocidas)
    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $fechaNac;
    public $tipoUser;
    public $telefono;
    public $email;
    public $pass;
    public $activo; // Asumiendo que el campo 'activo' existe en la tabla

    public static function findEmail($email) {
        $model = new static();
        $sql = "SELECT * FROM {$model->table} WHERE {$model->secundaryKey} = :email";
        $params = ["email" => $email];

        $db = \DataBase::connection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $data = $stmt->fetch();

        if ($data) {
            $model = new static();
            foreach ($data as $key => $value) {
                if (property_exists($model, $key)) {
                    $model->$key = $value;
                }
            }
            return $model;
        }
        return false;

    }


    public static function emailExists($email){
        $model = new static();
        $sql = "SELECT COUNT(*) as cantidad FROM " . $model->table . " WHERE email = :email";
        $params = ['email' => $email];
        $result = DataBase::query($sql, $params);

       return isset($result[0]->cantidad) && $result[0]->cantidad > 0;
    }

    public static function dniExists($dni) {
        $model = new static();
        $sql = "SELECT COUNT(*) as cantidad FROM {$model->table} WHERE dni = :dni";
        $params = ['dni' => $dni];
        $result = DataBase::query($sql, $params);

       return isset($result[0]->cantidad) && $result[0]->cantidad > 0;
    }

    public static function getPassword($email){
        $model = new static();
        $sql = "SELECT pass FROM {$model->table} WHERE {$model->secundaryKey} = :email";
        $params = ["email" => $email];
        $result = DataBase::query($sql, $params);

        return $result[0]['pass'] ?? false;
    }

    public static function createUser($data) {
        $db = DataBase::connection();
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, dni, fechaNac, tipoUser, telefono, email, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['dni'],
            $data['fechaNac'],
            $data['tipoUser'],
            $data['telefono'],
            $data['email'],
            $data['pass'],
        ]);
    }

    public static function getAll() {
        $model = new static();
        $sql = "SELECT * FROM {$model->table} ORDER BY apellido ASC";
        return DataBase::query($sql);
    }

    public static function buscarPor($campo, $valor) {
        $model = new static();
        $permitidos = ['apellido', 'dni', 'tipoUser'];
        if (!in_array($campo, $permitidos)) return [];

        $sql = "SELECT * FROM {$model->table} WHERE $campo LIKE :valor ORDER BY apellido ASC";
        $params = ['valor' => ($campo === 'dni') ? $valor : "%$valor%"];
        return DataBase::query($sql, $params);
    }

    public static function eliminarPorId($id) {
        $model = new static();
        $sql = "DELETE FROM {$model->table} WHERE id = :id";
        $params = ['id' => $id];
        return DataBase::query($sql, $params);
    }

    public static function getById($id) {
        $model = new static();
        $sql = "SELECT * FROM {$model->table} WHERE id = :id";
        $params = ['id' => $id];
        $result = DataBase::query($sql, $params);

        if ($result && count($result) > 0) {
            foreach ($result[0] as $key => $value) {
                if (property_exists($model, $key)) {
                    $model->$key = $value;
                }
            }
            return $model;
        }
        return false;
    }

    public static function listarTodos() {
        $sql = "SELECT * FROM usuarios ORDER BY apellido ASC";
        return DataBase::query($sql);
    }

    public static function buscarUsuarios($filtro, $tipoUser) {
        $sql = "SELECT * FROM usuarios WHERE 1";
        $params = [];

        if (!empty($filtro)) {
            $sql .= " AND (dni LIKE :filtro OR apellido LIKE :filtro)";
            $params['filtro'] = "%$filtro%";
        }

        if (!empty($tipoUser) && in_array($tipoUser, ['medico', 'paciente'])) {
            $sql .= " AND tipoUser = :tipoUser";
            $params['tipoUser'] = $tipoUser;
        }

        $sql .= " ORDER BY apellido ASC";
        return DataBase::query($sql, $params);
    }
}