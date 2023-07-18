<?php

namespace tests\_support;

use PHPUnit\Runner\StandardTestSuiteLoader;

class ConsoleInterceptor extends \php_user_filter
{
    public static $value = '';
    private $name_filter;
    private $watcher;
    private $showConsole;

    public function __construct()
    {
        $id = time();
        $this->name_filter = "consoleInterceptor.{$id}";

        if(!in_array($this->name_filter, stream_get_filters())){
            stream_filter_register($this->name_filter, $this::class);
        }

        $this->showConsole = false;

    }

    public function filter($in, $out, &$consumed, $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            self::$value .= $bucket->data;
            $consumed += $bucket->datalen;
            if($this->showConsole){
                stream_bucket_append($out, $bucket);
            }
        }

        return PSFS_PASS_ON;
    }

    public function setShowConsole($value){
        if(is_bool($value)){
            $this->showConsole;
        }
    }

    public function startWatch(){
        $this->watcher = stream_filter_append(STDOUT, $this->name_filter);
    }

    public function stopWatch(){
        if($this->watcher){
            stream_filter_remove($this->watcher);
        }
    }
}