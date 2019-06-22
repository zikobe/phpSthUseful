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
        'bubble',    // 冒泡排序
        'insertion', // 插入排序
        'quick',     // 快速排序
        'selection', // 选择排序
        'merge',     // 归并排序
        'shell',     // 希尔排序
        'heap',      // 堆排序
        'counting',  // 计数排序
        'lsdRadix',  // 基数排序
        'bucket',    // 桶排序
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
     * 算法思想：冒泡排序是一种简单直观的排序算法，它重复地走访过要排序的数列，一次比较两个元素，如果它们的顺序错误就把它们交换过来。
     * 走访数列的工作是重复地进行直到没有再需要交换，也就是说该数列已经排序完成。
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
     *
     * @param $nums
     * @return mixed
     */
    protected function heap_sort(array $nums)
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
        if ($nums[$dad] < $nums[$son]) {
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

    /**
     * 计数排序（桶排序的一种）
     *
     * 算法思想：计数排序是一个非基于比较的排序算法，它的优势在于对一定范围内的整数排序时，它的复杂度为O(n+k)（其中k是整数的范围），
     * 快于任何比较排序算法。当然这是一种牺牲空间换取时间的做法，而且当O(k)>O(n*log(n))的时候其效率反而不如基于比较的排序
     * （基于比较的排序的时间复杂度在理论上的下限是O(n*log(n))，如归并排序，堆排序）。
     * 算法步骤：
     * 1.找出待排序的数组中最大和最小的元素；
     * 2.统计数组中每个值为i的元素出现的次数，存入数组 C 的第 i 项；
     * 3.依次统计出C[i]表示数组中小于等于i的元素出现的个数；
     * 4.从带排序列A的最后一个元素开始，将A[i]放到正确的位置（从后往前保证了排序的稳定性）。即前面又几个元素小于等于它，它就放在第几个位置。
     * 性能分析：
     * 1、时间复杂度：O(k+n)
     * 2、空间复杂度：需要额外的内存空间O(k),这是一种牺牲空间换取时间的做法
     * 3、算法稳定性：不涉及相等元素位置交换，是稳定的排序算法
     *
     * @param array $nums
     * @return array
     */
    protected function counting_sort(array $nums)
    {
        $len = count($nums);
        if ($len <= 1) {
            return $nums;
        }

        // 找出待排序数组中最大值和最小值
        $max = max($nums);
        $min = min($nums);

        // 计算待排序的数组中每个元素的个数
        $count_arr = [];
        for ($i = $min; $i <= $max; $i++) {
            $count_arr[$i] = 0;
        }
        foreach ($nums as $num) {
            $count_arr[$num]++;
        }

        // 输出结果数组
        $ret = [];
        foreach ($count_arr as $k => $c) {
            for ($i = 0; $i < $c; $i++) {
                $ret[] = $k;
            }
        }

        return $ret;
    }

    /**
     * 基数排序
     *
     * 算法思想：基数排序是一种非比较型整数排序算法，其原理是将整数按位数切割成不同的数字，然后按每个位数分别比较。
     * 基数排序法会使用到桶，顾名思义，通过将要比较的位（个位、十位、百位...），将要排序的元素分配到0~9个桶中，
     * 借以达到排序的作用，在某些时候，基数排序法的效率高于其它的比较性排序法。
     * 算法步骤：
     * 1.将所有待比较数值（正整数）统一为同样的数位长度，数位较短的数前面补零
     * 2.从最低位开始，依次进行一次排序
     * 3.这样从最低位排序一直到最高位排序完成以后，数列就变成一个有序序列
     * 性能分析：
     * 1、时间复杂度：O(k*n)，其中n是排序元素个数，k是最大数字位数
     * 2、空间复杂度：该算法的空间复杂度就是在分配元素时，使用的桶空间；所以空间复杂度为：O(10*length) = O(length)
     * 3、算法稳定性：不涉及相等元素位置交换，是稳定的排序算法
     *
     * @param array $nums
     * @return array
     */
    protected function lsdRadix_sort(array $nums)
    {
        $len = count($nums);
        if ($len <= 1) {
            return $nums;
        }

        $max = max($nums);
        $loop = $this->getLoopTimes($max);
        // 对每一位进行桶分配（1 表示个位，$loop 表示最高位）
        for ($i = 1; $i <= $loop; $i++) {
            $this->lsdRadix_sort_c($nums, $i);
        }

        return $nums;
    }

    // 获取最大数的位数,最大值的位数就是我们分配桶的次数
    private function getLoopTimes($maxNum)
    {
        $count = 0;
        $temp = $maxNum;
        do {
            $count++;
            $temp = floor($temp / 10);
        } while ($temp != 0);

        return $count;
    }

    private function lsdRadix_sort_c(&$nums, $pos)
    {
        // 初始化桶数组:
        // 第一维是 0-9 十个数
        // 第二维这样定义是因为有可能待排序的数组中的所有数的某一位上的值是一样的，这样就全挤在一个桶里面了
        $bucketArr = [];
        for ($i = 0; $i <= 9; $i++) {
            $bucketArr[$i] = array();
        }

        // 入桶
        $tempNum = pow(10, $pos - 1);
        for ($i = 0; $i < count($nums); $i++) {
            // 求数组元素pos位上的数字（pos 1代表个位 2代表十位...以此类推）
            $posIndex = ($nums[$i] / $tempNum) % 10;
            array_push($bucketArr[$posIndex], $nums[$i]);
        }

        // 还原已经排好序的桶数据到原数组中
        $i = 0;
        while ($i < count($nums)) {
            foreach ($bucketArr as $bucket) {
                if (!empty($bucket)) {
                    foreach ($bucket as $num) {
                        $nums[$i++] = $num;
                    }
                }
            }
        }
    }

    /**
     * 桶排序
     *
     * 算法思想：桶排序或所谓的箱排序的原理是将数组分到有限数量的桶子里，然后对每个桶子再分别排序（有可能再使用别的排序算法
     * 或是以递归方式继续使用桶排序进行排序），最后将各个桶中的数据有序的合并起来。假设有一组长度为N的待排序关键字序列K[1...n]。
     * 首先将这个序列划分成M个的子区间（桶）。然后基于某种映射函数，将待排序序列的关键字K映射到第i个桶中（即桶数组B的下标i），
     * 那么该关键字k就作用B[i]中的元素（每个桶B[i]都是一组大小为N/M的序列）。接着对每个桶B[i]中的所有元素进行比较排序（可以使用快速排序）。
     * 然后依次枚举输出B[0]...B[M]中的全部内容即是一个有序序列。
     * 算法步骤：
     * 1. 假设待排序的一组数统一的分布在一个范围中，并将这一范围划分成几个子范围，也就是桶
     * 2. 将待排序的一组数，分档规入这些子桶，并将桶中的数据进行排序
     * 3. 将各个桶中的数据有序的合并起来
     * 性能分析：
     * 1、时间复杂度：桶排序的平均时间复杂度为线性的O(N+C)，其中C=N*(logN-logM)。如果相对于同样的N，桶数量M越大，其效率越高，
     *             最好的时间复杂度达到O(N)
     * 2、空间复杂度：当然桶排序的空间复杂度 为O(N+M)，如果输入数据非常庞大，而桶的数量也非常多，则空间代价无疑是昂贵
     * 3、算法稳定性：桶排序中，假如升序排列，a已经在桶中，b插进来是永远都会a右边的，所以桶排序是稳定的
     *             (PS:如果采用元素插入后再分别进行桶内排序，并且桶内排序算法采用快速排序，那么就不是稳定的)
     *
     * @param array $nums
     * @return array
     */
    protected function bucket_sort(array $nums)
    {
        $len = count($nums);
        if ($len <= 1) {
            return $nums;
        }

        $min = min($nums);
        $max = max($nums);
        $n = ceil(($max - $min) / $len) + 1;
        // 设置木桶
        $buckets = [];
        for ($i = 0; $i < $n; $i++) {
            $buckets[$i] = [];
        }
        // 将每个元素放入桶
        for ($i = 0; $i < $len; $i++) {
            $index = ceil(($nums[$i] - $min) / $len); // 映射函数f(k) = (k - min) / len
            $buckets[$index][] = $nums[$i];
        }
        // 对每个桶进行排序
        $ret = [];
        for ($i = 0; $i < $n; $i++) {
            sort($buckets[$i]); // 每个bucket各自排序，或用不同的排序算法，或者递归的使用bucket sort算法(此处用函数sort代替)
            // 合并所有桶中的元素
            $ret = array_merge($ret, $buckets[$i]);
        }

        return $ret;
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
    '堆排序' => 'heap',
    '计数排序' => 'counting',
    '基数排序' => 'lsdRadix',
    '桶排序' => 'bucket'
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
asort($sortRet); // 按运行时间升序排序
$mask = "|%-15s |%-20.20s |\n";
printf($mask, 'Name', 'Time(ms)');
foreach ($sortRet as $key => $value) {
    printf($mask, $algorithms[$key] . 'Sort', $value);
}




