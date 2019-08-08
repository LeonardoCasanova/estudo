<?php

namespace Estoque\Model;

use Estoque\DB\Sql;
use Estoque\Model;

class Category extends Model {

    public static function listAll() {

        $sql = new Sql();
        return $sql->select("select * from tb_categories order by descategory");
    }

    public function save() {

        $sql = new Sql();

        $results = $sql->select(
            "CALL sp_categories_save(:idcategory, :descategory)",
            array(
                ":idcategory" => $this->getidcategory(),
                ":descategory" => $this->getdescategory(),
            )
        );
        $this->setData($results[0]);

        Category::updateFile();
    }

    public function get($idcategory) {

        $sql = new Sql();

        $results = $sql->select("
             SELECT * FROM tb_categories  WHERE idcategory = :idcategory;", array(
            ":idcategory" => $idcategory,
        ));

        $data = $results[0];

        $this->setData($data);
    }

    public function delete() {

        $sql = new Sql();

        $sql->query(" delete from tb_categories WHERE idcategory = :idcategory; ", array(
            ":idcategory" => $this->getidcategory(),
        ));

        Category::updateFile();
    }

    public static function updateFile(){

        $categories = Category::listAll();

        $html =[];

        foreach ($categories as $row) {
            
          array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."categories-menu.html",implode('',$html));
    }

    public function getProducts($related = true){
        $sql = new Sql();

        if($related === true){

            return $sql->select("select * from tb_products where idproduct in
            ( select a.idproduct from tb_products a inner join tb_productscategories b on a.idproduct = b.idproduct 
            WHERE b.idcategory = :idcategory)", [
                ":idcategory" => $this->getidcategory()]);
        } else {

         return   $sql->select("select * from tb_products where idproduct not in
            (select a.idproduct from tb_products a inner join tb_productscategories b on a.idproduct = b.idproduct
             WHERE b.idcategory = :idcategory)", [
            ":idcategory" => $this->getidcategory()]);

        }
    }

    public function getProductsPage($page = 1,$itemsPerPage = 3){
       
        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

      $results = $sql->select("
        select SQL_CALC_FOUND_ROWS *
        FROM tb_products a 
        inner join tb_productscategories b on a.idproduct = b.idproduct
        inner join tb_categories c on c.idcategory = b.idcategory 
        where c.idcategory = :idcategory
        limit $start, $itemsPerPage;
        ", [
            ':idcategory' => $this->getidcategory()
        ]);

        $resultTotal = $sql->select("select FOUND_ROWS() as nrtotal");

        return [
           'data'=>Product::checkList($results), 
           'total'=>(int)$resultTotal[0]["nrtotal"],
           'pages'=> ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
        ];
    }

    public function addProduct(Product $product){

     $sql = new SQL();

     $sql->select(" insert into tb_productscategories (idcategory, idproduct) values (:idcategory, :idproduct)",[

        ':idcategory'=>$this->getidcategory(),
        ':idproduct'=>$product->getidproduct()
     ]);

    }


    public function removeProduct(Product $product){

        $sql = new SQL();
   
        $sql->select("delete from tb_productscategories where idcategory = :idcategory AND idproduct = :idproduct", [   
           ':idcategory'=>$this->getidcategory(),
           ':idproduct'=>$product->getidproduct()
        ]);

        
     }

     public static function getPage($page = 1, $itemsPerPage = 10){
       
        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

      $results = $sql->select("
        select SQL_CALC_FOUND_ROWS *
        from tb_categories 
        order by descategory
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
        from tb_categories 
        where descategory like :search
        order by descategory
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
