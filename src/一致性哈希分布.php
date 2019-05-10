<?php

/**
 * Created by PhpStorm.
 * User: zikobe
 * Date: 2019/5/10
 * Time: 11:18
 */
class FlexibleHash
{

    private $serverList = [];  // 服务器列表
    private $isSorted = false; // 排序标识

    /**
     * @param $server
     * @return bool
     */
    public function addServer($server)
    {
        $hash = $this->mHash($server);
        if (!isset($this->serverList[$hash])) {
            $this->serverList[$hash] = $server;
        }
        $this->isSorted = false;

        return true;
    }

    /**
     * @param $server
     * @return bool
     */
    public function removeServer($server)
    {
        $hash = $this->mHash($server);
        if (isset($this->serverList[$hash])) {
            unset($this->serverList[$hash]);
        }
        $this->isSorted = false;

        return true;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function lookup($key)
    {
        $hash = $this->mHash($key);
        if (!$this->isSorted) {
            krsort($this->serverList, SORT_NUMERIC);
            $this->isSorted = true;
        }
        foreach ($this->serverList as $pos => $server) {
            if ($hash > $pos) return $server;
        }

        return $this->serverList[count($this->serverList) - 1];
    }

    /**
     * @param $key
     * @return int
     */
    private function mHash($key)
    {
        $md5 = substr(md5($key), 0, 8);
        $seed = 31;
        $hash = 0;
        for ($i = 0; $i < 8; $i++) {
            $hash = $hash * $seed + ord($md5[$i]);
            $i++;
        }

        return $hash & 0x7FFFFFFF;
    }
}

// 测试用例
$hserver = new FlexibleHash();
$hserver->addServer('192.168.1.1');
$hserver->addServer('192.168.1.2');
$hserver->addServer('192.168.1.3');
$hserver->addServer('192.168.1.4');
$hserver->addServer('192.168.1.5');

echo "save key1 in server: ", $hserver->lookup('key1'), PHP_EOL;
echo "save key2 in server: ", $hserver->lookup('key2'), PHP_EOL;
echo "================================", PHP_EOL;

$hserver->removeServer('192.168.1.4');
echo "save key1 in server: ", $hserver->lookup('key1'), PHP_EOL;
echo "save key2 in server: ", $hserver->lookup('key2'), PHP_EOL;
echo "================================", PHP_EOL;

$hserver->addServer('192.168.1.6');
echo "save key1 in server: ", $hserver->lookup('key1'), PHP_EOL;
echo "save key2 in server: ", $hserver->lookup('key2'), PHP_EOL;
echo "================================", PHP_EOL;

