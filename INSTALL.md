Installation de Pochtron
========

Pour installer et utiliser Pochtron, vous avez besoin :
- d'un serveur web (Apache de préférence) exécutant PHP5
- d'une base de données MySQL accessible par ce serveur
- éventuellement d'une connexion à la base de données pour la récupération des photos des contacts via Facebook
(pour l'instant, la capacité à envoyer des courriels n'est pas requise)

Une solution locale (LAMP / WAMP) est tout à fait fonctionnelle (voire recommandée si vous n'avez pas un serveur et une connexion sécurisée à ce serveur).

Procédure d'installation
========
1. Copier l'intégralité du dossier à la racine (ou dans un sous-dossier) de votre serveur web
2. Créez un utilisateur pour la base de données et une base sur lequel il a tous les droits
3. Exécutez le code SQL du fichier installation.sql sur cette base de données
4. Renommez inclus/config.modele.php en inclus/config.inc.php et modifiez les paramètres pour refléter votre configuration

Il ne vous reste plus qu'à importer des clients, ce qui se fait pour l'instant plus facilement dans la base de données (l'ajout manuel dans le logiciel est également possible).

Nous vous recommandons d'utiliser le protocole HTTPS (quitte à générer un certificat auto-signé et à ajouter une exception) à la place du protocole HTTP puisque les mots de passe sont transmis en clair sur le réseau.