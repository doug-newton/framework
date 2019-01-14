# Hierarchical Vue-like MV\* frame work

Set up your database credentials in Core/Settings/DatabaseConnection.php

Generate autoload files:
```
$> cd public_html 
$> composer dump-autoload
```

## index.php

Entry point. Requires `routes.php`, which registers routes in Core\Router.
