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
        'quick',      // 快速排序
        'selection',  // 选择排序
        'merge',      // 归并排序
        'shell',      // 希尔排序
        'heap',       // 堆排序
    ];

    public function __construct(array $nums, $algorithm)
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
    protected function bubble_sort(array $nums)
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
     * 算法思想：通过构建有序序列，对于未排序数据，在已排序序列中从后向前扫描，找到相应位置插入。
     * 插入排序在实现上，通常采用in-place排序（即只需要用到O(1)的额外空间的排序），因而在从后向前扫描过程中，
     * 需要反复把已排序元素逐步向后挪位，为最新元素提供插入空间。
     * 性能分析：
     * 1、时间复杂度：O(n^2)
     * 2、空间复杂度：没有额外的存储空间，是原地排序算法
     * 3、算法稳定性：相等元素不发生交换，是稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function insertion_sort(array $nums)
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
     * 希尔排序（插入排序改进版）
     *
     * 算法思想：先将整个待排元素序列分割成若干个子序列（由相隔某个“增量”的元素组成的）分别进行直接插入排序，
     * 然后依次缩减增量再进行排序，待整个序列中的元素基本有序（增量足够小）时，再对全体元素进行一次直接插入排序。
     * 算法步骤：
     * 1. 取增量，一般取数组长度 / 2
     * 2. 按增量取得一个子数列，对子数列按插入排序的方式处理
     * 3. 将增量递减，重复1，2步骤
     * 4. 直至增量均为0，数列已经排好序
     * 性能分析：
     * 1、时间复杂度：优于O(n^2)
     * 2、空间复杂度：没有额外的存储空间，是原地排序算法
     * 3、算法稳定性：在不同的插入排序过程中，相等元素相对位置发生改变，是不稳定的排序算法
     *
     * @param $nums
     * @return array
     */
    protected function shell_sort(array $nums)
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        for ($gap = floor(count($nums) / 2); $gap > 0; $gap = floor($gap / 2)) {
            for ($i = $gap; $i < count($nums); $i++) {
                $temp = $nums[$i];
                $j = $i - $gap;
                for (; $j >= 0; $j -= $gap) {
                    if ($nums[$j] > $temp) {
                        $nums[$j + $gap] = $nums[$j];
                    } else {
                        break;
                    }
                }
                $nums[$j + $gap] = $temp;
            }
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
    protected function selection_sort(array $nums)
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
    protected function merge_sort(array $nums)
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
    protected function quick_sort(array $nums)
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

    /**
     * 堆排序（大顶堆）
     * 算法思想：1.将待排序的关键字序列（R1,R2,...Rn）构建大顶堆，此堆为初始的无序区.
     *         2.将堆顶元素R[1]与最后一个元素R[n]交换，此时得到新的无序区(R1,R2,......Rn-1)和新的有序区(Rn),
     *           且满足R[1,2...n-1]<=R[n];
     *         3.由于交换后新的堆顶R[1]可能违反堆的性质，因此需要对当前无序区(R1,R2,......Rn-1)调整为新堆，
     *           然后再次将R[1]与无序区最后一个元素交换，得到新的无序区(R1,R2....Rn-2)和新的有序区(Rn-1,Rn)。
     *           不断重复此过程直到有序区的元素个数为n-1，则整个排序过程完成。
     * 性能分析：
     * 1、时间复杂度：O(nlogn)
     * 2、空间复杂度：不需要额外的内存空间，是原地排序算法
     * 3、算法稳定性：涉及相等元素位置交换，是不稳定的排序算法
     * @param $nums
     * @return mixed
     */
    protected function heap_sort($nums)
    {
        $len = count($nums);
        if ($len <= 1) {
            return $nums;
        }

        // 建立大顶堆
        for ($i = floor($len / 2) - 1; $i >= 0; $i--) {
            $this->max_heapify($nums, $i, $len);
        }
        // 堆调整
        for ($i = $len - 1; $i >= 0; $i--) {
            $this->swap($nums[$i], $nums[0]);
            $this->max_heapify($nums, 0, $i);
        }

        return $nums;
    }

    private function max_heapify(&$nums, $start, $len)
    {
        // 建立父节点指标和子节点指标
        $dad = $start;
        $son = $dad * 2 + 1;
        if ($son >= $len) //若子节点指标超过范围直接跳出函数
            return;

        // 先比较两个子节点大小，选择最大的
        if ($son + 1 < $len && $nums[$son] < $nums[$son + 1])
            $son++;

        // 如果父节点小于子节点时，交换父子内容再继续子节点和孙节点比较
        if ($nums[$dad] < $nums[$son])
        {
            $this->swap($nums[$dad], $nums[$son]);
            $this->max_heapify($nums, $son, $len);
        }

    }

    private function swap(&$x, &$y)
    {
        $temp = $x;
        $x = $y;
        $y = $temp;
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
    '希尔排序' => 'shell',
    '选择排序' => 'selection',
    '归并排序' => 'merge',
    '快速排序' => 'quick',
    '堆排序' => 'heap'
];
$sortRet = [];
foreach ($algorithms as $algorithmName => $algorithm) {
    try {
        $startTime = microtime(true);
        $sortAlgorithm = new SortAlgorithm($testArr, $algorithm);
        $sortAlgorithm->sort();
        $endTime = microtime(true);
        $runTime = ($endTime - $startTime) * 1000;
        $sortRet[$algorithmName] = $runTime;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
asort($sortRet);
$mask = "|%-15s |%-20.20s |\n";
printf($mask, 'Name', 'Time(ms)');
foreach ($sortRet as $key=>$value){
    printf($mask, $algorithms[$key] . 'Sort', $value);
}




