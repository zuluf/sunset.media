# Sunset Media source code

### source code for the [sunset.media](https://sunset.media/) website

This repository contains the public source code for the sunset.media website.

The real purpose of the repo is to have a link i can send to HR people when interviewed for new projects, since all the work i have on production is maintained privately (doh!)

In the app folder you can find a couple of neat tricks i used to avoid paying for a VPS, and the Sunset (dare I say it) "framework" I used for server config, routing and parsing of the production/development environment on a 5.3.x PHP shared hosting.

Configuring the back-end section is done by adding app/Sunset/Config/files/file.php, containing something like this:

```
return (object) array (
	'host' => (object) array (
		'uri' => 'localhost',
		'secure' => false
	),

	'db' => (object) array (
		'host' => 'localhost',
		'port' => 3306,
		'user' => 'root',
		'pass' => '',
		'base' => 'sunset'
	),

	'errors' => (object) array (
		'display' => 0
	),

	'timezone' => array(
		'mysqli' => '+00:00',
		'php' => 'UTC'
	),

	'environment' => 'production',

	'contacts' => (object) array (
		'email' => 'admin@example.com'
	)
);
```
Change the data in the config for your environment variables, create tables from the database folder and you are good to go.


Assets folder contains front-end part of the whole app, and you can build the distribution with these commands (nodejs required):

```
cd assets/
npm install gulp --save-dev
npm install
gulp build
```

When you run the `gulp build` task assets/.htaccess file will be overwritten with the current timestamp for the 'Last-Modified' and 'Expires' headers for all public assets; and, of course, less and js will be uglified and minified to assets/dist :)
Use `gulp live` for watching assets/less files change, and automatic build of the css.

In the assets/fonts/ you can find the font icons for php, js, mysql, jquery logos, that i couldn't find on the web.

If you poke around the javascript, there is a nice trick [bitovi](https://github.com/bitovi/canjs) used for cleaning element bindings, in [assets/js/sunset/Control](https://github.com/zuluf/sunset.media/assets/js/sunset/Control/).

Hope you guys enjoy the code, and find something useful for your apps.
Cheers :)

