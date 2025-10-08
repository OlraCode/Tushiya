# 🎓 Tushiya — Plataforma de Cursos Online

**Tushiya** é uma plataforma web desenvolvida como **Trabalho de Conclusão de Curso (TCC)** do ensino médio técnico em Informática.  
O objetivo do sistema é oferecer um ambiente onde **professores possam criar cursos** e **alunos possam explorá-los e comprá-los**, promovendo o aprendizado online de forma prática e acessível.

---

## 🚀 Visão Geral

A Tushiya permite que professores criem e gerenciem cursos de forma simples. Os cursos contém um título, descrição, categorias, preço e imagem.
Os alunos podem navegar pela plataforma, filtrar cursos por categoria e visualizar detalhes de cada curso antes da compra.

> 💡 A funcionalidade de **compra de cursos** será integrada futuramente com uma **API de pagamentos**.

---

## 🧩 Funcionalidades

| Funcionalidade | Descrição |
|----------------|------------|
| 👩‍🏫 **Cadastro de professores** | Permite que professores criem conta e publiquem seus cursos. |
| 🎓 **Cadastro de alunos** | Alunos podem se registrar e comprar cursos. |
| 📚 **Criação e gerenciamento de cursos** | Professores enviam cursos com título, descrição, categoria, preço e imagem de capa. |
| ✅ **Aprovação de cursos por administrador** | Todo curso criado por um professor deve ser **avaliado e aprovado** por um administrador antes de ficar visível na plataforma. |
| 🗂️ **Filtro por categoria** | Os alunos podem filtrar cursos por área de interesse. |
| 💰 **Sistema de compra** | Cada curso pode ser adquirido individualmente (integração com API de pagamentos pendente). |
| ✉️ **Envio de emails via mensageria** | Sistema assíncrono para envio de emails automáticos. |
| 🐳 **Ambiente Dockerizado** | Facilita a execução do projeto em qualquer máquina. |

---

## 🛠️ Tecnologias Utilizadas

| Camada | Tecnologias |
|--------|--------------|
| **Back-end** | PHP 8.x, Symfony |
| **Banco de dados** | MySQL |
| **Front-end** | Twig, Bootstrap, JavaScript |
| **Ambiente de desenvolvimento** | Docker, Docker Compose |
| **Gerenciamento de dependências** | Composer, NPM |
| **Mensageria** | Symfony Messenger |

---

## 🐳 Executando o Projeto com Docker e Makefile

A Tushiya foi totalmente configurada para rodar dentro de containers Docker.  
O Makefile inclui comandos para facilitar a execução e gerenciamento do ambiente de desenvolvimento.

### 🧰 Pré-requisitos

Antes de iniciar, verifique se você possui:
- [Docker](https://www.docker.com/) instalado
- [Docker Compose](https://docs.docker.com/compose/) instalado
- [Make](https://www.gnu.org/software/make/) instalado

---

## ▶️ Tutorial de Execução

Siga os passos abaixo para rodar o projeto localmente.

### 1️⃣ Clonar o repositório
```
git clone https://github.com/seu-usuario/tushiya.git
cd tushiya
```

### 2️⃣ Subir os containers
Inicia os containers da aplicação (Php e MySql).
```  
make up
```

### 3️⃣ Instalar dependências
Instala as dependências do Composer e do NPM.
```
make install
```

### 4️⃣ Criar o banco de dados e rodar as migrations
Cria e configura o banco de dados dentro do container MySQL configurado.
```
make create-db
make migrate
```

### 5️⃣ Rodar o sistema de mensageria
Responsável por processar as filas de mensagens e envio de emails.
```
make messenger
```
<br>

Após isso a plataforma estará acessível em [localhost:8000](http://localhost:8000)
