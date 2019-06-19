<?php

/**
 * Created by PhpStorm.
 * User: zikobe
 * Date: 2019/6/18
 * Time: 15:25
 */
class SortAlgorithm
{
    private $nums = [];
    private $algorithm = [];
    private $allowed_algorithms = [
        'bubble',     // 冒泡排序
        'insertion',  // 插入排序
        'selection',  // 选择排序
        'merge',      // 归并排序
        'quick',      // 快速排序
    ];

    public function __construct($nums, $algorithm)
    {
        $this->nums = $nums;
        if (!in_array($algorithm, $this->allowed_algorithms)) {
            throw new Exception('the algorithm must be one of the following: ' . implode(', ', $this->allowed_algorithms) . "\n");
        }
        $this->algorithm = $algorithm;
    }

    public function sort()
    {
        return call_user_func_array([__CLASS__, $this->algorithm . '_sort'], [$this->nums]);
    }

    /**
     * 冒泡排序
     *
     * 性能分析：
     * 1、时间复杂度：O(n^2)
     * 2、空间复杂度：只涉及相邻元素的交换，是原地排序算法
     * 3、算法稳定性：相等元素不发生交换，是稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function bubble_sort($nums)
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        for ($i = 0; $i < count($nums); $i++) {
            $flag = false; // 标记一次循环是否有需要交换的元素
            for ($j = 0; $j < count($nums) - $i - 1; $j++) {
                if ($nums[$j] > $nums[$j + 1]) {
                    $temp = $nums[$j];
                    $nums[$j] = $nums[$j + 1];
                    $nums[$j + 1] = $temp;
                    $flag = true;
                }
            }
            if (!$flag) {
                break; // 本次循环没有需要交换的元素，整个序列排好已经完成
            }
        }

        return $nums;
    }

    /**
     * 插入排序
     *
     * 性能分析：
     * 1、时间复杂度：O(n^2)
     * 2、空间复杂度：没有额外的存储空间，是原地排序算法
     * 3、算法稳定性：相等元素不发生交换，是稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function insertion_sort($nums)
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        for ($i = 1; $i < count($nums); $i++) {
            $temp = $nums[$i];
            $j = $i - 1;
            for (; $j >= 0; $j--) {
                if ($nums[$j] > $temp) {
                    $nums[$j + 1] = $nums[$j];
                } else {
                    break;
                }
            }
            $nums[$j + 1] = $temp;
        }

        return $nums;
    }

    /**
     * 选择排序
     *
     * 算法思想：类似插入排序，区别在于选择排序每次从未排序区间找到最小元素，将其直接放到已排序区间的末尾
     * 性能分析：
     * 1、时间复杂度：O(n^2)
     * 2、空间复杂度：没有额外的存储空间，是原地排序算法
     * 3、算法稳定性：涉及相等元素的前后顺序位置变动，是不稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function selection_sort($nums)
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        for ($i = 0; $i < count($nums) - 1; $i++) {
            $min = $i;
            for ($j = $i + 1; $j < count($nums); $j++) {
                if ($nums[$j] < $nums[$min]) {
                    $min = $j;
                }
            }
            if ($min != $i) {
                $temp = $nums[$i];
                $nums[$i] = $nums[$min];
                $nums[$min] = $temp;
            }
        }

        return $nums;
    }

    /**
     * 归并排序
     *
     * 算法思想：该算法采用分治法，将两个（或两个以上）有序表合并成一个新的有序表，即把待排序序列分为若干子序列，
     * 使每个子序列有序，再将已有序的子序列合并，得到完全有序的序列。
     * 性能分析：
     * 1、时间复杂度：O(nlogn)
     * 2、空间复杂度：需要额外的存储空间O(n)
     * 3、算法稳定性：不涉及相等元素位置交换，是稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function merge_sort($nums)
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        $this->merge_sort_c($nums, 0, count($nums) - 1);

        return $nums;
    }

    private function merge_sort_c(&$arr, $left, $right)
    {
        if ($left < $right) {
            // 找出中间索引
            $mid = floor(($left + $right) / 2);
            // 对应左边数组进行递归
            $this->merge_sort_c($arr, $left, $mid);
            // 对应右边数组进行递归
            $this->merge_sort_c($arr, $mid + 1, $right);
            // 合并
            $this->merge($arr, $left, $mid, $right);
        }
    }

    private function merge(&$arr, $left, $mid, $right)
    {
        $i = $left;    // 左数组下标
        $j = $mid + 1; // 右数组下标
        $tempArr = []; // 临时合并数组
        while ($i <= $mid && $j <= $right) {
            if ($arr[$i] < $arr[$j]) {
                $tempArr[] = $arr[$i++];
            } else {
                $tempArr[] = $arr[$j++];
            }
        }
        while ($i <= $mid) {
            $tempArr[] = $arr[$i++];
        }
        while ($j <= $right) {
            $tempArr[] = $arr[$j++];
        }
        // 将合并序列复制到原始序列中
        for ($k = 0; $k < count($tempArr); $k++) {
            $arr[$left + $k] = $tempArr[$k];
        }
    }

    /**
     * 快速排序
     *
     * 算法思想：如果要排序数组中下标从 p 到 r 之间的一组数据，我们选择 p 到 r 之间的任意一个数据作为 pivot（分区点）。
     * 我们遍历 p 到 r 之间的数据，将小于 pivot 的放到左边，将大于 pivot 的放到右边，将 pivot 放到中间。
     * 经过这一步骤之后，数组 p 到 r 之间的数据就被分成了三个部分，前面 p 到 q-1 之间都是小于 pivot 的，中间是 pivot，
     * 后面的 q+1 到 r 之间是大于 pivot 的。根据分治、递归的处理思想，我们可以用递归排序下标从 p 到 q-1 之间的数据和下标
     * 从 q+1 到 r 之间的数据，直到区间缩小为 1，就说明所有的数据都有序了。
     * 性能分析：
     * 1、时间复杂度：O(nlogn)
     * 2、空间复杂度：不需要像归并排序那样做合并操作，也就不需要额外的内存空间，是原地排序算法
     * 3、算法稳定性：涉及相等元素位置交换，是不稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function quick_sort($nums)
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        $this->quick_sort_c($nums, 0, count($nums) - 1);

        return $nums;
    }

    private function quick_sort_c(&$nums, $p, $r)
    {
        if ($p >= $r) {
            return;
        }

        $q = $this->partition($nums, $p, $r);
        $this->quick_sort_c($nums, $p, $q - 1);
        $this->quick_sort_c($nums, $q + 1, $r);
    }

    private function partition(&$nums, $p, $r)
    {
        $pivot = $nums[$r];
        $i = $p;
        for ($j = $p; $j < $r; $j++) {
            // 原理：将比$pivot小的数丢到[$p...$i-1]中，剩下的[$i..$j]区间都是比$pivot大的
            if ($nums[$j] < $pivot) {
                if ($i != $j) {
                    $temp = $nums[$i];
                    $nums[$i] = $nums[$j];
                    $nums[$j] = $temp;
                }
                $i++;
            }
        }

        // 最后将 $pivot 放到中间，并返回 $i
        $temp = $nums[$i];
        $nums[$i] = $pivot;
        $nums[$r] = $temp;

        return $i;
    }
}

/**
 * 测试代码
 */
echo "请输入测试样本长度：";
$length = trim(fgets(STDIN));
$testArr = [];
for ($i = 0; $i < $length; $i++) {
    $testArr[] = rand(1, $length * 10);
}

$algorithms = [
    '冒泡排序' => 'bubble',
    '插入排序' => 'insertion',
    '选择排序' => 'selection',
    '归并排序' => 'merge',
    '快速排序' => 'quick'
];
foreach ($algorithms as $algorithmName => $algorithm) {
    try{
        $startTime = microtime(true);
        $sortAlgorithm = new SortAlgorithm($testArr, $algorithm);
        $sortAlgorithm->sort();
        $endTime = microtime(true);
        $runTime = ($endTime - $startTime) * 1000 . ' ms';
        echo "{$algorithmName} => {$runTime}", PHP_EOL;
    }catch (Exception $e){
        echo $e->getMessage();
    }
}




