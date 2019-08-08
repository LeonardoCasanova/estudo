<?php

namespace Estoque\Model;
use Estoque\DB\Sql;
use Estoque\Model;



class Account extends Model {


    public static function listAccount($iduser) {
               
        $sql = new Sql();

        $results = $sql->select("
                   SELECT * FROM tb_conta c inner join tb_users u on u.iduser = c.iduser
                   WHERE c.iduser = :iduser;", array(
                  ":iduser" => $iduser,
        ));

       return $results[0];             

    }

    public function update() {

        $sql = new Sql();


        

        $results = $sql->select( "update tb_conta SET desidconta= :desidconta,
                                 iduser= :iduser,
                                 desnome_emp= :desnome_emp,
                                 desconta= :desconta,
                                 desagencia= :desagencia,
                                 desbanco= :desbanco,
                                 descnpj= :descnpj,
                                 desddd= :desddd,
                                 destelefone= :destelefone,
                                 desemail= :desemail where desidconta = :desidconta",
            array(
                ":desidconta" => $this->getdesidconta(),
                ":iduser" =>$this->getdesiduser(),
                ":desnome_emp" => $this->getdesnome_emp(),
                ":desconta" => $this->getdesconta(),
                ":desagencia" => $this->getdesagencia(),
                ":desbanco" => $this->getdesbanco(),
                ":descnpj" => $this->getdescnpj(),
                ":desddd" => $this->getdesddd(),
                ":destelefone" => $this->getdestelefone(),
                ":desemail" => $this->getdesemail()
            )
        );

       

    }


}