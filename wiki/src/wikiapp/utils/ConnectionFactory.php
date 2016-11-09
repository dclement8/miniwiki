<?php
namespace wikiapp\utils;

class ConnectionFactory {
    private static $config;
    private static $db;

    public static function setConfig($filename = ROOT.'/conf/config.ini') {
        if(!file_exists($filename))
            throw new \Exception('Unable to load config file ! ('.$filename.')');
        if(!(self::$config = parse_ini_file($filename, true)))
            throw new \Exception('Unable to parse config file !');
    }

    public static function makeConnection() {
        if(isset(self::$db)) // already a connection
            return self::$db;
        if(empty(self::$config))
            self::setConfig();
        try {
            self::$db = new \PDO(
            self::$config['DB']['driver'].":host=".self::$config['DB']['server'].";
            dbname=".self::$config['DB']['db'].";
            charset=UTF8",
            self::$config['DB']['user'],
            self::$config['DB']['password']);
            self::$db->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_WARNING);
        } catch(PDOException $e) {
            echo 'Unable to connect to database !';
        }
        return self::$db;
    }
}
