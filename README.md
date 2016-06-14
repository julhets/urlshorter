#Urlshorter API - @Julio Reis

##A REST API for Urlshorter Service.

* Arquitetura
    - O framework utilizado para desenvolvimento da API foi o Silex, que é baseado em Symfony;
        - A estrutura está bem enxuta com o básico para rodar o serviço;
        - Seguindo o padrão Silex, temos os "Providers" (roteadores), "Repositories" (acesso ao banco), "Resources" (lógica da aplicação) e os "ValueObjects" (entidades).

    - O banco de dados utilizado é o Mysql e o servidor de aplicação foi o próprio embedd do Silex.

* Instalação
    1. Ao descompactar o .zip, a pasta da aplicação "urlshorter" conterá o arquivo install.sh;
    2. O install.sh instalará todas as dependências do projeto (git, composer, PHP, e extensões do PHP);
    3. Como foi informado que, caso haja um banco de dados, ele será instalado num servidor a parte, será necessário criar um banco de dados com o nome "urlshorter". O endereço do host, usuário e senha evem ser configurados no arquivo: "urlshorter/config/dev/db.php"
    4. Execute o comando: "composer install" na pasta raiz da aplicação;
    5. Execute o comando: "vendor/bin/doctrine orm:schema-tool:create" na raiz da aplicação - Esse comando criará as entidades no banco de dados;
    5. Execute o start.sh (que também se encontra na raiz da aplicação). Ele iniciará o servidor de aplicação e deverá ser executado dentro da pasta raiz da aplicação;
    6. Teste a API acessando: http://localhost:8999
    7. A API também está pública em: http://rapidets.com.br/

* Requerimentos (incluídos no install.sh)
    - PHP 5
        -apt-get install php5-common php5-dev php5-cli php5-fpm

    - Extensões do PHP5
        -apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql php-apc

    - MySql
        - apt-get install mysql-server

    - Composer
        -curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer