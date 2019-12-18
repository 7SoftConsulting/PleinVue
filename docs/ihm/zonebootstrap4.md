# Zone Bootstrap 4 - PHP-PV

## Pre-Requis

Librairie | Lien | Requis
------------ | ------------- | -------------
JQuery | https://www.jquery.com | Oui
Bootstrap4 | https://www.getbootstrap.com | Oui
Font Awesome | https://www.fontawesome.com | Non. Utilis� pour les fl�ches du tableau de donn�es.

## Installation

- T�l�chargez le code source de PHP-PV sur GITHUB. D�compressez le fichier **php-pv-master.zip**. Copiez le contenu du dossier **php-pv-master** dans **/**
- Copiez le fichier **jquery-3.x.x.min.js** dans **/js/jquery.min.js**
- Copiez le fichier **jquery-migrate-1.x.x.min.js** dans **/js/jquery-migrate.min.js**
- D�compressez le fichier **bootstrap-4.x.x-dist.zip**. Copiez :
	- **bootstrap-4.x.x-dist/css/bootstrap.min.css** vers **/css/bootstrap.min.css**
	- **bootstrap-4.x.x-dist/js/bootstrap.min.js** vers **/js/bootstrap.min.js**
- D�compressez le fichier **fontawesome-free-5.x.x-web.zip**. Copiez le contenu du dossier **fontawesome-free-5.x.x-web/** dans **/vendor/fontawesome/**

Vous devez avoir la structure suivante :

```
/php-pv-master
/css
	bootstrap.min.css
/js
	bootstrap.min.js
	jquery-migrate.min.js
	jquery.min.js
/vendor
	fontawesome
```	

Cr�ez votre fichier **/mazone1.php** avec ce contenu :

```php
<?php
// Librairie PHP-PV par defaut
include dirname(__FILE__)."/php-pv-master/Pv/Base.class.php" ;
// Librairie Bootstrap 4
include dirname(__FILE__)."/php-pv-master/Pv/IHM/Bootstrap4.class.php" ;
// D�clarer la classe Application
class MonApplication1 extends PvApplication
{
protected function ChargeIHMs()
{
// Inscrire la zone bootstrap dans l'application
$this->InsereIHM('mazone1', new MaZone1) ;
}
}
// D�clarer la zone Bootstrap
class MaZone1 extends PvZoneBaseBootstrap4
{
public $AccepterTousChemins = 1 ;
protected function ChargeScripts()
{
// Inscrire le script index de la zone
$this->InsereScriptParDefaut(new MonScript1()) ;
}
}
// D�clarer le script index
class MonScript1 extends PvScriptWebSimple
{
public function RenduSpecifique()
{
return "Hello, ma zone 1" ;
}
}
// Afficher la zone dans le navigateur
$app = new MonApplication1() ;
$app->Execute() ;

?>
```

R�sultat dans un navigateur :

![Resultat zone bootstrap 4](../images/zonebootstrap4_apercu1.png)

Voici le code source de cette page dans le navigateur :

![Code source zone bootstrap 4](../images/zonebootstrap4_codesource1.png)

## Caract�ristiques

- [Ent�tes de document](zone/entetesdoc.md)
- [CSS et Javascript](zone/css_javascript.md)
- [Membership (Service d'authentification)](zone/membership.md)
- [Scripts](zone/scriptsweb.md)
- [Documents Web](zone/documentsweb.md)
- [Actions Web](zone/actionsweb.md)
- [Filtres de donn�es](zone/filtresdonneesweb.md)
- [T�ches web](zone/tachesweb.md)

## Composants IU

- [Composants IU](zone/composantsui.md)
- [Tableau de donn�es](zone/tableaudonnees.md)
- [Formulaire de donn�es](zone/formulairedonnees.md)
