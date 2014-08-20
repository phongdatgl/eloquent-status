Eloquent Status
===============

## Install

Add `"fatagroup/eloquent-status": "dev-master"`  in your `composer.json`, and run:

```
$ composer update
```

## Setup

Make sure you have `status` column like this in database table.

```php
$table->enum('status', array('DRAFT', 'APPROVED'))->default('DRAFT');
```

In your model:

```php
<?php


class Post extends Eloquent {
  use \Fatagroup\EloquentStatus\StatusTrait;
  
	const DRAFT = 'DRAFT';
	const APPROVED = 'APPROVED';
	
	// ...
}

```

## Usage

```php
<?php

$posts = Post::all();
// SELECT * FROM `posts` WHERE `status` = `APPROVED`

$posts = Post::withDraft()->get();
// SELECT * FROM `posts`

$posts = Post::onlyDraft()->get();
// SELECT * FROM `posts` WHERE `status` = `DRAFT`

```

## License

MIT License
