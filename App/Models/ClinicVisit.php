<?php


namespace App\Models;


use App\Core\Database\Database;
use PDO;

class ClinicVisit implements IModel
{

    private const TABLE = "clinic_visits";

    public ?int $id, $clinic_id;
    public ?string $visit_date;

    public ?Clinic $clinic;

    public static function build( $array ): self
    {

        $object = new self();
        foreach ( $array as $key => $value ) {
            $object->$key = $value;
        }
        return $object;
    }

    public static function find( int $id ): ?self
    {
        /** @var self $result */
        $result = Database::find( self::TABLE, $id, self::class );

        if ( !empty( $result ) ) {
            $result->clinic = Clinic::find( $result->clinic_id );
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
        $data = [
            "clinic_id" => $this->clinic_id,
            "visit_date" => $this->visit_date,
        ];

        return Database::insert( self::TABLE, $data );
    }

    public function update(): bool
    {
        $data = [
            "visit_date" => $this->visit_date,
        ];
        return Database::update( self::TABLE, $data, [ "id" => $this->id ] );
    }

    public function delete(): bool
    {
        return Database::delete( self::TABLE, "id", $this->id );
    }

    /**
     * @param Clinic $clinic
     * @return array
     */
    public static function getByClinic( Clinic $clinic ): array
    {
        $db = Database::instance();
        $statement = $db->prepare( "select * from clinic_visits where clinic_id = ? order by visit_date desc" );
        $statement->execute( [ $clinic->id ] );

        /** @var self[] $results */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, self::class );

        $output = [
            "clinic" => $clinic,
            "visits" => [],
        ];

        if ( !empty( $results ) ) {
            $output[ "visits" ] = $results;
        }
        return $output;
    }

    public static function getByClinicAndDate()
    {

    }

}
