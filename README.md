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
-----------------------------------------
# IMPORTANT!
Le fichier pdf question et reflexion ce trouve dans le dossier PDF-ECF
voici le lien direct https://github.com/BillelSahouli/ECF/tree/master/PDF-ECF
--------------------------------------------
# Deployement : HEROKU
# 1 : Creer un compte HEROKU
# 2 : inserez votre carte bleu
# 3 : instalez le cli de Heroku https://devcenter.heroku.com/articles/heroku-cli
# 4 : ouvrez invite de commande et faite : heroku login 
# 5 : si la fenetre de login ne souvre pas copié le lien qui ce trouve dans l'invite de commande
# 6 : identifié vous 
# 7 : Se mettre dans le dossier du projet et initié un dépot git si ce n'est pas fait. Commande : git init
# 8 : allez dans le .env et ligne 17 APP_ENV=dev a remplacé par APP_ENV=prod
# 9 : rendez vous sur https://dashboard.heroku.com/apps et cliquez sur New > Create new app choisissez la region le nom de l'app.
# 10 : rendez vous dans https://dashboard.heroku.com/apps/"nomDeVotreApp"/resources et rechercher l'addons JawsDB MySQL
# 11 : rendez vous dans settings et cliquez sur Reveal Config Vars copié la value cliquez sur add et rentrez la value et en KEY vous mettez DATABASE_URL
# 12 : rendez vous dans notre application et fait un git add . et un git commit -m "votre message" et faite git push heroku master
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
