<?php


namespace App\Models;


use App\Core\Database\Database;
use PDO;
use PDOException;

class Doctor implements IModel
{

    private const TABLE = 'doctors';

    public ?int $id, $speciality_id, $user_id;
    public ?string $name, $email, $nic, $phone, $dob;

    public ?DoctorSpeciality $doctor_speciality;
    public ?User $user;

    /**
     * 
     * @var null|Clinic[]
     */
    public ?array $clinics;

    public static function build($array): self
    {

        $object = new self();
        foreach ($array as $key => $value) {
            $object->$key = $value;
        }
        return $object;
    }

    /**
     * @param int $id
     * @return Doctor
     */
    public static function find(int $id): ?Doctor
    {
        /** @var Doctor $result */
        $result = Database::find(self::TABLE, $id, self::class);

        if (!empty($result)) {
            $result->doctor_speciality = DoctorSpeciality::find($result->speciality_id);

            if (!empty($result->user_id)) {
                $result->user = User::find($result->user_id);
                unset($result->user->password_hash);
            }

            return $result;
        }

        return null;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Doctor[]
     */
    public static function findAll($limit = 1000, $offset = 0): array
    {
        /** @var Doctor[] $results */
        $results = Database::findAll(self::TABLE, $limit, $offset, self::class, 'name');

        if (!empty($results)) {

            $output = [];

            foreach ($results as $doctor) {
                $doctor->doctor_speciality = DoctorSpeciality::find($doctor->speciality_id);
                $output[] = $doctor;
            }

            return $output;
        }

        return [];
    }

    /**
     * @return int
     */
    public function insert(): int
    {

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nic' => $this->nic,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'speciality_id' => $this->speciality_id,
        ];

        return Database::insert(self::TABLE, $data);
    }

    public function update(): bool
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nic' => $this->nic,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'speciality_id' => $this->speciality_id,
            'user_id' => $this->user_id,
        ];

        return Database::update(self::TABLE, $data, ['id' => $this->id]);
    }

    public function delete(): bool
    {
        return Database::delete(self::TABLE, 'id', $this->id);
    }


    /**
     * @param int $associatedUserId 
     * @return null|Doctor 
     * @throws PDOException 
     */
    public static function findByAssociatedUser(int $associatedUserId): ?self
    {
        $db = Database::instance();
        $statement = $db->prepare('SELECT * FROM doctors WHERE user_id=?');
        $statement->execute([$associatedUserId]);

        /**
         * @var self $result
         */
        $result = $statement->fetchObject(self::class);

        if (!empty($result)) {
            $result->doctor_speciality = DoctorSpeciality::find($result->speciality_id);
            $result->clinics = Clinic::findByDoctor($result->id);
            return $result;
        }
        return null;
    }
}
