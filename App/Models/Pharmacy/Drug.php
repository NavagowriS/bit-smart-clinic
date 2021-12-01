<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use App\Models\IModel;
use PDO;

class Drug implements IModel
{

    private const TABLE = 'pharma_drugs';

    public ?int $id;
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
            }
            return $results;
        }

        return [];
    }

    public function insert(): int
    {
        $data = [
            'drug_name' => $this->drug_name,
            'generic_name' => $this->generic_name,
            'brand_name' => $this->brand_name,
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            'drug_name' => $this->drug_name,
            'generic_name' => $this->generic_name,
            'brand_name' => $this->brand_name,
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
            }
            return $results;
        }

        return [];
    }


}
