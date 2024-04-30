<?php

class Logger {
    private static array $instance = [];
    private string $tag;

    public static int $LOG_NONE = 0;
    public static int $LOG_ERROR = 1;
    public static int $LOG_INFO = 2;
    public static int $LOG_DEBUG = 3;

    private function __construct($tag) {
        $this->tag = $tag;
    }

    public static function getLogger($tag): Logger {
        if(!isset(self::$instance[$tag])) {
            self::$instance[$tag] = new Logger($tag);
        }

        return self::$instance[$tag];
    }

    function debug($message): void {
        if(Config::$LOG_SETTINGS["LOG_LEVEL"] >= self::$LOG_DEBUG) {
            if(!(file_exists(Config::$LOG_SETTINGS["LOG_DIRECTORY"]) && is_dir(Config::$LOG_SETTINGS["LOG_DIRECTORY"]))) {
                mkdir(Config::$LOG_SETTINGS["LOG_DIRECTORY"]);
            }
    
            $logfileName = str_replace("%date%", date("Y-m-d"), Config::$LOG_SETTINGS["LOG_FILENAME"]);
            $logfile = fopen(Config::$LOG_SETTINGS["LOG_DIRECTORY"] . $logfileName, "a");
            $lineNumber = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0]["line"] | 0;
            fwrite($logfile, PHP_EOL . "[" . date("Y-m-d H:i:s") . "] [DEBUG] [{$this->tag}:{$lineNumber}]: " . $message);
        }
    }

    function info($message): void {
        if(Config::$LOG_SETTINGS["LOG_LEVEL"] >= self::$LOG_INFO) {
            if(!(file_exists(Config::$LOG_SETTINGS["LOG_DIRECTORY"]) && is_dir(Config::$LOG_SETTINGS["LOG_DIRECTORY"]))) {
                mkdir(Config::$LOG_SETTINGS["LOG_DIRECTORY"]);
            }
    
            $logfileName = str_replace("%date%", date("Y-m-d"), Config::$LOG_SETTINGS["LOG_FILENAME"]);
            $logfile = fopen(Config::$LOG_SETTINGS["LOG_DIRECTORY"] . $logfileName, "a");
            $lineNumber = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0]["line"] | 0;
            fwrite($logfile, PHP_EOL . "[" . date("Y-m-d H:i:s") . "] [INFO] [{$this->tag}:{$lineNumber}]: " . $message);
        }
    }

    function error($message): void {
        if(Config::$LOG_SETTINGS["LOG_LEVEL"] >= self::$LOG_ERROR) {
            if(!(file_exists(Config::$LOG_SETTINGS["LOG_DIRECTORY"]) && is_dir(Config::$LOG_SETTINGS["LOG_DIRECTORY"]))) {
                mkdir(Config::$LOG_SETTINGS["LOG_DIRECTORY"]);
            }
    
            $logfileName = str_replace("%date%", date("Y-m-d"), Config::$LOG_SETTINGS["LOG_FILENAME"]);
            $logfile = fopen(Config::$LOG_SETTINGS["LOG_DIRECTORY"] . $logfileName, "a");
            $lineNumber = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0]["line"] | 0;
            fwrite($logfile, PHP_EOL . "[" . date("Y-m-d H:i:s") . "] [ERROR] [{$this->tag}:{$lineNumber}]: " . $message);
        }
    }
}
