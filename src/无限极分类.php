<?php
/**
 * 无限极分类常用方法
 *
 * User: zikobe
 * Date: 2019/4/8
 * Time: 11:32
 */

/**
 * 数据表创建语句：
 * CREATE TABLE categories (
 * `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
 * `parent_id` TINYINT UNSIGNED NOT NULL COMMENT '上级分类',
 * `cat_name` VARCHAR(12) NOT NULL DEFAULT '' COMMENT '栏目名称',
 * `order` TINYINT NOT NULL DEFAULT 100 COMMENT '排序',
 * `created_at` INT UNSIGNED NOT NULL COMMENT '创建时间',
 * PRIMARY KEY (id)
 * )ENGINE=InnoDB CHARSET "utf8" COMMENT '栏目分类表';
 * INSERT INTO categories VALUES (1,0,'新闻',1,unix_timestamp(now())),(2,0,'图片',2,unix_timestamp(now())),(3,1,'国内新闻',1,unix_timestamp(now())),(4,1,'国际新闻',2,unix_timestamp(now())),(5,3,'北京新闻',1,unix_timestamp(now())),(6,4,'美国新闻',2,unix_timestamp(now())),(7,2,'美女图片',1,unix_timestamp(now())),(8,2,'风景图片',2,unix_timestamp(now())),(9,7,'日韩明星',1,unix_timestamp(now())),(10,9,'日本AV',1,unix_timestamp(now()));
 */

/**
 * 无限极分类树 getTree($categories)
 * @param array $data
 * @param int $parent_id
 * @param int $level
 * @return array
 */
function getTree($data = [], $parent_id = 0, $level = 0)
{
    $tree = [];
    if ($data && is_array($data)) {
        foreach ($data as $v) {
            if ($v['parent_id'] == $parent_id) {
                $tree[] = [
                    'id' => $v['id'],
                    'level' => $level,
                    'cat_name' => $v['cat_name'],
                    'parent_id' => $v['parent_id'],
                    'children' => getTree($data, $v['id'], $level + 1),
                ];
            }
        }
    }

    return $tree;
}

/**
 * 循环获取子孙树 getSubTree($categories)
 *
 * @param array $data
 * @param int $id
 * @param int $level
 * @return array
 */
function getSubTree($data = [], $id = 0, $level = 0)
{
    static $tree = [];

    foreach ($data as $key => $value) {
        if ($value['parent_id'] == $id) {
            $value['level'] = $level;
            $tree[] = $value;
            getSubTree($data, $value['id'], $level + 1);
        }
    }

    return $tree;
}

/**
 * 递归获取子孙树 getSubTree2($categories, 1)
 *
 * @param array $data
 * @param int $parent_id
 * @param int $level
 * @return array
 */
function getSubTree2($data = [], $parent_id = 0, $level = 0)
{
    $tree = [];
    if ($data && is_array($data)) {
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['level'] = $level;
                $tree[] = $value;
                $tree = array_merge($tree, getSubTree2($data, $value['id'], $level + 1));
            }
        }
    }

    return $tree;
}

/**
 * 通过pid获取所有上级分类 常用于面包屑导航 getParentsByParentId2($categories, 9)
 *
 * @param array $data
 * @param $parent_id
 * @return array
 */
function getParentsByParentId($data = [], $parent_id)
{
    static $categories = [];

    if ($data && is_array($data)) {
        foreach ($data as $item) {
            if ($item['id'] == $parent_id) {
                $categories[] = $item;
                getParentsByParentId($data, $item['parent_id']);
            }
        }
    }

    return $categories;
}

/**
 * 无限极分类获取某根节点下所有子节点(包括根节点)
 * @param array $data
 * @param int $pid
 * @param string $parentKey
 * @return array
 */
function getSubIds($data = [], $pid = 1, $parentKey = 'parent_id')
{
    $sub_ids = [intval($pid)]; //初始化结果
    $pid_arr = [$pid]; //根节点
    do {
        $sub_pid_arr = [];
        $end_loop = false;
        foreach ($pid_arr as $pid) {
            foreach ($data as $key => $value) {
                if ($value[$parentKey] == $pid) {
                    $sub_ids[] = $value['id']; //将子级添加到最终结果中
                    $sub_pid_arr[] = $value['id']; //将子级id保存起来用来下轮循环他的子级
                    unset($data[$key]); //剔除已经添加的子级
                    $end_loop = true;
                }
            }
        }
        $pid_arr = $sub_pid_arr; //继续循环找到子级的子级
    } while ($end_loop == true);

    return $sub_ids;
}
