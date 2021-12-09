<?php

namespace App\Models;

use App\Core\Database\Database;
use App\Models\Pharmacy\Drug;
use PDO;

class PrescriptionItem implements IModel
{

    private const TABLE = 'prescription_items';

    public ?int $id, $prescription_id, $drug_id, $dose, $period, $total_count;
    public ?string $remarks, $frequency;

    public ?Drug $drug;

    public static function build( $array ): self
    {
        $object = new self();
        foreach ( $array as $key => $value ) {
            $object->$key = $value;
        }
        return $object;
    }

    public static function find( int $id ): ?PrescriptionItem
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->drug = Drug::find( $result->drug_id );
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

        $this->total_count = 1;

        $data = [
            'prescription_id' => $this->prescription_id,
            'drug_id' => $this->drug_id,
            'dose' => 1,
            'period' => 1,
            'remarks' => '',
            'total_count' => $this->total_count,
        ];
        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            'dose' => $this->dose,
            'frequency' => $this->frequency,
            'period' => $this->period,
            'remarks' => $this->remarks,
            'total_count' => $this->total_count,
        ];
        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );
    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }

    public static function findByPrescription( Prescription $prescription ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from prescription_items where prescription_id=?' );
        $statement->execute( [ $prescription->id ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $result->drug = Drug::find( $result->drug_id );
            }
            return $results;
        }
        return [];

    }

}
