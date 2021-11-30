<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use Exception;

class DrugsTag implements \App\Models\IModel
{


    private const TABLE = 'pharma_drugs_tags';

    public ?int $id, $drug_id, $tag_id;

    public ?Drug $drug;
    public ?PharmacyTag $tag;

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

    public static function find( int $id ): ?DrugsTag
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->drug = Drug::find( $result->drug_id );
            $result->tag = PharmacyTag::find( $result->tag_id );

            return $result;
        }

        return null;

    }

    public static function findAll( $limit = 1000, $offset = 0 )
    {
        // TODO: Implement findAll() method.
    }

    /**
     * @throws Exception
     */
    public function insert(): int
    {

        if ( $this->exists() ) throw new Exception( 'Tag already assigned' );

        $data = [
            'drug_id' => $this->drug_id,
            'tag_id' => $this->tag_id,
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }

    public static function findByDrug( Drug $drug ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_drugs_tags where drug_id=?' );
        $statement->execute( [ $drug->id ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( \PDO::FETCH_CLASS, self::class );

        if ( !empty( $results ) ) {

            foreach ( $results as $result ) {
                $result->drug = Drug::find( $result->drug_id );
                $result->tag = PharmacyTag::find( $result->tag_id );
            }

            return $results;
        }
        return [];

    }

    public function exists(): bool
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_drugs_tags where drug_id=? and tag_id=? limit 1' );
        $statement->execute( [ $this->drug_id, $this->tag_id ] );

        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) return true;
        return false;

    }

}
