<?php 

function getIncludePath($className) {
   if (strpos($className, 'Controller') !== false)
      return __DIR__ . '/src/Controller/' . $className . '.php';
   else if (strpos($className, 'Database') !== false)
      return __DIR__ . '/src/Database/' . $className . '.php';
   else if (strpos($className, 'Gateway') !== false)
      return __DIR__ . '/src/Gateway/' . $className . '.php';
   else if (strpos($className, 'Seeder') !== false)
      return __DIR__ . '/src/Seeder/' . $className . '.php';
   else return $className . '.php';
}

spl_autoload_register( function($className) {
   $className = str_replace("\\",DIRECTORY_SEPARATOR,$className);
   $path = getIncludePath($className);
   error_log("Class Name: $className");
   error_log("Path: $path");
   include_once $path;
});
