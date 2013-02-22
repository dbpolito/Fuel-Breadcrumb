## Instalation

	1. Copy the files to your fuel/app/ folder.
	2. Add the breadrumb to your autoloader on fuel/app/bootstrap.php
		Autoloader::add_classes(array(
			'Breadcrumb' => APPPATH.'classes/breadcrumb.php',
		));

## Usage

	$this->template->set_global('breadcrumb', \Breadcrumb::create_links(), false);

## License

This is released under the MIT License.

## Documentation

Docs coming soon...

Feel free to contribute sending issues and pull request! :D
