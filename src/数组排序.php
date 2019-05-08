<?php
/**
 * Created by PhpStorm.
 * User: zikobe
 * Date: 2019/5/8
 * Time: 14:15
 */
/**
 * 多维数组按多字段字段排序
 * @return array $result = multiArraySortByMultiFile($result,'branch_id',SORT_ASC,'third_party_id',SORT_DESC...);
 * $result = multiArraySortByMultiFile($result,'branch_id',SORT_ASC,'third_party_id',SORT_DESC...);
 * @throws Exception
 * @internal param $array
 * @internal param $array
 * @internal param $array
 * @internal param $array 参数说明：* 参数说明：
 *  1、第一个参数、第二个参数必选，第三个参数、第四个参数可选
 *  2、第一个参数为要进行排序的数组,其他参数为排序规则
 *  3、排序规则参数格式为 [$a,$b]($a为排序字段,$b为排序规则:升序SORT_ASC/降序SORT_DESC)
 */
function multiArraySortByMultiFile()
{
    $args = func_get_args();
    if (empty($args)) {
        return null;
    }
    $arr = array_shift($args);
    if (!is_array($arr)) {
        throw new exception("第一个参数不为数组");
    }
    foreach ($args as $key => $field) {
        if (is_string($field)) {
            $temp = [];
            foreach ($arr as $index => $val) {
                $temp[$index] = $val[$field];
            }
            $args[$key] = $temp;
        }
    }
    $args[] = &$arr;//引用值
    call_user_func_array('array_multisort', $args);
    $arr = array_pop($args);

    return $arr;
}