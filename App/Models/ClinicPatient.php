<?php


namespace App\Models;


use App\Core\Database\Database;
use PDO;

class ClinicPatient implements IModel
{

    private const TABLE = "clinic_patients";

    public ?int $id, $clinic_id, $patient_id;
    public ?string $since;

    public ?Clinic $clinic;
    public ?Patient $patient;


    /** @var ClinicAppointment[]|null */
    public ?array $appointments;


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
    public static function find( int $id ): ?ClinicPatient
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->patient = Patient::find( $result->patient_id );
            $result->clinic = Clinic::find( $result->clinic_id );

            $result->appointments = self::getAllAppointments( $id );

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
            "clinic_id" => $this->clinic_id,
            "patient_id" => $this->patient_id,
            "since" => $this->since,
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            "since" => $this->since,
        ];

        return Database::update( self::TABLE, $data, [ "id" => $this->id ] );
    }


    public function delete(): bool
    {
        return Database::delete( self::TABLE, "id", $this->id );
    }

    public static function findByClinic( Clinic $clinic ): array
    {

        $db = Database::instance();
        $statement = $db->prepare( "select * from clinic_patients where clinic_id = ?" );
        $statement->execute( [ $clinic->id ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        $output = [
            "clinic" => $clinic,
            "patients" => [],
        ];

        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $result->patient = Patient::find( $result->patient_id );
                $output[ "patients" ][] = $result;
            }
        }

        return $output;

    }

    public static function findByPatient( int $clinicId, int $patientId ): ?ClinicPatient
    {
        $db = Database::instance();
        $statement = $db->prepare( "select * from clinic_patients where clinic_id=? and patient_id=?" );
        $statement->execute( [ $clinicId, $patientId ] );

        /** @var self $result */
        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) {
            $result->patient = Patient::find( $result->patient_id );
            $result->clinic = Clinic::find( $result->clinic_id );

            return $result;
        }
        return null;

    }


//    public static function getAllClinicVisitDetails( int $clinicPatientId ): array
//    {
//
//        $db = Database::instance();
//
//        $statement = $db->prepare( 'SELECT cp.*, cv.visit_date FROM clinic_visit_patients cp
//                                        INNER JOIN clinic_visits cv ON cp.clinic_visit_id = cv.id
//                                        WHERE cp.clinic_patient_id=?;' );
//
//        $statement->execute( [ $clinicPatientId ] );
//
//        /** @var ClinicVisitPatient[] $results */
//        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );
//
//        if ( !empty( $results ) ) {
//            return $results;
//        }
//
//        return [];
//    }

    public static function getAllAppointments( int $clinicPatientId ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_appointments where clinic_patient_id=? order by clinic_date desc' );

        $statement->execute( [ $clinicPatientId ] );

        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) return $results;

        return [];
    }

    public static function getCount( Clinic $clinic ): int
    {
        return count( self::findByClinic( $clinic )['patients'] );
    }

}
