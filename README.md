# hierarchical Vue-like MV\* framework

Set up your database credentials in Core/Settings/DatabaseConnection.php

Generate autoload files:
```
$> cd public_html 
$> composer dump-autoload
```

## index.php

Entry point. Requires `routes.php`, which registers routes in Core\Router.

The output of Router::handleRequest() is then stored in `$htmlBuffer`. This the the "inner content" of the page, irrespective of the view. In other words, $htmlBuffer must be such that it can be replaced within the page without affecting the surrounding layout, the template, which will be discussed later.

## routes.php

Defines "routes" (anonymous functions) to be registed within the Core\Router. These simply `require` scripts from `pages` or `data`. The scripts themselves determine within which `template` the `$htmlBuffer` is to be rendered, or if it must use a template at all \(`setPlain(true)`\).

## index.php

After requiring `routes.php`, it `require`s the `template`, which would have been determined by the script required by the anonymous function that Router::handleRequest() invoked.

If no `template` was defined, an `\Exception` is thrown.

The required script by the Router might also throw an exception.

Regardless, if an exception is thrown, `$pageBottom` (the inner content of `<body>` minus the `$customScriptArray`), is swapped with a div displaying the exception that occured.
