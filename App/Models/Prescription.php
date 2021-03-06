<?php

namespace App\Models;

use App\Core\Database\Database;
use PDO;

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
            'remarks' => $this->remarks,
            'status' => $this->status,
        ];

        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );
    }


    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }


    public static function findByAppointment( ClinicAppointment $appointment ): ?Prescription
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from prescriptions where appointment_id=? limit 1' );
        $statement->execute( [ $appointment->id ] );

        /** @var self $result */
        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) {
            $result->appointment = ClinicAppointment::find( $result->appointment_id );

            return $result;
        }

        return null;

    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @param string|null $status - PENDING, COMPLETED
     * @param int $clinicId - 0 for all clinic
     * @return array
     */
    public static function findBetweenDates( string $startDate, string $endDate, string $status = null, int $clinicId = 0 ): array
    {
        $db = Database::instance();

        if ( $clinicId != 0 ) {
            if ( is_null( $status ) ) {

                $statement = $db->prepare( 'select * from prescriptions 
                                                where prescription_date between :sd and :ed and 
                                                appointment_id in (select id from clinic_appointments where clinic_id = :clinicId)' );

                $statement->execute( [
                    ':sd' => $startDate,
                    ':ed' => $endDate,
                    ':clinicId' => $clinicId,
                ] );

            } else {
                $statement = $db->prepare( 'select * from prescriptions 
                                                where (prescription_date between :sd and :ed) 
                                                  and status=:status and
                                                   appointment_id in (select id from clinic_appointments where clinic_id = :clinicId)' );
                $statement->execute( [
                    ':sd' => $startDate,
                    ':ed' => $endDate,
                    ':status' => $status,
                    ':clinicId' => $clinicId,
                ] );
            }
        } else {
            if ( is_null( $status ) ) {
                $statement = $db->prepare( 'select * from prescriptions where prescription_date between :sd and :ed' );
                $statement->execute( [
                    ':sd' => $startDate,
                    ':ed' => $endDate,
                ] );

            } else {
                $statement = $db->prepare( 'select * from prescriptions where (prescription_date between :sd and :ed) and status=:status' );
                $statement->execute( [
                    ':sd' => $startDate,
                    ':ed' => $endDate,
                    ':status' => $status,
                ] );
            }
        }


        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $result->appointment = ClinicAppointment::find( $result->appointment_id );
            }
            return $results;
        }

        return [];
    }

}
