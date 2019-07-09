<?php

/**
 * Created by PhpStorm.
 * User: zikobe
 * Date: 2019/7/1
 * Time: 13:37
 */
class Client
{
    public static function Main()
    {
        try {
            //实现文件的自动加载
            function autoload($class)
            {
                include strtolower($class) . '.php';
            }

            spl_autoload_register('autoload');
            $arr = array(13, 21, 5, 16, 4, 11, 8, 7, 9, 10, 12);
            $tree = new Bst();  //搜索二叉树
//            $tree = new Avl();  //平衡二叉树
//            $tree = new Rbt();  //红黑树
            $tree->init($arr);   //树的初始化
            $tree->Delete(21);
//            $tree->Insert(3);
//            $tree->MidOrder();  //树的中序遍历（这也是调试的一个手段，看看数字是否从小到大排序）
            $image = new image($tree);
            $image->show();  //显示图像
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

Client::Main();
