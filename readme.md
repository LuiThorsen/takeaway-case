# Test case: Take Away Menu Admin

Se, opret, rediger og slet produkter i en back-office visning.

## Installationsvejledning

1. Klon eller download og udpak repoen

2. Installer og konfigurer en MySQL-server. Du kan fx bruge denne: https://dev.mysql.com/downloads/installer/ eller XAMPP

3. Opsæt .env med databaseforbindelsen. Start med .env.example, som allerede har det meste klar

4. Installer PHP, hvis du ikke allerede har det. Installer derefter Composer: https://getcomposer.org/download/

5. Kør `composer global require laravel/installer`

6. Kør `composer install` og `composer update`, hvis det er nødvendigt.

7. Kør `npm install` og `npm run build`

8. Kør `php artisan migrate` for at oprette databasen.

9. Kør `composer run dev` for at starte appen.

10. Åbn http://localhost:8000/ og opret en konto, så kan du begynde at oprette produkter.
