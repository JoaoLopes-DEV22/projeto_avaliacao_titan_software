# Sistema de Gerenciamento de Funcionários

Este é um sistema de gerenciamento de funcionários desenvolvido em PHP e MySQL. Ele permite o cadastro, edição de funcionários e exclusão, incluindo funcionalidades como o cálculo de bonificação baseado no tempo de serviço e a associação de funcionários a empresas.

## Funcionalidades

- **Cadastro de Funcionários**: Adicione novos funcionários ao sistema com informações como nome, CPF, RG, email, salário, data de cadastro e empresa associada.
- **Edição de Funcionários**: Edite as informações de funcionários existentes, incluindo a atualização de dados como nome, CPF, email, salário e bonificação.
- **Cálculo Automático de Bonificação**: A bonificação do funcionário é calculada automaticamente com base no tempo de serviço (anos de empresa), sendo 10% para mais de 1 ano de serviço e 20% para mais de 5 anos.
- **Cadastro de Empresas**: Cadastre empresas para associar os funcionários a elas.
- **Listagem de Funcionários**: Exiba todos os funcionários cadastrados com suas informações, como nome, CPF, RG, salário, data de cadastro, bonificação e empresa associada.
- **Mensagem de Sucesso ou Erro**: Após realizar ações como cadastro ou edição, o sistema exibe mensagens de sucesso ou erro.
- **Logout**: Permite que o usuário saia do sistema de forma segura.

## Tecnologias Utilizadas

- **PHP**: Linguagem de programação utilizada para o backend.
- **MySQL**: Banco de dados relacional para armazenar dados de funcionários e empresas.
- **HTML/CSS**: Para a construção das páginas e estruturação do layout.
- **PDO**: Para interação com o banco de dados de forma segura.
- **TCPDF**: Para criação de PDFs.

![HTML Badge](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS Badge](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![PHP Badge](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL Badge](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white) 

## Estrutura do Banco de Dados

### Tabelas:

1. **tbl_usuario**:
   - `id_usuario` (INT, Primary Key)
   - `login` (VARCHAR(50))
   - `senha` (VARCHAR(32))

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
   - `data_cadastro` (DATE)
   - `salario` (DOUBLE(10, 2))
   - `bonificacao` (DOUBLE(10, 2))

## Como Rodar o Projeto

1. Clone este repositório:
   ```bash
   git clone https://github.com/JoaoLopes-DEV22/projeto_avaliacao_titan_software.git

2. Inicie o servidor 
3. Acesse localhost/projeto_avaliacao_titan_software/
