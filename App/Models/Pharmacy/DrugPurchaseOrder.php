<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use PDO;

class DrugPurchaseOrder implements \App\Models\IModel
{

    private const TABLE = 'pharma_drug_po';

    public ?int $id, $drug_id, $quantity;
    public ?string $order_date;

    public ?Drug $drug;


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


    public static function find( int $id ): ?DrugPurchaseOrder
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->drug = Drug::find( $result->drug_id );

            return $result;
        }

        return null;

    }


    public static function findByDrug( Drug $drug ): array
    {

        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_drugs_po where drug_id=?' );
        $statement->execute( [ $drug->id ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );


        if ( !empty( $results ) ) {

            foreach ( $results as $result ) {
                $result->drug = $drug;
            }

            return $results;
        }
        return [];
    }

    public static function findAll( $limit = 1000, $offset = 0 )
    {
        // TODO: Implement findAll() method.
    }

    public function insert(): int
    {
        $data = [
            'drug_id' => $this->drug_id,
            'order_date' => $this->order_date,
            'quantity' => $this->quantity,
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            'order_date' => $this->order_date,
            'quantity' => $this->quantity,
        ];

        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );

    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }
}
