<?php

namespace App\Models;

use App\Core\Database\Database;
use App\Models\Pharmacy\Drug;

class PrescriptionItem implements IModel
{

    private const TABLE = 'prescription_items';

    public ?int $id, $prescription_id, $drug_id, $dose, $period, $total_count;
    public ?string $remarks;

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

        $this->total_count = $this->dose * $this->period;

        $data = [
            'prescription_id' => $this->prescription_id,
            'drug_id' => $this->drug_id,
            'dose' => $this->dose,
            'period' => $this->period,
            'remarks' => $this->remarks,
            'total_count' => $this->total_count,
        ];
        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $this->total_count = $this->dose * $this->period;

        $data = [
            'dose' => $this->dose,
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
}
