<?php

namespace App\Models;

use App\Core\Database\Database;
use PDO;

class ClinicVisitPatient implements IModel
{

    private const TABLE = 'clinic_visit_patients';

    public ?int $id, $clinic_patient_id, $clinic_visit_id, $token_number;
    public ?string $status, $doctor_remarks;
    public ?int $param_weight, $param_sbp, $param_dbp, $param_blood_sugar;

    public ?string $visit_date; // from clinic_visits (join table column)

    public ?float $param_height;

    public ?ClinicVisit $clinic_visit;
    public ?ClinicPatient $clinic_patient;
    /**
     * @var ClinicVisitPatient[]|array
     */
    public ?array $clinic_details;


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
     * @return ClinicVisitPatient|null
     */
    public static function find( int $id ): ?ClinicVisitPatient
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->clinic_patient = ClinicPatient::find( $result->clinic_patient_id );
            $result->clinic_visit = ClinicVisit::find( $result->clinic_visit_id );



            return $result;
        }

        return null;

    }

    /**
     * @param ClinicVisit $clinicVisit
     * @return self[]
     */
    public static function findByClinicVisit( ClinicVisit $clinicVisit ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_visit_patients where clinic_visit_id = ?' );
        $statement->execute( [ $clinicVisit->id ] );

        /** @var self[] $result */
        $result = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $result ) ) {
            foreach ( $result as $clinicVisitPatient ) {
                $clinicVisitPatient->clinic_patient = ClinicPatient::find( $clinicVisitPatient->clinic_patient_id );
                $clinicVisitPatient->clinic_visit = ClinicVisit::find( $clinicVisitPatient->clinic_visit_id );
            }
            return $result;
        }

        return [];
    }

    public static function findAll( $limit = 1000, $offset = 0 )
    {
        // TODO: Implement findAll() method.
    }


    /**
     * @return int
     */
    public function insert(): int
    {

        $token_number = $this->getLastVisitTokenNumber() + 1;

        $data = [
            'clinic_patient_id' => $this->clinic_patient_id,
            'clinic_visit_id' => $this->clinic_visit_id,
            'token_number' => $token_number,
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
     * @return int
     */
    public function getLastVisitTokenNumber(): int
    {

        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_visit_patients where clinic_visit_id = ? order by token_number desc limit 1' );
        $statement->execute( [ $this->clinic_visit_id ] );

        /** @var self $result */
        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) {
            return (int)$result->token_number;
        }

        return 0;
    }


    /**
     * Checks if the patient already exist in the visit
     * @return bool
     */
    public function alreadyExist(): bool
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from clinic_visit_patients where clinic_visit_id=? and clinic_patient_id=? limit 1' );

        $statement->execute( [ $this->clinic_visit_id, $this->clinic_patient_id ] );

        /** @var self $result */
        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) return true;
        return false;

    }




}
