<?php
/**
 * phpDoctrine - Clansuite Initialization Class
 *
 * The library provides a database abstraction layer (DAL) 
 * as well as object-relational mapping (ORM)
 * with drivers for every PDO-supported database:
 *
 *    - FreeTDS / Microsoft SQL Server / Sybase
 *    - Firebird/Interbase 6
 *    - Informix
 *    - Mysql
 *    - Oracle
 *    - Odbc
 *    - PostgreSQL
 *    - Sqlite
 */
class Clansuite_Doctrine
{
    /**
     * Doctrine Bootstrap
     */
    public static function doctrine_bootstrap() 
    {   
        // Require compiled Library or normal 
        if (is_file( ROOT_LIBRARIES . '/doctrine/Doctrine.compiled.php'))
        {
            require_once ROOT_LIBRARIES .'/doctrine/Doctrine.compiled.php';
        }
        else
        {
            require_once ROOT_LIBRARIES .'/doctrine/Doctrine.php';
        }
        // Register autoloader         
        spl_autoload_register(array('Doctrine', 'autoload'));
        
        self::prepareDbConnection();
    }
    
    /**
     * prepareDbConnection
     *
     * This prepares a Database Connection via Doctrine_Manager
     * It's a lazy-connect, so it connects only when needed.
     * That will safe ressources.
     *
     * @link DNS Types on Doctrine Chapter 4.1.
     */
    public static function prepareDbConnection()
    {
        // construct the Data Source Name (DNS)
        $dsn = 'mysql://clansuite:toop@localhost/clansuite';
    
        // initalize a new Doctrine_Connection
        $db = Doctrine_Manager::connection($dsn);
        // !! no actual database connection yet !!
    
        // Set Database Prefix
        $db->setAttribute(Doctrine::ATTR_DBNAME_FORMAT, DB_PREFIX.'_%s');
        
        
        /**
         * Aggressive - It finds all .php files in a given path recursively and
           performs a require_once() on each file. Your files can be in subfolders, and
           the files can include multiple models. It is very flexible but the downside
           is that it will require_once() all files.
           Conservative - It finds all .php files in a given path recursively and
           builds an array of className => /path/to/file. The className is parsed from
           the name of the file, so each file must contain only one class and the file
           must be named after the class inside of it. This array is then referenced in
           Doctrine::autoload() and used to load models when they are asked for. 
           Johnatan Wage on http://groups.google.com/group/doctrine-user
         */
        $db->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE); 
    
        // case-sensitive: 
        // array('ModelName' => '/path/to/ModelName.php'); 
        #$db->loadModels($this->get_path('model')); 
    
        # Set Attributes for that later Connection
    
        // Validate All
        #$db->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
    
        // Set portability for all rdbms = default
        #$db->setAttribute('portability', Doctrine::PORTABILITY_ALL);
    
        // identifier quoting
        // disabled for now, because we have no reserved words as a field names
        #$db->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
        
        // Set Cache Driver
        /**
         * Doctrine_Cache_Exception: The apc extension must be loaded for using this backend !
        $cacheDriver = new Doctrine_Cache_Apc();
        $db->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
        // set the lifespan as one hour (60 seconds * 60 minutes = 1 hour = 3600 secs)
        $db->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 3600);
        */
    
        # $db->execute("SET CHARACTER SET utf8");
        // ---- DO QUERY ---- //
    
        // connects database and performs a query
        #$db->query('FROM User u');
    
       /**
        *   $conn = Doctrine_Manager::getInstance()
        *           ->openConnection(new PDO("dsn", "username", "pw"));
        */
    }
}

/**

try {
    $conn->execute('SELECT * FROM unknowntable');
} catch (Doctrine_Connection_Exception $e) {
    print 'Code : ' . $e->getPortableCode();
    print 'Message : ' . $e->getPortableMessage();
}


$q = new Doctrine_Query();

// Doctrine uses 'connection 1' for fetching here
$users = $q->from('User u')->where('u.id IN (1,2,3)')->execute();

// Doctrine uses 'connection 2' for fetching here
$groups = $q->from('Group g')->where('g.id IN (1,2,3)')->execute();

# Delete

$q->delete()->from($modelname)->execute(); 





$users = Doctrine_Query::create()->select('u.name, u.password')->from('User u')->execute();
###

$pdo = new PDO('mysql:host=localhost;dbname=developpez', 'Yogui', 'motdepasse');
$db = Doctrine_Manager::connection($pdo);

###

$r = Doctrine_Manager::getInstance()->getQueryRegistry();

$r->add('User/all', 'FROM User u');
$r->add('User/byName', 'FROM User u WHERE u.name = ?');

$user = new User();

// find the user named Jack Daniels
$user = $user->findOne('byName', array('Jack Daniels'));

// find all users
$users = $user->find('all');


findBy%s($value)
$user = $userTable->findOneByUsername('jonwage');
findOneBy%s($value)


// Array fetching is the best choice whenever you need data read-only like passing it to the view for display. 
$comments = $query->select("c.id, ...")->from("Comment c")
        ->leftJoin("c.foo f")
        ->leftJoin("f.bar b")
        ->where("c.article_id = ?")
        ->execute(array(1), Doctrine::FETCH_ARRAY);
$view->comments = $comments;

# ----- #

$users = $query->select('u.name')->from('User u INDEXBY
u.id')->execute(array(), Doctrine::HYDRATE_ARRAY);

That gets you an array where the keys are the ids and the values is an
assoc array with just the name name.

foreach ($users as $key => $user) {
  echo $user['name'];

}

The HYDRATE_ARRAY option reduces a lot the overheads because you are
not instatiating new records. 

*/
?>