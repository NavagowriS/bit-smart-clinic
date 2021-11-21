<?php

namespace App\Models;

use App\Core\Database\Database;
use PDO;

class ClinicAppointment implements IModel
{

    private const TABLE = 'clinic_appointments';

    public ?int $id, $clinic_id, $clinic_patient_id, $token_number;
    public ?string $status, $doctor_remarks, $clinic_date;
    public ?int $param_sbp, $param_dbp, $param_blood_sugar;
    public ?float $param_weight, $param_height;

    public ?ClinicPatient $clinic_patient;
    public ?Clinic $clinic;


    /**
     * @param $array
     * @return static
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
     * @return ClinicAppointment|null
     */
    public static function find( int $id ): ?ClinicAppointment
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->clinic_patient = ClinicPatient::find( $result->clinic_patient_id );
            $result->clinic = Clinic::find( $result->clinic_id );
            return $result;
        }

        return null;

    }


    /**
     * @param int $limit
     * @param int $offset
     */
    public static function findAll( $limit = 1000, $offset = 0 )
    {
        // TODO: Implement findAll() method.
    }


    /**
     * @return int
     */
    public function insert(): int
    {
        $data = [
            'clinic_id' => $this->clinic_id,
            'clinic_patient_id' => $this->clinic_patient_id,
            'token_number' => $this->token_number,
            'clinic_date' => $this->clinic_date,
        ];

        return Database::insert( self::TABLE, $data );
    }


    /**
     * @return bool
     */
    public function update(): bool
    {
        $data = [
            'status' => $this->status,
            'param_weight' => $this->param_weight,
            'param_height' => $this->param_height,
            'param_sbp' => $this->param_sbp,
            'param_dbp' => $this->param_dbp,
            'param_blood_sugar' => $this->param_blood_sugar,
            'doctor_remarks' => $this->doctor_remarks,
        ];

        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );
    }


    /**
     * Updates the clinic visit patient's status.
     *
     * @return bool
     */
    public function updateStatus(): bool
    {
        $data = [
            'status' => $this->status,
        ];
        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );
    }


    /**
     * @return bool
     */
    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }


    /**
     * Returns all appointments for a given day
     * @param $clinicId
     * @param $clinicDate
     * @return ClinicAppointment[]|array
     */
    public static function getAppointmentsForDay( $clinicId, $clinicDate ): array
    {

        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_appointments where clinic_id = ? and clinic_date = ? order by token_number' );

        $statement->execute( [ $clinicId, $clinicDate ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) return $results;

        return [];

    }


    /**
     * @param $clinicPatientId
     * @return array
     */
    public static function getByClinicPatient( $clinicPatientId ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_appointments where clinic_patient_id=? order by clinic_date desc' );

        $statement->execute( [ $clinicPatientId ] );

        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) return $results;

        return [];

    }


    /**
     * @param Clinic $clinic
     * @return array
     */
    public static function getByClinic( Clinic $clinic ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( "select * from clinic_appointments where clinic_id = ? order by clinic_date desc" );
        $statement->execute( [ $clinic->id ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        $output = [
            "clinic" => $clinic,
            "appointments" => [],
        ];

        if ( !empty( $results ) ) {
            $output[ "appointments" ] = $results;
        }
        return $output;
    }


    /**
     * @param Clinic $clinic
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public static function getByClinicBetweenDates( Clinic $clinic, string $startDate, string $endDate, string $status ): array
    {

        $db = Database::instance();

        if ( $status === 'ALL' ) {
            $statement = $db->prepare( "select * from clinic_appointments where clinic_id = ? and (clinic_date between ? and ?) order by clinic_date desc" );
            $statement->execute( [ $clinic->id, $startDate, $endDate ] );

        } else {

            $statement = $db->prepare( "select * from clinic_appointments where clinic_id = ? and (clinic_date between ? and ?) and status = ? order by clinic_date desc" );
            $statement->execute( [ $clinic->id, $startDate, $endDate, $status ] );
        }


        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );


        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {

                $result->clinic_patient = ClinicPatient::find( $result->clinic_patient_id );

            }
        }

        $output = [
            "clinic" => $clinic,
            "appointments" => [],
        ];

        if ( !empty( $results ) ) {
            $output[ "appointments" ] = $results;
        }
        return $output;
    }


    public static function checkExist($clinic_id, $clinic_patient_id, $clinic_date): bool
    {

        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_appointments where clinic_id=? and clinic_patient_id=? and clinic_date=? limit 1');
        $statement->execute([
            $clinic_id, $clinic_patient_id, $clinic_date
        ]);


        $result = $statement->fetchObject(self::class);

        if(!empty( $result)) return true;
        return  false;

    }


}
