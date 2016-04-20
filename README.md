yii1-enlog
=
yii1 component for Enlog v1 API implementation

#### Requirements
* Yii Framework > 1.1.15 (Have not test any other frameworks)
* [Enlog Account](enlog.net)

#### Configure
main.php:
```php
...
'components' => [
	'enlog' => [
		'class' => 'EnlogService',
		'url' =>	'https://api.enlog.net',
		'name' =>	'YOUR ENLOG USER NAME',
		'pass' =>	'YOUR ENLOG USER PASS',
	],
	...
```
