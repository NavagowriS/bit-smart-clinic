<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use App\Models\IModel;
use Exception;

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

    public static function find( int $id )
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

}
