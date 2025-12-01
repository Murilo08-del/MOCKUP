# Sistema Ferroviário MiniTrilhos

Projeto integrador do curso técnico - Sistema web para gerenciar trens, estações e sensores IoT.

## Sobre o Projeto

Desenvolvemos um sistema completo de gerenciamento ferroviário que integra sensores IoT com uma plataforma web. O objetivo é facilitar o controle de trens, rotas, estações e manutenções, tudo em um só lugar.

O projeto usa sensores ESP32 para monitorar temperatura, umidade e outros dados em tempo real, enviando informações via MQTT para o sistema web.

## O que tem funcionando

- Cadastro e gerenciamento de trens, estações e rotas
- Dashboard com informações gerais do sistema
- Sistema de alertas baseado nos sensores
- Controle de itinerários (viagens com várias rotas)
- Geração de relatórios em PDF e CSV
- Mapa interativo para visualizar e desenhar rotas
- Perfil de usuário e sistema de login
- Integração com sensores via MQTT

## Tecnologias usadas

**Frontend:** HTML, CSS, JavaScript, Leaflet.js (para os mapas)

**Backend:** PHP, MySQL

**IoT:** ESP32, sensores DHT11, LDR, HC-SR04, protocolo MQTT

**Ferramentas:** XAMPP, Visual Studio Code, Git

## Como rodar

1. Instale o XAMPP e inicie Apache + MySQL

2. Clone o repositório na pasta htdocs:
```bash
cd C:\xampp\htdocs
git clone [seu-repositorio]
```

3. Importe o banco de dados:
- Abra o phpMyAdmin (http://localhost/phpmyadmin)
- Crie um banco chamado "Ferrovia"
- Importe o arquivo database.sql

4. Configure a conexão em `php/conexao.php` se necessário

5. Acesse: http://localhost/[nome-da-pasta]/php/login.php

**Usuário de teste:** qualquer email cadastrado
**Senha:** mínimo 8 caracteres com maiúscula, minúscula, número e caractere especial

## Estrutura do projeto
```
/php - arquivos PHP (login, cadastros, conexão com BD)
/html - páginas principais do sistema
/css - estilos
/img - imagens
database.sql - script do banco de dados
```

## Equipe

- [Murilo] - 
- [Luiz] - 
- [Vitor] - 
- [Henrique] - 

## Funcionalidades implementadas

Das 17 funcionalidades solicitadas, implementamos 15:

✅ Dashboard geral
✅ CRUD de sensores
✅ CRUD de estações
✅ CRUD de rotas com mapa
✅ CRUD de trens
✅ Gerenciamento de alertas
✅ CRUD de itinerários
✅ Perfil de usuário
✅ Login e cadastro
✅ Integração IoT/MQTT
✅ Geração de relatórios
✅ Página sobre
✅ Banco de dados completo

## Problemas conhecidos

- Algumas telas podem não ficar 100% responsivas em celulares muito pequenos
- A integração MQTT funciona mas precisa configurar o broker
- Faltou implementar o sistema de chamados completo

## Observações

Este foi nosso primeiro projeto integrando tantas tecnologias diferentes. Aprendemos muito sobre trabalho em equipe, Git, e como integrar IoT com web.

O código pode não estar perfeito, mas fizemos o nosso melhor com o tempo que tínhamos. Qualquer dúvida é só entrar em contato!

## Licença

MIT