# Projeto Estoque

Projeto desenvolvido para o teste de Dev Jr Pleno da Superlógica

Template usado no projeto [Almsaeed Studio](https://almsaeedstudio.com)

PHP versão  7.1.23 

Banco de Dados MySQL 5.7

Utilizado o Framework : Slim Framework (www.slimframework.com/) e o RainTPL - easy php template engine 

https://github.com/feulf/raintpl3/wiki/Documentation-for-web-designers
 
Para a chamada da API de pagamento em php, utilizei a biblioteca do sdk php do próprio pjbank
Usuário de acesso: teste
Senha: teste

Script do banco está na raíz com o nome db_estoque.sql

foi utilizada vitualização de host com essa configuração


<VirtualHost *:80>
    ServerAdmin estoque.com.br
    DocumentRoot "D:/estoque"	              
    ServerName www.estoque.com.br
    ErrorLog "logs/dummy-host2.example.com-error.log"
    CustomLog "logs/dummy-host2.example.com-access.log" common
	<Directory "D:/estoque">
        Require all granted
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
	</Directory>
</VirtualHost>


