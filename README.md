# Migration-demo 

Une petite application démo d'utilisateur de la librairie "TuxBoy/Migration" [Voir le dépôt du projet](https://github.com/TuxBoy/Migration).

## Installation

````bash
$ git clone https://github.com/TuxBoy/Migration-demo.git && cd Migration-demo
````

Installerles dépendances :

````bash
$ composer install
````

Démarrer l'application :

````bash
$ php -S localhost:8080 -t public -d display_errors=1 -d xdebug.remote_enable=1 -d xdebug.remote_autostart=1
````

Aller sur http://localhost:8080/posts, la migration sera lancer automatiquement.