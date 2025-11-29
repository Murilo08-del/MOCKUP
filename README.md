# 🚆 MiniTrilhos - Sistema Ferroviário Inteligente

## 📋 Objetivo do Projeto

O **MiniTrilhos** é uma plataforma completa de gerenciamento e monitoramento de operações ferroviárias que integra tecnologias modernas de IoT (Internet das Coisas) com uma interface web intuitiva, permitindo o controle em tempo real de trens, rotas, estações e sensores distribuídos pela rede ferroviária.

## 🎓 Contexto

Este projeto foi desenvolvido como atividade final integradora das disciplinas do curso técnico, reunindo conhecimentos adquiridos ao longo do semestre em:

- **Programação Web**: Desenvolvimento frontend com HTML5, CSS3 e JavaScript
- **Banco de Dados**: Modelagem e implementação de SGBD MySQL
- **Internet das Coisas (IoT)**: Integração com sensores ESP32 via protocolo MQTT
- **Redes de Computadores**: Comunicação em tempo real com broker MQTT
- **Engenharia de Software**: Metodologias ágeis, versionamento Git e documentação

## ⚙️ Funcionalidades Principais

### ✅ Funcionalidades Implementadas (14/17 - 82%)

1. ✅ **Dashboard Geral** - Visão consolidada de trens, rotas, sensores e estatísticas em tempo real
2. ✅ **CRUD de Sensores** - Gerenciamento completo de sensores IoT (cadastrar, listar, editar, excluir)
3. ✅ **CRUD de Estações** - Gestão de estações ferroviárias com dados de localização e capacidade
4. ✅ **Rotas com Mapa Interativo** - Visualização e desenho de rotas utilizando Leaflet.js
5. ✅ **Gerenciamento de Alertas** - Sistema de alertas vinculados aos sensores com níveis de prioridade
6. ✅ **CRUD de Itinerários** - Criação de viagens compostas por múltiplas rotas sequenciais
7. ✅ **Geração de Relatórios** - Relatórios detalhados de sensores, rotas, itinerários e manutenções (exportação PDF/CSV)
8. ✅ **Tela de Login** - Autenticação de usuários com validação de credenciais
9. ✅ **Tela de Cadastro** - Registro de novos usuários com validação de senhas fortes
10. ✅ **Página Sobre** - Informações sobre o sistema, equipe e tecnologias utilizadas
11. ✅ **CRUD de Trens** - Gerenciamento da frota de trens (cadastrar, listar, editar, excluir)
12. ✅ **Banco de Dados SQL** - Script completo de criação das 13 tabelas principais
13. ✅ **Design Responsivo** - Interface adaptável para desktop, tablets e dispositivos móveis
14. ✅ **Sistema de Navegação** - Sidebar responsiva com menu hambúrguer para mobile

### 🔨 Funcionalidades em Desenvolvimento (3/17)

15. ⚠️ **Integração MQTT Funcional** - Comunicação em tempo real com sensores ESP32 (mockup pronto, aguardando integração)
16. ⚠️ **CRUD de Manutenção de Trens** - Sistema de agendamento e histórico de manutenções (estrutura de banco pronta)
17. ⚠️ **Página de Chamados de Manutenção** - Sistema para abertura e gerenciamento de chamados (estrutura de banco pronta)

**Nota**: Funcionalidades como notificações e perfil de usuário foram planejadas mas não implementadas para priorizar as 14 funcionalidades essenciais do requisito mínimo.

## 💻 Tecnologias Utilizadas

### Frontend
- **HTML5** - Estruturação semântica das páginas
- **CSS3** - Estilização avançada com Flexbox e Grid Layout
- **JavaScript (ES6+)** - Interatividade e manipulação do DOM
- **Leaflet.js** - Mapas interativos para visualização de rotas

### Backend
- **PHP 7.4+** - Linguagem de programação server-side
- **MySQL 8.0** - Sistema de gerenciamento de banco de dados relacional

### IoT
- **ESP32** - Microcontrolador para sensores
- **Protocolo MQTT** - Comunicação assíncrona entre dispositivos
- **HiveMQ Broker** - Intermediação de mensagens MQTT
- **Arduino IDE** - Desenvolvimento de firmware para ESP32

### Ferramentas de Desenvolvimento
- **Git/GitHub** - Controle de versão e colaboração
- **XAMPP** - Ambiente de desenvolvimento local (Apache + MySQL + PHP)
- **VS Code** - Editor de código-fonte

## 👥 Equipe de Desenvolvimento

Este projeto foi desenvolvido por alunos do curso técnico em Informática:

- **Desenvolvedor 1** - Frontend e Design UI/UX
- **Desenvolvedor 2** - Backend e Banco de Dados
- **Desenvolvedor 3** - Integração IoT e Sensores
- **Desenvolvedor 4** - Testes e Documentação

## 📁 Estrutura do Repositório

```
MOCKUP-5/
│
├── README.md                    # Documentação principal do projeto
│
├── php/                         # Scripts PHP backend
│   ├── conexao.php             # Configuração de conexão com banco de dados
│   ├── login.php               # Autenticação de usuários
│   └── crieconta.php           # Registro de novos usuários
│
├── html/                        # Páginas HTML/PHP do sistema
│   ├── dashboard.php           # Painel principal com estatísticas
│   ├── gerenciarsensores.php   # Listagem e gerenciamento de sensores
│   ├── cadastrarsensores.php   # Formulário de cadastro de sensores
│   ├── gerenciarestações.php   # Listagem e gerenciamento de estações
│   ├── cadastrarestações.php   # Formulário de cadastro de estações
│   ├── gerenciartrens.php      # Listagem e gerenciamento de trens
│   ├── gerenciaritinerários.php # Listagem de itinerários
│   ├── cadastroitinerário.php  # Formulário de criação de itinerários
│   ├── alertas.php             # Sistema de gerenciamento de alertas
│   ├── geraçãorelátorios.php   # Geração de relatórios do sistema
│   ├── rotas.php               # Mapa interativo de rotas
│   └── sobre.php               # Página informativa sobre o projeto
│
├── css/                         # Arquivos de estilização
│   ├── login.css               # Estilos da página de login
│   └── crieconta.css           # Estilos da página de cadastro
│
├── js/                          # Scripts JavaScript
│   ├── dashboard.js            # Lógica do dashboard
│   ├── alertas.js              # Lógica de alertas
│   └── sidebar.js              # Controle da sidebar responsiva
│
├── img/                         # Recursos de imagem
│   └── login-removebg-preview.png
│
├── database/                    # Scripts de banco de dados
│   └── database.sql            # Script completo de criação do banco
│
└── docs/                        # Documentação adicional
    └── manual_usuario.pdf      # (Opcional) Manual do usuário
```

## 🗃️ Estrutura do Banco de Dados

O sistema utiliza **MySQL** com as seguintes tabelas principais:

1. **usuarios** - Dados de login e perfis de usuários (admin, funcionário, comum)
2. **estacoes** - Informações sobre estações ferroviárias
3. **trens** - Cadastro da frota de trens e status operacional
4. **sensores** - Dispositivos IoT instalados em trens e estações
5. **rotas** - Rotas individuais entre estações
6. **itinerarios** - Viagens compostas por múltiplas rotas
7. **itinerarios_rotas** - Relacionamento entre itinerários e rotas
8. **manutencoes** - Histórico e agendamento de manutenções
9. **alertas** - Alertas gerados pelos sensores
10. **notificacoes** - Notificações gerais do sistema
11. **chamados_manutencao** - Chamados abertos por usuários
12. **leituras_sensores** - Histórico de leituras dos sensores

### Diagrama Entidade-Relacionamento

O banco de dados segue modelo relacional com chaves estrangeiras para garantir integridade referencial. O script completo está disponível em `database/database.sql`.

## 🚀 Como Executar o Projeto

### Pré-requisitos

- **XAMPP** (ou LAMP/WAMP) instalado
- **MySQL** configurado
- **PHP 7.4** ou superior
- Navegador web moderno (Chrome, Firefox, Edge)

### Passo a Passo

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/minitrilhos.git
   cd minitrilhos
   ```

2. **Configure o banco de dados**
   - Abra o **phpMyAdmin** (http://localhost/phpmyadmin)
   - Crie um banco de dados chamado `Ferrovia`
   - Importe o arquivo `database/database.sql`

3. **Configure a conexão**
   - Edite o arquivo `php/conexao.php`
   - Ajuste as credenciais se necessário:
     ```php
     $localhost = "localhost";
     $user = "root";
     $password = "";
     $banco = "Ferrovia";
     ```

4. **Inicie o servidor**
   - Abra o **XAMPP Control Panel**
   - Inicie os módulos **Apache** e **MySQL**

5. **Acesse o sistema**
   - Abra o navegador e acesse: `http://localhost/SA/MOCKUP-5/php/login.php`
   - **Credenciais de teste**:
     - Admin: `admin@minitrilhos.com` / senha: `password`
     - Usuário: `maria@usuario.com` / senha: `password`

## 🔐 Credenciais Padrão

O sistema vem com usuários pré-cadastrados para teste:

| Tipo | E-mail | Senha | Permissões |
|------|--------|-------|------------|
| Admin | admin@minitrilhos.com | password | Acesso total |
| Funcionário | joao@minitrilhos.com | password | Gerenciamento operacional |
| Comum | maria@usuario.com | password | Visualização e chamados |

**⚠️ IMPORTANTE**: Altere estas senhas antes de colocar o sistema em produção!

## 📊 Funcionalidades Detalhadas

### 1. Dashboard
- Estatísticas em tempo real (trens ativos, estações, alertas)
- Gráficos de desempenho
- Monitoramento de sensores
- Alertas recentes priorizados por criticidade

### 2. Gerenciamento de Sensores
- Cadastro de sensores com tipos: temperatura, umidade, luminosidade, presença, GPS
- Configuração de limites mínimos e máximos para alertas
- Vinculação de sensores a trens ou estações
- Status online/offline em tempo real
- Tópicos MQTT configuráveis

### 3. Gerenciamento de Estações
- Cadastro completo com endereço e coordenadas GPS
- Capacidade de passageiros e número de plataformas
- Indicação de acessibilidade
- Status operacional (ativa, inativa, manutenção)

### 4. Gerenciamento de Trens
- Cadastro da frota com especificações técnicas
- Tipos: expresso, regional, metropolitano, luxo, carga
- Controle de quilometragem rodada
- Histórico de manutenções

### 5. Rotas e Itinerários
- **Rotas**: Trechos individuais entre duas estações
- **Itinerários**: Viagens completas com múltiplas rotas
- Mapa interativo com Leaflet.js para desenhar trajetos
- Cálculo automático de distância e duração total

### 6. Sistema de Alertas
- Três níveis de prioridade: crítico, aviso, informativo
- Gerados automaticamente pelos sensores
- Possibilidade de marcar como resolvido
- Filtros por tipo e status

### 7. Relatórios
- Relatórios de sensores, rotas, itinerários, manutenções, trens e estações
- Filtros por período e status
- Exportação em PDF e CSV (funcionalidade planejada)

## 🔒 Segurança

- Senhas armazenadas com hash bcrypt (PASSWORD_DEFAULT do PHP)
- Validação de e-mails únicos no cadastro
- Requisitos de senha forte: 8+ caracteres, maiúscula, minúscula, número e caractere especial
- Proteção contra SQL Injection com prepared statements
- Sessões PHP para controle de autenticação

## 📱 Responsividade

O sistema é totalmente responsivo com:
- Layout adaptável para telas de 320px até 4K
- Menu hamburger para dispositivos móveis
- Grid layouts que reorganizam conforme a tela
- Formulários otimizados para touch
- Breakpoints em 768px e 1024px

## 🎨 Design

- **Paleta de cores**: Gradientes de cinza (#a79f9fff a #332e2eff) e laranja/marrom (#d6651aff a #5b575fff)
- **Tipografia**: Segoe UI (fonte do sistema)
- **Ícones**: Emojis Unicode para compatibilidade universal
- **Animações**: Transições suaves em hover e focus
- **Cards**: Elevação com sombras para profundidade visual

## 🐛 Problemas Conhecidos

1. **Integração MQTT**: Mockup pronto, mas requer configuração de broker e firmware ESP32
2. **Exportação de relatórios**: Funcionalidade de PDF/CSV ainda não implementada
3. **Upload de imagens**: Não há sistema de upload para fotos de perfil ou chamados
4. **Notificações em tempo real**: Requer implementação de WebSockets ou Long Polling

## 🔄 Próximas Melhorias

- [ ] Implementar backend PHP para todas as operações CRUD
- [ ] Conectar sensores ESP32 reais via MQTT
- [ ] Sistema de notificações em tempo real
- [ ] Página de perfil de usuário com edição
- [ ] Sistema completo de chamados de manutenção
- [ ] Gráficos históricos de leituras de sensores
- [ ] API REST para integração com aplicativos mobile
- [ ] Testes automatizados (PHPUnit)

## 📄 Licença

Este projeto é distribuído sob a licença **MIT**. Você é livre para usar, modificar e distribuir o código, desde que mantenha os créditos aos autores originais.

```
MIT License

Copyright (c) 2024 MiniTrilhos - Sistema Ferroviário Inteligente

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 📞 Informações Complementares

### Suporte e Dúvidas

- **E-mail**: suporte@minitrilhos.com.br
- **GitHub Issues**: [https://github.com/seu-usuario/minitrilhos/issues](https://github.com/seu-usuario/minitrilhos/issues)
- **Documentação**: Este README e comentários inline no código

### Apresentação do Projeto

Este projeto foi apresentado como trabalho final integrando as disciplinas:
- Programação Web
- Banco de Dados
- Internet das Coisas (IoT)
- Redes de Computadores

**Instituição**: Curso Técnico em Informática  
**Data de Entrega**: Novembro de 2024  
**Versão**: 1.0.0

### Agradecimentos

Agradecemos aos professores das disciplinas envolvidas pelo suporte durante o desenvolvimento, aos colegas de turma pelos feedbacks valiosos, e à comunidade open-source pelas ferramentas e bibliotecas utilizadas.    

---

**🚆 MiniTrilhos** - Conectando o futuro dos transportes ferroviários  
© 2024 - Todos os direitos reservados