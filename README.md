# ECF
---------------
Liens vers TRELLO : https://trello.com/b/OROsUvoS/ecf-kanban
----------------------------------------
Mon environement de développement :
Langages : Php 7.2.5 au minimum, html css js bootstrap doctrine et mysql
Versionning : Git
Serveur : WAMP
System : WINDOWS 10 64bit
IDE : PHPSTORM
Deployement : HEROKU
----------------------------------------------------------------

--------------------------------------------
Installation :
Symfony CLI + guide initialisation projet : https://symfony.com/download
Projet installer avec la commande : symfony new --full banque
---------------------------------------------------------------

----------------------------------------------------------
Télécharger les vendors avec Composer bien évidemment :
php composer.phar install
-----------------------------------------------------------

---------------------------------------------------------
Créez la base de données
php bin/console doctrine:database:create
---------------------------------------------------------------

------------------------------------------------------------------
Puis créez les tables correspondantes au schéma Doctrine :
php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force
------------------------------------------------------------------

Enfin, éventuellement, ajoutez les fixtures :
php bin/console doctrine:fixtures:load
