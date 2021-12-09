<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use App\Models\IModel;
use Exception;
use PDO;
use stdClass;

class Drug implements IModel
{

    private const TABLE = 'pharma_drugs';

    public ?int $id, $total_count, $min_quantity;
    public ?string $drug_name, $generic_name, $brand_name;

    /** @var DrugsTag[] */
    public ?array $drugTags;

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


    public static function find( int $id ): ?Drug
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->total_count = Drug::getCount( $result );
            return $result;
        }
        return null;
    }

    public static function findAll( $limit = 1000, $offset = 0 ): array
    {
        /** @var self[] $results */
        $results = Database::findAll( self::TABLE, $limit, $offset, self::class, 'drug_name' );

        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $result->drugTags = DrugsTag::findByDrug( $result );
                $result->total_count = Drug::getCount( $result );
            }
            return $results;
        }

        return [];
    }

    /**
     * @throws Exception
     */
    public function insert(): int
    {

        if ( $this->exists() ) throw new Exception( 'Drug already exist' );

        $data = [
            'drug_name' => $this->drug_name,
            'generic_name' => $this->generic_name,
            'brand_name' => $this->brand_name,
            'min_quantity' => $this->min_quantity,
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            'drug_name' => $this->drug_name,
            'generic_name' => $this->generic_name,
            'brand_name' => $this->brand_name,
            'min_quantity' => $this->min_quantity,
        ];

        return Database::update( self::TABLE, $data, [ 'id' => $this->id ] );
    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }

    public static function search( string $keyword ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_drugs where drug_name like :q or generic_name like :q or brand_name like :q' );

        $statement->execute( [ ':q' => '%' . $keyword . '%' ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) {
            foreach ( $results as $result ) {
                $result->drugTags = DrugsTag::findByDrug( $result );
                $result->total_count = Drug::getCount( $result );
            }
            return $results;
        }

        return [];
    }

    /**
     * Get the total available count for the drug,
     * this will subtract count from prescription too
     * @param Drug $drug
     * @return int
     */
    public static function getCount( Drug $drug ): int
    {


        $totalPurchased = 0;
        $totalDispensed = 0;

        $db = Database::instance();

        /* get total purchased drugs */
        $statement = $db->prepare( 'select sum(quantity) as total from pharma_drug_po where drug_id=?' );

        $statement->execute( [ $drug->id ] );
        $result = $statement->fetchObject( stdClass::class );

        if ( !empty( $result->total ) ) $totalPurchased = $result->total;


        /* get total dispensed drugs count */
        $statement = $db->prepare( 'select sum(total_count) as total from prescription_items where drug_id=?
                                    and prescription_id in (select id from prescriptions where status = "COMPLETED")' );

        $statement->execute( [ $drug->id ] );
        $result = $statement->fetchObject( stdClass::class );

        if ( !empty( $result->total ) ) $totalDispensed = $result->total;


        return $totalPurchased - $totalDispensed;

    }


    public function exists(): bool
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_drugs where drug_name = ? limit 1' );

        $statement->execute( [ $this->drug_name ] );

        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) return true;
        return false;

    }

}
