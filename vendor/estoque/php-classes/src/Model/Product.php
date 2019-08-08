<?php

namespace Estoque\Model;
use Estoque\DB\Sql;
use Estoque\Model;
use Faker\DefaultGenerator;
use Faker\Factory;

class Product extends Model {

    public static function listProducts() {

        $sql = new Sql();

        $results = $sql->select("select * from tb_products order by desproduct");

        return  $results;

    }

    public static function listProductsId($idproduct) {

        $sql = new Sql();

        $results = $sql->select("select * from tb_products where idproduct = :idproduct", array(
         ':idproduct'=> $idproduct   
        ));

        return  $results;

    }

    public static function checkList($list) {

        foreach ($list as &$row) {

            $p = new Product();
            $p->setData($row);
            $row = $p->getValues();

        }

        return $list;
    }
    

    public function save() {

        $sql = new Sql();

       

        $results = $sql->select(
            "CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlqtde, :desurl)",

            array(
                ":idproduct" => $this->getidproduct(),
                ":desproduct" => $this->getdesproduct(),
                ":vlprice" => $this->getvlprice(), 
                ":vlqtde" => $this->getvlqtde(),   
                ":desurl" => $this->getdesurl(),
            )
        );

        $this->setData($results[0]);

    }



    public function geraMassa() {

        $sql = new Sql();

        $faker = Factory::create();
     
     for ($i=0; $i <=1 ; $i++) { 

        $results = $sql->select(
            "CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlqtde, :desurl)",

            array(
                ":idproduct" => '',
                ":desproduct" => $faker->name,
                ":vlprice" =>$faker->randomNumber(4), 
                ":vlqtde" =>$faker->randomNumber(1),   
                ":desurl" =>'',
            )
        );

        $this->setData($results[0]);
     }
       

    }

    public function deleteMassa() {

        $sql = new Sql();

        $sql->query(" delete from tb_products  WHERE desproduct = 'Teste'; ", array(
            ":idproduct" => $this->getidproduct(),
        ));

    }
    public function get($idproduct) {

        $sql = new Sql();

        $results = $sql->select("
             SELECT * FROM tb_products  WHERE idproduct= :idproduct;", array(
            ":idproduct" => $idproduct,
        ));

        $data = $results[0];

        $this->setData($data);
    }

    public function delete() {

        $sql = new Sql();

        $sql->query(" delete from tb_products  WHERE idproduct= :idproduct; ", array(
            ":idproduct" => $this->getidproduct(),
        ));
    }

    

    public function getFromURL($desurl){

        $sql = new Sql();

        $rows = $sql->select("select * from tb_products  WHERE desurl = :desurl limit 1", [
            ":desurl" => $desurl
        ]);

        $this->setData($rows[0]);
    }


    public function getCategories(){

        $sql = new Sql();

        return $sql->select("select * from  tb_categories  a inner join tb_productscategories b on 
          a.idcategory = b.idcategory where b.idproduct = :idproduct ", [
            ":idproduct" => $this->getidproduct()
        ]);      
    }

    public static function getPage($page = 1, $itemsPerPage = 20){
       
        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

      $results = $sql->select("
        select SQL_CALC_FOUND_ROWS *
        from tb_products order by desproduct
        limit $start, $itemsPerPage;
        ");

        $resultTotal = $sql->select("select FOUND_ROWS() as nrtotal");

        return [
           'data'=>$results, 
           'total'=>(int)$resultTotal[0]["nrtotal"],
           'pages'=> ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
        ];
    }


    public static function getPageSearch($search, $page = 1, $itemsPerPage = 10){
       
        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

      $results = $sql->select("
        select SQL_CALC_FOUND_ROWS *
        from tb_products 
        where desproduct like :search or idproduct like :search
        order by desproduct
        limit $start, $itemsPerPage;
        ",[
            ':search'=>'%'.$search.'%'
        ]);

        $resultTotal = $sql->select("select FOUND_ROWS() as nrtotal");

        return [
           'data'=>$results, 
           'total'=>(int)$resultTotal[0]["nrtotal"],
           'pages'=> ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
        ];
    }
}
