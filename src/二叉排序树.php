<?php

/**
 * Created by PhpStorm.
 * User: zikobe
 * Date: 2019/6/27
 * Time: 10:24
 */
class Node
{
    public $data;
    public $left = null;
    public $right = null;

    public function __construct($data)
    {
        $this->data = $data;
    }
}

/**
 * 二叉排序树定义：
 * 二叉排序树，又称为二叉查找树。 二叉排序树或者是一棵空树，或者是具有以下性质的二叉树：
 * 若其左子树不为空，则左子树上的所有节点的值均小于它的根结点的值；
 * 若其右子树不为空，则右子树上的所有节点的值均大于它的根结点的值；
 * 左右子树又分别是二叉排序树。
 *
 * Class BinarySortedTree
 */
class BinarySortedTree
{
    private $tree;

    public function getTree()
    {
        return $this->tree;
    }

    /**
     * 插入
     * @param int $data
     * @throws Exception
     */
    public function insert(int $data)
    {
        if (!$this->tree) {
            $this->tree = new Node($data);
            return;
        }
        $p = $this->tree;
        while ($p) {
            if ($data < $p->data) {
                if (!$p->left) {
                    $p->left = new Node($data);
                    return;
                }
                $p = $p->left;
            } elseif ($data > $p->data) {
                if (!$p->right) {
                    $p->right = new Node($data);
                    return;
                }
                $p = $p->right;
            } else {
                throw new Exception("值{$data}已存在");
            }
        }
    }

    /**
     * 查找
     * @param int $data
     * @return null
     */
    public function find(int $data)
    {
        $p = $this->tree;
        while ($p) {
            if ($data < $p->data) {
                $p = $p->left;
            } elseif ($data > $p->data) {
                $p = $p->right;
            } else {
                return $p;
            }
        }
        return null;
    }

    /**
     * 删除，分三种情况：
     * 1.如果要删除的节点没有子节点，我们只需要直接将父节点中，指向要删除节点的指针置为 null
     * 2.如果要删除的节点只有一个子节点（只有左子节点或者右子节点），我们只需要更新父节点中，指向要删除节点的指针，
     *   让它指向要删除节点的子节点就可以了。
     * 3.如果要删除的节点有两个子节点，这就比较复杂了。我们需要找到这个节点的右子树中的最小节点，把它替换到要删除的节点上。
     *   然后再删除掉这个最小节点，因为最小节点肯定没有左子节点（如果有左子结点，那就不是最小节点了），
     *   所以，我们可以应用上面两条规则来删除这个最小节点。
     * @param int $data
     */
    public function delete(int $data)
    {
        if (!$this->tree) {
            return;
        }

        $p = $this->tree; // 待删除节点
        $pp = null; // p的父节点
        // 查找待删除节点
        while ($p && $p->data != $data) {
            $pp = $p;
            if ($p->data < $data) {
                $p = $p->right;
            } else {
                $p = $p->left;
            }
        }
        // 待删除数据不存在
        if (!$p) {
            return;
        }
        // 待删除节点有两个子节点
        if ($p->left && $p->right) {
            $minP = $p->right; // 右子树中的最小节点
            $minPP = $p; // $minP 的父节点
            // 查找右子树中的最小节点
            while ($minP->left) {
                $minPP = $minP;
                $minP = $minP->left;
            }
            $p->data = $minP->data;  // 将 $minP 的数据设置到 $p 中
            // 待删除节点转移至最小节点
            $p = $minP;
            $pp = $minPP;
        }
        $child = null;
        if ($p->left) {
            $child = $p->left;
        } elseif ($p->right) {
            $child = $p->right;
        } else {
            $child = null;
        }
        if (!$pp) {
            $this->tree = $child;   // 删除的是根节点
        } elseif ($pp->left == $p) {
            $pp->left = $child;
        } else {
            $pp->right = $child;
        }
    }

    /**
     * 前序遍历-对内
     * @param $tree
     */
    private function pre_order_traverse_c($tree)
    {
        if ($tree == null) {
            return;
        }
        printf("%s ", $tree->data);
        $this->pre_order_traverse_c($tree->left);
        $this->pre_order_traverse_c($tree->right);
    }

    /**
     * 前序遍历-对外
     */
    public function preOrderTraverse(){
        $this->pre_order_traverse_c($this->tree);
    }

    /**
     * 中序遍历-对内
     * @param $tree
     */
    private function mid_order_traverse_c($tree)
    {
        if ($tree == null) {
            return;
        }
        $this->mid_order_traverse_c($tree->left);
        printf("%s ", $tree->data);
        $this->mid_order_traverse_c($tree->right);
    }

    /**
     * 中序遍历-对外
     */
    public function midOrderTraverse()
    {
        $this->mid_order_traverse_c($this->tree);
    }

    /**
     * 后序遍历-对内
     * @param $tree
     */
    private function post_order_traverse_c($tree)
    {
        if ($tree == null) {
            return;
        }
        $this->post_order_traverse_c($tree->left);
        $this->post_order_traverse_c($tree->right);
        printf("%s ", $tree->data);
    }

    /**
     * 后序遍历-对外
     */
    public function PostOrderTraverse()
    {
        $this->post_order_traverse_c($this->tree);
    }

    public function getTreeHeight()
    {
        if (!$this->tree) {
            return 0;
        }

        $height = 1;
        $lp = $this->tree;
        while ($lp->left) {
            $height++;
            $lp = $lp->left;
        }
        $rp = $this->tree;
        while ($rp->right) {
            $height++;
            $rp = $rp->right;
        }

        return $height;
    }

}

/**
 * 测试
 */
$tree = new BinarySortedTree();
$tree->insert(3);
$tree->insert(2);
$tree->insert(5);
$tree->insert(1);
$tree->insert(4);
$tree->insert(9);
$tree->insert(8);
$tree->insert(7);
$tree->insert(6);
$tree->midOrderTraverse();
echo "\n";

$tree->delete(7);
$tree->midOrderTraverse();
echo "\n";

$tree->delete(3);
$tree->midOrderTraverse();
echo "\n";

