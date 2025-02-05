# symfony-api

This project is a simple API for a blog application built with Symfony. It provides user authentication and allows registered users to create and manage blog posts. The system follows a clean architecture and utilizes the Symfony Messenger component as a message bus for handling asynchronous tasks and background processing.

Features:
 - User Authentication – Secure login and registration system using Symfony Security.
 - Post Management – Users can create, update, delete, and view blog posts.
 - Message Bus (Symfony Messenger) – Ensures efficient processing of commands and events.
 - Testing Database – A dedicated database setup for running automated tests, ensuring stability and reliability.

This project is designed to be lightweight, scalable, and easily extendable for further features such as comments, likes, or categories.


## Technology

- PHP 8.2
- Symfony 7
- Postgres 14.1

## Instalation

3. Run docker-compose in main directory

   ```bash
   docker-compose up --build
   ```

4. After the containers start correctly, open new terminal and enter php-container bash

   ```bash
   docker exec -it php-container bash
   ```


** In point 4 and 5 you may need administrator privileges (in Linux, use commands with the `sudo` prefix)

   ```bash
    sudo docker-compose up --build
    sudo docker exec -it php-container bash
   ```
** If backend container have different name, please change `php-container` to this name in command from point 5

5. In bash generate key, push migrations, seed and downland articles using custom command

   ```bash
   composer install
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:migrations:migrate --env=test
   ```