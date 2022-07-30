<?php 

/**
 * Função para gerar o caminho de uma classe incluida em qualquer arquivo php. 
 * Usando o nome da classe, ela gera o caminho do arquivo no sistema de arquivos 
 * do projeto.
 */
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

/**
 * Função para dinamicamente incluir classes chamadas no código.
 * Em qualquer chamada a new, ela pega o nome da classe e chama include_once
 * com o caminho correto para o arquivo onde a classe é declarada. Além disso, 
 * imprime nos logs do PHP o nome da classe e o caminho gerado durante a chamada.
 */
spl_autoload_register( function($className) {
   $className = str_replace("\\",DIRECTORY_SEPARATOR,$className);
   $path = getIncludePath($className);
   error_log("Class Name: $className");
   error_log("Path: $path");
   include_once $path;
});
