<?php
/**
 * 文件内容常用操作类
 *
 * User: zikobe
 * Date: 2019/3/30
 * Time: 14:10
 */
class FileContentOperate
{
    private $filePath = ''; // 文件路径
    private $cacheFileName = ''; // 缓存key值
    private $redis = null; // redis实例

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->cacheFileName = md5($filePath);
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }

    /**
     * 替换指定字符串
     * @param string $replaceCont 替换内容
     * @param string $target 被替换内容
     */
    public function replaceTarget($replaceCont, $target)
    {
        $this->cacheFileContent();

        $fileCont = file_get_contents($this->filePath);
        $replacedFileCont = str_replace($target, $replaceCont, $fileCont);
        file_put_contents($this->filePath, $replacedFileCont);
    }

    /**
     * 指定内容后新起一行插入内容
     * @param string $insertCont 插入内容
     * @param string $target 查找内容
     */
    public function insertAfterTarget($insertCont, $target)
    {
        $this->cacheFileContent();

        $result = null;
        $fileCont = file_get_contents($this->filePath);
        $targetIndex = strpos($fileCont, $target); // 查找目标字符串的位置

        if ($targetIndex !== false) {
            // 找到target的后一个换行符
            $chLineIndex = strpos(substr($fileCont, $targetIndex), "\n") + $targetIndex;
            if ($chLineIndex !== false) {
                // 插入需要插入的内容
                $result = substr($fileCont, 0, $chLineIndex + 1) . $insertCont . "\n"
                    . substr($fileCont, $chLineIndex + 1);
                file_put_contents($this->filePath, $result);
            }
        }
    }

    /**
     * 删除内容所在行
     * @param string $target 查找内容
     * @param bool $all 是否删除所有行，否的话只删除第一行
     */
    public function delTargetLine($target, $all = false)
    {
        $this->cacheFileContent();

        $result = null;
        $fileCont = file_get_contents($this->filePath);
        $targetIndex = strpos($fileCont, $target); // 查找目标字符串的位置
        if ($targetIndex !== false) {
            // 找到target的前一个换行符
            $preChLineIndex = strrpos(substr($fileCont, 0, $targetIndex + 1), "\n");
            $preChLineIndex = $preChLineIndex === false ? 0 : $preChLineIndex;
            // 找到target的后一个换行符
            $AfterChLineIndex = strpos(substr($fileCont, $targetIndex), "\n") + $targetIndex;
            $AfterChLineIndex = $AfterChLineIndex === false ? (filesize($this->filePath) - 1) : $AfterChLineIndex;
            // 重新写入删掉指定行后的内容
            $result = substr($fileCont, 0, $preChLineIndex + 1)
                . substr($fileCont, $AfterChLineIndex + 1);
            file_put_contents($this->filePath, $result);
            if($all){
                $this->delTargetLine($target, $all); // 递归继续删除
            }else{
                exit(0);
            }
        }else{
            exit(0); // 找不到目标内容，递归终止
        }
    }

    /**
     * 获取某段内容的行号
     * @param string $target 待查找字段
     * @param bool $all 是否匹配所有行
     * @return array
     */
    public function getTargetLineNum($target, $all = true)
    {
        $fp = fopen($this->filePath, "r") or die("Unable to open file!\n");
        $lineNumArr = array();
        $lineNum = 0;
        while (!feof($fp)) {
            $lineNum++;
            $lineCont = fgets($fp);
            if (strstr($lineCont, $target)) {
                $lineNumArr[] = $lineNum;
                if (!$all) {
                    break;
                }
            }
        }
        return $lineNumArr;
    }

    /**
     *  打印文件内容
     */
    public function showFileContent()
    {
        $file = fopen($this->filePath, "r") or ("Unable to open file!\n");
        echo fread($file,filesize($this->filePath)), PHP_EOL;
        fclose($file);
    }

    /**
     * 缓存文件内容
     */
    private function cacheFileContent()
    {
        $this->redis->rPush($this->cacheFileName, file_get_contents($this->filePath));
        $this->redis->expire($this->cacheFileName, 3600);
    }

    /**
     * 文件内容回滚到上一次操作前内容
     */
    public function fileContentRollback()
    {
        if($this->redis->exists($this->cacheFileName)) {
            file_put_contents($this->filePath, $this->redis->rPop($this->cacheFileName));
        }
    }
}
/** 测试 */
//$test = new FileContentOperate('../public/fileContentOperateTest');
//$test->showFileContent();
//print_r($test->getTargetLineNum('夜'));
//$test->insertAfterTarget('This is a new line', '禁不住');
//$test->replaceTarget('Night', '夜');
//$test->delTargetLine('李白', true);
//$test->fileContentRollback();

