<?php


namespace App\Models;


use App\Core\Database\Database;

class Clinic implements IModel
{

    public const TABLE = "clinics";

    public ?int $id, $doctor_in_charge_id;
    public ?string $title;

    public ?Doctor $doctor_in_charge;


    /**
     * @param $array
     * @return self
     */
    public static function build( $array ): self
    {
        $object = new self();
        foreach ( $array as $key => $value ) {
            $object->$key = $value;
        }
        return $object;
    }


    /**
     * @param int $id
     * @return self|null
     */
    public static function find( int $id ): ?self
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );


        if ( !empty( $result ) ) {
            $result->doctor_in_charge = Doctor::find( $result->doctor_in_charge_id );

            return $result;

        }
        return null;
    }


    /**
     * @param int $limit
     * @param int $offset
     * @return self[]
     */
    public static function findAll( $limit = 1000, $offset = 0 ): array
    {
        /** @var self[] $results */
        $results = Database::findAll( self::TABLE, $limit, $offset, self::class, "title" );


        if ( !empty( $results ) ) {

            $output = [];

            foreach ( $results as $result ) {
                $result->doctor_in_charge = Doctor::find( $result->doctor_in_charge_id );
                $output[] = $result;
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
            "title" => $this->title,
            "doctor_in_charge_id" => $this->doctor_in_charge_id,
        ];

        return Database::insert( self::TABLE, $data );

    }


    /**
     * @return bool
     */
    public function update(): bool
    {
        $data = [
            "title" => $this->title,
            "doctor_in_charge_id" => $this->doctor_in_charge_id,
        ];

        return Database::update( self::TABLE, $data, [ "id" => $this->id ] );
    }


    /**
     * @return bool
     */
    public function delete(): bool
    {
        return Database::delete( self::TABLE, "id", $this->id );
    }
}
