<?php

namespace Estoque\Model;

use Estoque\Recebimento;
use Estoque\Model;
use function GuzzleHttp\json_encode;

class GeraBoleto extends Model {
    

    public static function gerarBoleto($client, $product,$data_venc) {
    
        $valor_total = 0;

        $descricao = array();

        foreach ($product as $key => $value){
                
                $valor_total += $value['vlprice'] * $value['vlqtde'];

                array_push($descricao,'Código '.$value["idproduct"]."");        
                array_push($descricao,$value["desproduct"]."");                  
                array_push($descricao,"R$".$value['vlprice']."");
                array_push($descricao,$value['vlqtde'].' Unids'."\r\n");     
                array_push($descricao,"\r\n");                                         
                                        
            
            }
            
        $credencial = "3d0ef66356a4b21f01c68df33cbcc5ec07dcb771";
        $chave = "540e8f2d683a6148c143e312fd4a2bf2e186d865";
      
        $PJBankRecebimentos = new Recebimento($credencial, $chave);

        $boleto = $PJBankRecebimentos->Boletos->NovoBoleto();

        $boleto->setNomeCliente($client['tb_nome_cli'])            
            ->setCpfCliente($client['tb_cpf_cli'])
            ->setValor($valor_total)
            ->setVencimento($data_venc)
            ->setPedidoNumero(rand(0, 999))
            ->setTexto(implode("\r\n", $descricao))   
            ->setLogoUrl('https://www.defatoonline.com.br/wp-content/uploads/2019/06/2477_img.png')
            ->gerar(); 
            
        $link_boleto = $boleto->getLink();
        
        
        header("Location: $link_boleto");

        exit;
        
      
        
    }
       

}