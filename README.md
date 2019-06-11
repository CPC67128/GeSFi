# GeSFi

Project description: http://stevefuchs.fr/wordpress/gesfi/

## Description

GeSFi (Gestion Financière) est une application web de gestion financière que je développe et fait évoluer depuis 2010.

Elle permet de gérer sa comptabilité privée (dépenses, revenus) et ses finances personnelles (patrimoine immobilier, placements financiers, emprunts), mais également la comptabilité du couple (partage des dépenses).

Quelques captures des versions précédentes:

Version 2014

![V2014](https://github.com/CPC67128/GeSFi/blob/master/media/v2014_1.jpg)
![V2014](https://github.com/CPC67128/GeSFi/blob/master/media/v2014_2.jpg)

Version 3

Transition en programmation PHP objet

![V3](https://github.com/CPC67128/GeSFi/blob/master/media/v3.jpg)

Version 2

Introduction des librairies JavaScript jQuery, jQuery UI, jQuery Mobile et mobile_device_detect.

![V2](https://github.com/CPC67128/GeSFi/blob/master/media/v2_1.png)
![V2](https://github.com/CPC67128/GeSFi/blob/master/media/v2_2.png)
![V2](https://github.com/CPC67128/GeSFi/blob/master/media/v2_3.png)
![V2](https://github.com/CPC67128/GeSFi/blob/master/media/v2_4.png)

Version 1

Le nom du projet initial était "Gestion Financière du Couple" (GFC)

![V1](https://github.com/CPC67128/GeSFi/blob/master/media/v1.jpg)



## Installation (French / Français)

### Prérequis

* Capacité à installer et à héberger une une application web
* Espace web disposant de PHP 7
* Une base de données MariaDB 10

Cette application peut être hébergée sur un [site Internet 1&1](http://www.1and1.fr/?kwk=16605005), sur un NAS Synology, un site en local via XAMPP ou WAMP, voir encore un site auto-hébergé sur un Raspberry Pi.

### Première installation

* Créer une base de données MariaDB:
  * Nom: « gesfi »
  * Interclassement: « utf8_general_ci »

![Création base de données](https://stevefuchs.fr/wordpress/wp-content/uploads/2014/09/2017-08-27_232352.png?w=492)
* [Télécharger l’archive de l’application sur GitHub](https://github.com/CPC67128/GeSFi/archive/master.zip), et la décompresser
* Sur votre site web, créer un répertoire « gesfi » (ou autre)
* Uploader les fichiers dans ce répertoire
* Editer le fichier ./configuration/configuration.php et remplir les paramètres requis (base de données…)
* Aller sur http://site web/gesfi/setup/
* Cliquer sur « Upgrade database » pour créer la structure de la base de données:

![Mise à jour de la base](https://stevefuchs.fr/wordpress/wp-content/uploads/2014/09/2015-04-18_155906.png?w=600)
* Effacer le répertoire ./setup/ de votre répertoire d’installation
* Se rendre sur la page http://site web/gefi/ et se connecter avec un des deux comptes en laissant le champ mot de passe vide
* Débuter par la configuration des utilisateurs, des comptes et des catégories depuis l’espace d’administration.

## Mise à jour

* Sauvegarder
  * Base de données
  * Répertoire GeSFi de votre site web
* Dans ce dernier, effacer tout les sous-répertoires excepté ./configuration/
* [Télécharger l’archive de l’application sur GitHub](https://github.com/CPC67128/GeSFi/archive/master.zip), et la décompresser
* Uploader les fichiers excepté le répertoire ./configuration/ sur votre site web
* Aller sur http://site web/gesfi/setup/
* Cliquer sur « Upgrade database » pour créer la structure de la base de données:
* Effacer le répertoire ./setup/ de votre répertoire d’installation

# Development notes

record_type:

- money transfer from an account to another account :

1x = income

2x = outcome

0 = between account (money transfer)

    20 (-, debit) linked to 10 (+, credit)

1 = from account to virtual duo account

    21 = - or debit from private account (expected to be linked to 22 or 12 in virtual duo account)
        These records are not considered as an expense but as a debit
    11 = + or credit from duo account (partner)
        (expected to be linked to 12 in virtual duo account)

2 = going or coming from outside (income, expense)

    12 = wage, gift, ...
    22 = expense

Duo virtual account allowed operations:

    12 (credit from partners) -> Account -> 22 (expense)

Duo real account allowed operations:

    10 (credit between account) -> Account -> 20 (debit between account)
                                       -> 22 (expense)

Private real account allowed operations:

    10 (credit between account)     -> Account -> 20 (debit between account)
    11 (credit coming from partner)            -> 22 (expense)
    12 (money coming from outside)

## Glossaire de l'application


Balance
Transfer
Credit
Debit
Payment
Income



Record

Account : compte bancaire (comptes d'utilisation courante)

* Payment
* Transfer

Investment Account : placement bancaire

* Deposit
* Withdrawal

## Coding standards

Classes naming:
- standard: AaaaAaaaAaaa
- inherited: AaaaAaaaAaaa_BbbbbBbbb
Use of singular in names

Quotes:
- HTML tags "
- Javascript within HTML tags '
- PHP strings '
but Strings to translate: "
- SQL queries delimiters within PHP "
- SQL queries in-string delimiters '

Examples:
 $row['record_group_id']
 $sql = sprintf("update {TABLEPREFIX}record set designation='%s' where record_group_id = '%s'", ...
