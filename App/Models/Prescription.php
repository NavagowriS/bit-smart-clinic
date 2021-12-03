<?php

namespace App\Models;

use App\Core\Database\Database;

class Prescription implements IModel
{

    private const TABLE = 'prescriptions';

    public ?int $id, $appointment_id;
    public ?string $prescription_date, $remarks, $status;

    public ?ClinicAppointment $appointment;

    public static function build( $array ): self
    {
        $object = new self();
        foreach ( $array as $key => $value ) {
            $object->$key = $value;
        }
        return $object;
    }


    public static function find( int $id ): ?Prescription
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->appointment = ClinicAppointment::find( $result->appointment_id );

            return $result;
        }

        return null;
    }

    public static function findAll( $limit = 1000, $offset = 0 )
    {
        // TODO: Implement findAll() method.
    }

    public function insert(): int
    {
        $data = [
            'appointment_id' => $this->appointment_id,
            'prescription_date' => $this->prescription_date,
            'remarks' => '',
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            'remarks' => '',
        ];

        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );
    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }
}
