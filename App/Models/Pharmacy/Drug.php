<?php

namespace App\Models\Pharmacy;

use App\Core\Database\Database;
use App\Models\IModel;

class Drug implements IModel
{

    private const TABLE = 'pharma_drugs';

    public ?int $id;
    public ?string $drug_name, $generic_name, $brand_name;


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
        return Database::findAll( self::TABLE, $limit, $offset, self::class, 'drug_name' );
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
}
