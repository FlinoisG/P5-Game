# Projet 5 - Jeu de Stratégie

Un petit jeu de stratégie en ligne dévelopé en PHP et JS.
Une partie dure tout la semaine et commencer le dimanche à minuit.
Le but du jeu est de faire le plus de points lors d'une partie en se développant économiquement ou militairement.
Construisez des mines pour vous enrichir en matière première, des bases pour produire des ouvriers et des soldats.

## Installation

Un guide étape par étape pour installer l'application sur un server.

### Préparation de l'installation

- Premièrement, clonez la branche Master et copiez son contenu dans un dossier sur votre ordinateur

- Installez Composer. Dans le terminale, positionez-vous dans le dossier que vous venez de créer et entrer le script suivant.

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

- Une fois la copie de composer.phar téléchargée, entrez la commande suivante pour installer les dépendances. Composer créera un dossier "vendor" contenant les fichiers nécessaire à l'autoloading et le namespacing.

```
php composer.phar install
```
### Préparation de la base de données

Importez la base de données mysql avec le fichier game.sql à la racine du dossier.

**par ligne de commande**

avec la ligne de commande suivante (remplacez username):

```
mysql -u [username] -p game < game.sql
```

**par PhpMyAdmin**

Onglet Importer > bouton "parcourir..." > game.sql > éxecuter

### Installation de l'application sur le server

- Suprimez l'extention .dist du fichier src/config/mysqlConfig.json.dist, et modifiez-le avec paramètre de connection à la base de donnée.

- Uploader les dossiers deposit, pubic, src, vendor et le fichier cron.php sur le server

- Pour vérifier que tout fonctionne, il suffit de se connecter au site et de voir si la carte du jeu s'affiche correctement.

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - The web framework used
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Billie Thompson** - *Initial work* - [PurpleBooth](https://github.com/PurpleBooth)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
