<?php
class Database {
    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbname;

    private $dbh;
    private $error;
    private $stmt;

    public function __construct() {
        // Look for .env file across standard project root locations
        $envLocations = [
            __DIR__ . '/../../.env',
            dirname(dirname(__DIR__)) . '/.env',
            ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/.env',
            ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/../.env',
            __DIR__ . '/../.env'
        ];

        $envFile = null;
        foreach ($envLocations as $path) {
            if (!empty($path) && file_exists($path)) {
                $envFile = $path;
                break;
            }
        }

        if ($envFile) {
            $env = @parse_ini_file($envFile);
            if ($env === false) {
                // Fallback manual parser for .env files with comments or special characters
                $env = [];
                $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line) || strpos($line, '#') === 0) continue;
                    if (strpos($line, '=') !== false) {
                        list($key, $val) = explode('=', $line, 2);
                        $env[trim($key)] = trim($val);
                    }
                }
            }
            $this->host = trim($env['DB_HOST'] ?? 'localhost', "\"'");
            $this->port = trim($env['DB_PORT'] ?? '3306', "\"'");
            $this->user = trim($env['DB_USER'] ?? 'root', "\"'");
            $this->pass = trim($env['DB_PASS'] ?? '', "\"'");
            $this->dbname = trim($env['DB_NAME'] ?? 'brgy_waste_db', "\"'");
        } else {
            // Check getenv / environment variables
            $this->host = getenv('DB_HOST') ?: 'localhost';
            $this->port = getenv('DB_PORT') ?: '3306';
            $this->user = getenv('DB_USER') ?: 'root';
            $this->pass = getenv('DB_PASS') ?: '';
            $this->dbname = getenv('DB_NAME') ?: 'brgy_waste_db';

            if (!getenv('DB_NAME') && !file_exists(__DIR__ . '/../../.env')) {
                die("
                <div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'>
                    <h2 style='color: #dc2626; margin-top: 0;'>⚙️ Missing .env Configuration</h2>
                    <p style='color: #475569; font-size: 14px;'>The <code>.env</code> file was not found in your server root directory.</p>
                    <div style='background: #f8fafc; padding: 14px; border-radius: 8px; font-family: monospace; font-size: 13px; color: #0f172a; margin: 15px 0;'>
                        <strong>Required Action on Hostinger:</strong><br>
                        1. Open Hostinger File Manager in your project root.<br>
                        2. Create a file named <code>.env</code><br>
                        3. Fill in your Hostinger MySQL database details (DB_HOST, DB_USER, DB_PASS, DB_NAME).
                    </div>
                </div>");
            }
        }
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            die("
            <div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);'>
                <h2 style='color: #dc2626; margin-top: 0;'>🔌 Database Connection Failed</h2>
                <p style='color: #475569; font-size: 14px;'>Unable to connect to MySQL database on Hostinger.</p>
                <div style='background: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 8px; font-family: monospace; font-size: 12px; color: #991b1b; margin: 12px 0;'>
                    " . htmlspecialchars($this->error) . "
                </div>
                <div style='background: #f8fafc; padding: 14px; border-radius: 8px; font-family: monospace; font-size: 13px; color: #0f172a; margin: 15px 0;'>
                    <strong>Checklist for Hostinger:</strong><br>
                    1. Verify your MySQL Database Name &amp; User in Hostinger hPanel.<br>
                    2. Check that the password in <code>.env</code> matches your Hostinger database password.<br>
                    3. Make sure you have imported your SQL tables into phpMyAdmin on Hostinger.
                </div>
            </div>");
        }
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
