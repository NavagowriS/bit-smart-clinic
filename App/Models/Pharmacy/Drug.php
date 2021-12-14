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

    public static function getDrugDispenseStats( string $startDate, string $endDate, int $clinicId = 0 ): array
    {
        $db = Database::instance();

        if ( $clinicId == 0 ) {
            $statement = $db->prepare(
                "SELECT tpi.drug_id as drug_id, tpd.drug_name as drug_name, sum(total_count) as total
                FROM prescription_items tpi
                         INNER JOIN pharma_drugs tpd
                                    ON tpi.drug_id = tpd.id
                WHERE prescription_id in (
                    SELECT id
                    FROM prescriptions
                    WHERE prescription_date BETWEEN :sd AND :ed
                      AND STATUS = 'COMPLETED'
                )
                GROUP BY drug_id;" );

            $statement->execute( [
                ':sd' => $startDate,
                ':ed' => $endDate,
            ] );

        } else {
            $statement = $db->prepare(
                "SELECT tpi.drug_id as drug_id, tpd.drug_name as drug_name, sum(total_count) as total
                FROM prescription_items tpi
                         INNER JOIN pharma_drugs tpd
                                    ON tpi.drug_id = tpd.id
                WHERE prescription_id in (
                    SELECT id
                    FROM prescriptions
                    WHERE prescription_date BETWEEN :sd AND :ed
                      AND STATUS = 'COMPLETED'
                      AND appointment_id IN (
                        SELECT id
                        FROM clinic_appointments
                        WHERE clinic_id = :clinic_id
                    )
                )
                GROUP BY drug_id;" );

            $statement->execute( [
                ':sd' => $startDate,
                ':ed' => $endDate,
                ':clinic_id' => $clinicId,
            ] );

        }


        $output = [
            'data' => [],
            'chart_data' => [
                'labels' => [],
                'values' => [],
            ],
        ];

        /*
         * [drug_id, $total]
         */
        $results = $statement->fetchAll( PDO::FETCH_CLASS, stdClass::class );


        if ( !empty( $results ) ) {


            foreach ( $results as $result ) {
                $result->drug = Drug::find( $result->drug_id );
                $result->total = intval( $result->total );

                /* process into chart data */

                $output[ 'chart_data' ][ 'labels' ][] = $result->drug->drug_name;
                $output[ 'chart_data' ][ 'values' ][] = $result->total;
            }

            $output[ 'data' ] = $results;

        }
        return $output;

    }

}
