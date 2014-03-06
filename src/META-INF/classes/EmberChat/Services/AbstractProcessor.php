<?php

namespace EmberChat\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use EmberChat\Entities\User;
use TechDivision\ApplicationServer\Interfaces\ApplicationInterface;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class AbstractProcessor
{

    /**
     * Datasource name to use.
     *
     * @var string
     */
    protected $datasourceName = 'EmberChat';

    /**
     * Relative path to the folder with the database entries.
     *
     * @var string
     */
    protected $pathToEntities = 'WEB-INF/classes/EmberChat/Entities';

    /**
     * The application instance that provides the entity manager.
     *
     * @var ApplicationInterface
     */
    protected $application;

    /**
     * Initializes the session bean with the Application instance.
     *
     * Checks on every start if the database already exists, if not
     * the database will be created immediately.
     *
     * @param ApplicationInterface $application
     *            The application instance
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application)
    {

        try {

            // set the application instance and initialize the connection parameters
            $this->setApplication($application);
            $this->initConnectionParameters();

            // check if the database already exists, if not create it
            $tool = new SchemaValidator($this->getEntityManager());
            if ($tool->schemaInSyncWithMetadata() === false) {
                $this->createSchema();
            }


        } catch (\Doctrine\DBAL\DBALException $e) {
            // doesn't do anything here, because SQLite is not enabled of updating the schema
        }

        try {
            $this->createAdmin();
        } catch (\Exception $e) {
        }
    }

    /**
     * Return's the path to the doctrine entities.
     *
     * @return string The path to the doctrine entities
     */
    public function getPathToEntities()
    {
        return $this->pathToEntities;
    }

    /**
     * Return's the datasource name to use.
     *
     * @return string The datasource name
     */
    public function getDatasourceName()
    {
        return $this->datasourceName;
    }

    /**
     * The application instance providing the database connection.
     *
     * @param
     *            \TechDivision\ApplicationServer\Interfaces\ApplicationInterface The application instance
     *
     * @return void
     */
    public function setApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * The application instance providing the database connection.
     *
     * @return Application The application instance
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * The database connection parameters used to connect to Doctrine.
     *
     * @param array $connectionParameters
     *            The Doctrine database connection parameters
     *
     * @return
     *
     */
    public function setConnectionParameters(array $connectionParameters = array())
    {
        $this->connectionParameters = $connectionParameters;
    }

    /**
     * Returns the database connection parameters used to connect to Doctrine.
     *
     * @return array The Doctrine database connection parameters
     */
    public function getConnectionParameters()
    {
        return $this->connectionParameters;
    }

    /**
     * Return's the initial context instance.
     *
     * @return \TechDivision\ApplicationServer\InitialContext The initial context instance
     */
    public function getInitialContext()
    {
        return $this->getApplication()->getInitialContext();
    }

    /**
     * Return's the system configuration
     *
     * @return \TechDivision\ApplicationServer\Api\Node\NodeInterface The system configuration
     */
    public function getSystemConfiguration()
    {
        return $this->getInitialContext()->getSystemConfiguration();
    }

    /**
     * Return's the array with the datasources.
     *
     * @return array The array with the datasources
     */
    public function getDatasources()
    {
        return $this->getSystemConfiguration()->getDatasources();
    }

    /**
     * Return's the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManager The initialized Doctrine entity manager
     */
    public function getEntityManager()
    {
        $pathToEntities = array(
            $this->getApplication()->getWebappPath() . DIRECTORY_SEPARATOR . $this->getPathToEntities()
        );
        $metadataConfiguration = Setup::createAnnotationMetadataConfiguration($pathToEntities, true);
        //error_log(var_export($this->getConnectionParameters(), true));
        return EntityManager::create($this->getConnectionParameters(), $metadataConfiguration);
    }

    /**
     * Initializes the database connection parameters necessary
     * to connect to the database using Doctrine.
     *
     * @return void
     */
    public function initConnectionParameters()
    {

        // iterate over the found database sources
        foreach ($this->getDatasources() as $datasourceNode) {

            // if the datasource is related to the session bean
            if ($datasourceNode->getName() == $this->getDatasourceName()) {

                // initialize the database node
                /** @var \TechDivision\ApplicationServer\Api\Node\DatabaseNode $databaseNode */
                $databaseNode = $datasourceNode->getDatabase();

                // initialize the connection parameters
                $connectionParameters = array(
                    'driver' => $databaseNode->getDriver()
                        ->getNodeValue()
                        ->__toString(),
                    'user' => $databaseNode->getUser()
                        ->getNodeValue()
                        ->__toString(),
                    'password' => $databaseNode->getPassword()
                        ->getNodeValue()
                        ->__toString(),
                    'dbname' => $databaseNode->getDatabaseName()
                        ->getNodeValue()
                        ->__toString(),
                    'host' => $databaseNode->getDatabaseHost()
                        ->getNodeValue()
                        ->__toString()

                );

                // set the connection parameters
                $this->setConnectionParameters($connectionParameters);
            }
        }
    }

    /**
     * Deletes the database schema and creates it new.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function createSchema()
    {
        // load the entity manager and the schema tool
        $entityManager = $this->getEntityManager();
        $tool = new SchemaTool($entityManager);

        // initialize the schema data from the entities
        $classes = array(
            $entityManager->getClassMetadata('EmberChat\Entities\User'),
            $entityManager->getClassMetadata('EmberChat\Entities\Room')
        );

        // drop the schema if it already exists and create it new
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }

    /**
     * Create the initial admin user
     * Credentials:
     * auth = admin
     * password = password
     */
    public function createAdmin()
    {
        $admin = new User();
        $admin->setId('ceaee2ddbac8ffc4731bfc926efdbc6819dc7171626ef3b3969244e07605caa4');
        $admin->setForename('Admin');
        $admin->setSurname('');
        $admin->setAuth('admin');
        // password=password
        $admin->setPassword(hash('sha256', 'password'));
        $admin->setAdmin(true);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($admin);
        $entityManager->flush();
    }
}