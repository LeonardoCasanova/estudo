<?php

namespace Estoque\Model;

use \Estoque\DB\Sql;
use \Estoque\Model;

class Client extends Model
{

    const SUCCESS = "Ordem-Success";
    const ERROR = "Order-Error";

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("insert into tb_clients(tb_id, tb_nome_cli, tb_cpf_cli, tb_end_cli, tb_num_cli,
                         tb_compl_cli, tb_bairro_cli, tb_cidade_cli, tb_estado_cli, tb_cep_cli)
                         values (:tb_id,:tb_nome_cli,:tb_cpf_cli,:tb_end_cli,
                         :tb_num_cli,:tb_compl_cli,:tb_bairro_cli,:tb_cidade_cli,
                         :tb_estado_cli,:tb_cep_cli)", [

                        ':tb_id' => '',
                        ':tb_nome_cli' => $this->getdesname(),
                        ':tb_cpf_cli' => $this->getdescnpj(),
                        ':tb_end_cli' => $this->getdesaddress(),
                        ':tb_num_cli' => $this->getdesnumber(),
                        ':tb_compl_cli' => '',
                        ':tb_bairro_cli' => $this->getdesdistrict(),
                        ':tb_cidade_cli' => $this->getdescity(),
                        ':tb_estado_cli' => $this->getdesstate(),
                        ':tb_cep_cli' => $this->getzipcode(),
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function get($idclient)
    {

        $sql = new Sql();

        $results = $sql->select("
             SELECT * FROM tb_clients  WHERE tb_id= :tb_id;", array(
            ":tb_id" => $idclient,
        ));

        $data = $results[0];
        
        $this->setData($data);
    }

    public function delete()
    {

        $sql = new Sql();

        $sql->query("delete from  tb_clients  where tb_id = :tb_id", [
            ':tb_id' => $this->gettb_id(),
        ]);
    }

    public static function getSuccess()
    {

        $msg = (isset($_SESSION[Order::SUCCESS]) && $_SESSION[Order::SUCCESS]) ?
        $_SESSION[Order::SUCCESS] : '';

        Order::clearSuccess();
        return $msg;
    }

    public static function setSuccess($msg)
    {

        $_SESSION[Order::SUCCESS] = $msg;
    }

    public static function clearSuccess()
    {

        $_SESSION[Order::SUCCESS] = null;
    }

    public static function setError($msg)
    {

        $_SESSION[Order::ERROR] = $msg;
    }

    public static function getError()
    {

        $msg = (isset($_SESSION[Order::ERROR]) && $_SESSION[Order::ERROR]) ?
        $_SESSION[Order::ERROR] : '';

        Order::clearError();
        return $msg;
    }

    public static function clearError()
    {

        $_SESSION[Order::ERROR] = null;
    }

    public static function setErrorRegister($msg)
    {

        $_SESSION[Order::ERROR_REGISTER] = $msg;
    }

    public static function getPage($page = 1, $itemsPerPage = 10)
    {

        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

        $results = $sql->select("
        select SQL_CALC_FOUND_ROWS *
        from  tb_clients
        limit $start, $itemsPerPage;
        ");

        $resultTotal = $sql->select("select FOUND_ROWS() as nrtotal");

        return [
            'data' => $results,
            'total' => (int) $resultTotal[0]["nrtotal"],
            'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage),
        ];
    }

    public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
    {

        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

        $results = $sql->select("
        select SQL_CALC_FOUND_ROWS *
        from  tb_clients
        where tb_nome_cli like :search or tb_bairro_cli = :search
        limit $start, $itemsPerPage;
        ", [
            ':search' => '%' . $search . '%',
            ':id' => $search,
        ]);

        $resultTotal = $sql->select("select FOUND_ROWS() as nrtotal");

        return [
            'data' => $results,
            'total' => (int) $resultTotal[0]["nrtotal"],
            'pages' => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage),
        ];
    }
}
