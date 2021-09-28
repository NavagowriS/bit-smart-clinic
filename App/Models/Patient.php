<?php


namespace App\Models;


use App\Core\Database\Database;
use PDO;
use PDOException;

class Patient implements IModel
{

    private const TABLE = "patients";

    public ?int $id, $age;
    public ?string $full_name, $phone, $nic, $address, $guardian_name, $guardian_phone, $gender, $dob;
    public ?string $login_name, $login_pass;


    public static function build($array): self
    {
        $object = new self();
        foreach ($array as $key => $value) {
            $object->$key = $value;
        }
        return $object;
    }

    public static function find(int $id): ?Patient
    {
        return Database::find(self::TABLE, $id, self::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Patient[]
     */
    public static function findAll($limit = 1000, $offset = 0): array
    {
        return Database::findAll(self::TABLE, $limit, $offset, self::class, "full_name");
    }

    public function insert()
    {


        $db = Database::instance();

        $db->beginTransaction();

        try {

            $data = [
                "full_name" => $this->full_name,
                "age" => $this->age,
                "phone" => $this->phone,
                "nic" => $this->nic,
                "address" => $this->address,
                "guardian_name" => $this->guardian_name,
                "guardian_phone" => $this->guardian_phone,
                "gender" => $this->gender,
            ];

            if (!empty($this->dob)) {
                $data["dob"] = $this->dob;
            }

            $id = Database::insert(self::TABLE, $data);


            /* generate unique username and password */
            $patient = self::find($id);

            $pass = substr(md5(microtime() . rand()), 0, 6);

            $data = [
                "login_name" => $patient->generateLoginName(),
                "login_pass" => $pass,
            ];

            Database::update(self::TABLE, $data, ["id" => $id]);

            $db->commit();

            return $id;
        } catch (PDOException $exception) {
            $db->rollBack();
        }

        return -1;
    }

    public function update(): bool
    {

        $pass = substr(md5(microtime() . rand()), 0, 6);

        $data = array(
            "full_name" => $this->full_name,
            "age" => $this->age,
            "phone" => $this->phone,
            "nic" => $this->nic,
            "address" => $this->address,
            "guardian_name" => $this->guardian_name,
            "guardian_phone" => $this->guardian_phone,
            "gender" => $this->gender,
            "dob" => $this->dob,
            "login_pass" => empty($this->login_pass) ? $pass : $this->login_pass,
            "login_name" => $this->generateLoginName(),
        );


        return Database::update(self::TABLE, $data, ["id" => $this->id]);
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }


    private function generateLoginName(): string
    {

        $output = "";

        $id = $this->id;
        $name = str_replace(" ", "", $this->full_name);

        if (strlen($name) > 5) {
            $output = sprintf("%s%s", $id, substr($name, 0, 5));
        } else {
            $output = sprintf("%s%s", $id, $name);
        }

        return strtoupper($output);
    }

    /**
     * @param string $keyword
     * @return self[]
     */
    public static function search(string $keyword): array
    {

        $db = Database::instance();
        $statement = $db->prepare("select * from patients where 
                             full_name like :keyword or
                             nic like :keyword or phone like :keyword");

        $statement->execute([
            ":keyword" => "%" . $keyword . "%"
        ]);

        $results = $statement->fetchAll(PDO::FETCH_CLASS, self::class);

        if (!empty($results)) return $results;
        return [];
    }


    public static function loginPatient(string $username, string $password)
    {
        $db = Database::instance();
        $statement = $db->prepare('SELECT * from patients where login_name=? and login_pass=?');

        $statement->execute([$username, $password]);

        /** @var self $result */
        $result = $statement->fetchObject(self::class);

        if (!empty($result)) {
            return $result;
        }

        return null;
    }

    


}
