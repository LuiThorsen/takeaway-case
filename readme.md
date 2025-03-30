# Test case: Take Away Menu Admin

Se, opret, rediger og slet produkter i en back-office visning.

Se live på <http://167.99.216.216/>

## Installationsvejledning

1. Klon eller download og udpak repoen

2. Installer og konfigurer en MySQL-server. Du kan fx bruge denne: https://dev.mysql.com/downloads/installer/ eller XAMPP

3. Opsæt .env med databaseforbindelsen mm. Start med .env.example, som allerede har det meste klar

4. Installer PHP, hvis du ikke allerede har det. Installer derefter Composer: https://getcomposer.org/download/

5. Kør `composer global require laravel/installer`

6. Kør `composer install` og `composer update`, hvis det er nødvendigt.

7. Installer npm, og kør `npm install` og `npm run build`

8. Kør `php artisan migrate` for at oprette databasen.

9. Kør `composer run dev` for at starte appen i udvikler tilstand.

10. Åbn http://localhost:8000/ og opret en konto.

## Overvejelser, der er værd at nævne

Jeg kom nok lidt over de 20 timer, men der var også relativt mange ting, jeg skulle sætte mig ind i.

Hvis jeg havde haft mere tid, ville jeg overveje følgende:

- Gøre med ud af formular valideringen, så den videregiver sigende fejl-beskeder til brugeren.

- Lave et custom PrimeVue tema, i stedet for at bruge !important til de få rettelser, hvor jeg blev nødt til at overskrive temaets (Lara) styling.

- Skifte til navngivet domæne med SSL/TLS certifikat.

- Splitte salgsprodukter siden op i adskilte moduler.

- Gøre mere for brugervenligheden på små skærme.
    - Forsøge at undgå horisontal scrolling ved alle scenarier.
    - Sikre mig at browserens UI ikke blokerer for nogle elementer, som knapperne nederst i produkt formular popup.
    - Mulighed for træk-og-slip for at ændre rækkefølge, også på touch-skærme.
