# Sistema de Gerenciamento de Funcionários

Este é um sistema simples de gerenciamento de funcionários, com funcionalidades para listar e cadastrar funcionários e empresas. Desenvolvido com PHP e MySQL, o sistema utiliza um banco de dados relacional para associar cada funcionário à sua empresa.

## Funcionalidades

- **Cadastro de Funcionários**: Adicione novos funcionários ao sistema.
- **Cadastro de Empresas**: Cadastre novas empresas que os funcionários podem ser associados.
- **Listagem de Funcionários**: Veja todos os funcionários cadastrados com suas informações e o nome da empresa associada.
- **Logout**: Permite que o usuário saia do sistema de forma segura.

## Tecnologias Utilizadas

- **PHP**: Linguagem de programação utilizada para o backend.
- **MySQL**: Banco de dados relacional para armazenar dados de funcionários e empresas.
- **HTML/CSS**: Para a construção das páginas e estruturação do layout.
- **PDO**: Para interação com o banco de dados de forma segura.

![HTML Badge](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS Badge](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![PHP Badge](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL Badge](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white) 

## Estrutura do Banco de Dados

### Tabelas:

1. **tbl_usuario**:
   - `id_usuario` (INT, Primary Key)
   - `login` (VARCHAR(20))
   - `senha` (VARCHAR(20))

2. **tbl_empresa**: 
   - `id_empresa` (INT, Primary Key)
   - `nome` (VARCHAR(40))

3. **tbl_funcionario**:
   - `id_funcionario` (INT, Primary Key)
   - `nome` (VARCHAR(50))
   - `cpf` (VARCHAR(11))
   - `rg` (VARCHAR(20))
   - `email` (VARCHAR(30))
   - `id_empresa` (INT, Foreign Key, refere-se a `tbl_empresa`)

## Como Rodar o Projeto

1. Clone este repositório:
   ```bash
   git clone https://github.com/JoaoLopes-DEV22/projeto_avaliacao_titan_software.git

2. Inicie o servidor 
3. Acesse localhost/projeto_avaliacao_titan_software
