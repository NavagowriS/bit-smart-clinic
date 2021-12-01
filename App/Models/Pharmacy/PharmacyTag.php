<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use App\Models\IModel;
use Exception;
use PDO;

class PharmacyTag implements IModel
{

    private const TABLE = 'pharma_tags';

    public ?int $id;
    public ?string $tag;

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

    /**
     * @param int $id
     * @return self
     */
    public static function find( int $id ): PharmacyTag
    {
        return Database::find( self::TABLE, $id, self::class );
    }

    public static function findAll( $limit = 1000, $offset = 0 ): array
    {
        return Database::findAll( self::TABLE, $limit, $offset, self::class, 'tag' );
    }

    /**
     * @throws Exception
     */
    public function insert(): int
    {

        if ( $this->tagNameExist() ) throw new Exception( 'Tag already exist' );

        return Database::insert( self::TABLE, [ 'tag' => $this->tag ] );
    }

    /**
     * @throws Exception
     */
    public function update(): bool
    {
        if ( $this->tagNameExist() ) throw new Exception( 'Tag already exist' );

        return Database::update( self::TABLE, [ 'tag' => $this->tag ], [ 'id' => $this->id ] );
    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, 'id', $this->id );
    }

    public function tagNameExist(): bool
    {

        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_tags where tag=? limit 1' );
        $statement->execute( [ $this->tag ] );

        /** @var self $result */
        $result = $statement->fetchObject( self::class );

        if ( !empty( $result ) ) return true;
        return false;

    }

    public function findAllDrugs(): array
    {
        $db = Database::instance();
        $statement = $db->prepare( 'select * from pharma_drugs_tags where tag_id = ?' );
        $statement->execute( [ $this->id ] );

        /** @var DrugsTag[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, DrugsTag::class );

        if ( !empty( $results ) ) {

            $drugs = [];

            foreach ( $results as $result ) {
                $drug = Drug::find( $result->drug_id );
                $drug->drugTags = DrugsTag::findByDrug( $drug );
                $drugs[] = $drug;
            }

            return $drugs;
        }
        return [];
    }

}
