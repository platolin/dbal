<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

//namespace PDODblibBundle\Doctrine\DBAL\Driver\PDODblib;
namespace Doctrine\DBAL\Driver\PDOOdbc;

/**
 * The PDO-based Dblib driver.
 *
 * @since 2.0
 */
class Driver implements \Doctrine\DBAL\Driver {
	public function connect(array $params, $username = null, $password = null, array $driverOptions = array()) {
		return new Connection( 			$this->_constructPdoDsn($params));
	}

	/**
	 * Constructs the ODBC PDO DSN.
	 *
	 * @return string  The DSN.
	 */
	private function _constructPdoDsn(array $params) {
    $dsn = 'odbc:';
    $appendFlag = false;

    if (isset($params['odbcdriver'])){
      $dsn .= "Driver=" . $params['odbcdriver'];
      $appendFlag = true;
    }

    if (isset($params['host'])) {
      $paramSeparator = ($appendFlag === true) ? ';' : '';
      $dsn .= $paramSeparator . "Server=" . $params['host'];
      $appendFlag = true;
    }

        if (isset($params['port'])) {
            $paramSeparator = ($appendFlag === true) ? ';' : '';
            $dsn .= $paramSeparator . "PORT=" . $params['port'];
            $appendFlag = true;
        }

    if (isset($params['dsn'])) {
      $paramSeparator = ($appendFlag === true) ? ';' : '';
      $dsn .= $paramSeparator . "DSN=" . $params['dsn'];
      $appendFlag = true;
    }

    if (isset($params['dbname'])) {
      $paramSeparator = ($appendFlag === true) ? ';' : '';
      $dsn .= $paramSeparator . 'Database=' . $params['dbname'];
      $appendFlag = true;
    }

    if (isset($params['user'])) {
      $paramSeparator = ($appendFlag === true) ? ';' : '';
      $dsn .= $paramSeparator . 'UID=' . $params['user'];;
      $appendFlag = true;
    }
    
    if (isset($params['password'])) {
      $paramSeparator = ($appendFlag === true) ? ';' : '';
      $dsn .= $paramSeparator . 'PWD=' . $params['password'];
      $appendFlag = true;
    }

        if (isset($params['option'])) {
            $paramSeparator = ($appendFlag === true) ? ';' : '';
            $dsn .= $paramSeparator . $params['option'];
            $appendFlag = true;
        }
//    var_dump($dsn);
//die(0);
    return $dsn;

//		$dsn = 'dblib:host=';
//
//		if (isset($params['host'])) {
//			$dsn .= $params['host'];
//		}
//
//		if (isset($params['port']) && !empty($params['port'])) {
//			$portSeparator = (PATH_SEPARATOR === ';') ? ',' : ':';
//			$dsn .= $portSeparator . $params['port'];
//		}
//
//		if (isset($params['dbname'])) {
//			$dsn .= ';dbname=' . $params['dbname'];
//		}
//		return $dsn;
	}

	public function getDatabasePlatform() {
		
		if (class_exists('\\Doctrine\\DBAL\\Platforms\\SQLServer2008Platform')) {
			return new \Doctrine\DBAL\Platforms\SQLServer2008Platform();
		}
		
		if (class_exists('\\Doctrine\\DBAL\\Platforms\\SQLServer2005Platform')) {
			return new \Doctrine\DBAL\Platforms\SQLServer2005Platform();
		}

		if (class_exists('\\Doctrine\\DBAL\\Platforms\\MsSqlPlatform')) {
			return new \Doctrine\DBAL\Platforms\MsSqlPlatform();
		}
	}

	public function getSchemaManager(\Doctrine\DBAL\Connection $conn) {
		if (class_exists('\\Doctrine\\DBAL\\Schema\\SQLServerSchemaManager')) {
			return new \Doctrine\DBAL\Schema\SQLServerSchemaManager($conn);
		}

		if (class_exists('\\Doctrine\\DBAL\\Schema\\MsSqlSchemaManager')) {
			return new \PDODblibBundle\Doctrine\DBAL\Schema\PDODblibSchemaManager($conn);
		}


	}

	public function getName() {
		return 'pdo_odbc';
	}

	public function getDatabase(\Doctrine\DBAL\Connection $conn) {
		$params = $conn->getParams();
		return $params['dbname'];
	}
}
