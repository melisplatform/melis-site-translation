# melis-site-translation  

MelisSiteTranslation allows to manage the text translation of every module.

## Getting Started  

These instructions will get you a copy of the project up and running on your machine.  

### Prerequisites  

You will need to install melisplatform/melis-core, melisplatform/melis-cms and melisplatform/melis-engine in order to have this module running.  
This will automatically be done when using composer.

### Installing

Run the composer command:  
```
composer require melisplatform/melis-site-translation
```

### Database  

Database model is accessible on the MySQL Workbench file:  
/melis-site-translation/install/model  
Database will be installed through composer and its hooks.  
In case of problems, SQL files are located here:  
/melis-site-translation/install/sql  

## Tools & Elements provided  

* Site Translation tool  

## Running the code  

### MelisSiteTranslation Services  

* MelisSiteTranslationService  
  Provides services to translate text and list all translations  
  File: /melis-site-translation/src/Service/MelisSiteTranslationService.php  
  
```
//Get the service
$melisSiteTranslationService = $this->getServiceLocator()->get('MelisSiteTranslationService');
//translate a certain text using translation key(eg. tr_translation_name)
//$langId - (optional) language id
$translatedText = $melisSiteTranslationService->getText($key, $langId);

```

## Authors

* **Melis Technology** - [www.melistechnology.com](https://www.melistechnology.com/)

See also the list of [contributors](https://github.com/melisplatform/melis-site-translation/contributors) who participated in this project.


## License

This project is licensed under the OSL-3.0 License - see the [LICENSE](LICENSE) file for details



