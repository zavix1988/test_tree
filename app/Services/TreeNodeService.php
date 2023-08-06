<?php

namespace App\Services;

use App\Models\TreeNode;

class TreeNodeService
{
    /**
     * @var TreeNode
     */
    private TreeNode $treeNodesModel;

    public function __construct()
    {
        $this->treeNodesModel = new TreeNode();
    }

    public function getAllTransformed()
    {
        $treeNodes = $this->treeNodesModel->findAll('id');

        return $this->transformToTree($treeNodes);
    }

    protected function transformToTree(array $elements, $parentId = null) {
        $branch = [];
        foreach ($elements as $element) {
            if ( $parentId == $element['parent_id']) {
                $children = $this->transformToTree($elements, $element['id']);
                $element['children'] = !empty($children) ? $children : [];
                $branch[] = $element;
            }
        }
        return $branch;
    }

}