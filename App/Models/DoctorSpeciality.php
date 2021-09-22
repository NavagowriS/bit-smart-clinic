<?php

namespace App\Models;

use App\Core\Database\Database;

class DoctorSpeciality implements IModel
{
    private const TABLE = 'doctor_speciality';

    public ?int $id;
    public ?string $speciality;


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
     * @return DoctorSpeciality
     */
    public static function find(int $id): ?DoctorSpeciality
    {
        return Database::find(self::TABLE, $id, self::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return DoctorSpeciality[]
     */
    public static function findAll($limit = 1000, $offset = 0): array
    {
        return Database::findAll(self::TABLE, $limit, $offset, self::class, 'speciality');
    }

    /**
     * @return bool|int|null
     */
    public function insert()
    {

        $data = [
            'speciality' => $this->speciality,
        ];

        return Database::insert(self::TABLE, $data);
    }

    public function update(): bool
    {

        $data = [
            'speciality' => $this->speciality,
        ];

        return Database::update(self::TABLE, $data, ['id' => $this->id]);
    }

    public function delete(): bool
    {
        return Database::delete(self::TABLE, 'id', $this->id);
    }

}
