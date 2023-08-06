<?php

namespace App\Controllers\Api;

use App\Models\TreeNode;
use App\Services\TreeNodeService;
use Core\Base\AbstractController;
use Exception;

/**
 * TreeNodeController class.
 */
class TreeNodeController extends AbstractController
{
    /**
     * @var TreeNode
     */
    private TreeNode $treeNode;

    /**
     * @var TreeNodeService
     */
    private TreeNodeService $treeNodeService;

    /**
     * @param $route
     */
    public function __construct($route)
    {
        parent::__construct($route);

        $this->treeNodeService = new TreeNodeService();
        $this->treeNode =  new TreeNode();
    }

    /**
     * @return void
     */
    public function getListAction(): void
    {
        $treeNodes = $this->treeNodeService->getAllTransformed();
        $this->jsonResponse($treeNodes);
    }

    /**
     * @return void
     */
    public function getAction(): void
    {
        $nodeId = (int)$_GET['id'];

        $treeNode = $this->treeNode->findById($nodeId);

        $this->jsonResponse([
            'status' => 'OK',
            'node' => $treeNode
        ]);
    }

    /**
     * @return void
     */
    public function getChildrenAction(): void
    {
        $parentNodeId = isset($_GET['parent_id']) ? (int)$_GET['parent_id'] : null;

        $treeNodes = $this->treeNode->findByParentId($parentNodeId);

        $this->jsonResponse([
            'status' => 'OK',
            'nodes' => $treeNodes
        ]);
    }

    /**
     * @throws Exception
     */
    public function addAction(): void
    {
        $this->checkMethod('POST');

        $request = $this->getRequest();
        $treeNode = [];

        $parentId = $request['parent_id'];
        $systemName = $request['system_name'] ?? 'New node';
        if (!is_null($parentId) && $this->treeNode->findById($parentId) == 0) {
            throw new Exception('Not fount parent node');
        } else if ($parentId == null && count($treeNodes = $this->treeNode->findByParentId($parentId)) > 0) {
            $treeNode = array_shift($treeNodes);
        } else {
            if( $this->treeNode->create([
                'parent_id' => $parentId,
                'system_name' => $systemName
            ]) ) {
                $treeNode = $this->treeNode->findById($this->treeNode->lastInsertId());
            }
        }

        $this->jsonResponse(['status' => 'OK', 'node' => $treeNode]);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function editAction()
    {
        $this->checkMethod('PUT');

        $request = $this->getRequest();

        $id = (int)$this->route['id'];
        $systemName = $request['system_name'] ?? '';

        if (count($this->treeNode->findById($id)) == 0) {
            throw new Exception('Not fount parent node');
        }

        $this->treeNode->update($id, [
            'system_name' => $systemName
        ]);

        $this->jsonResponse(['status' => 'OK']);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function deleteAction ()
    {
        $this->checkMethod('DELETE');

        $id = (int)$this->route['id'];
        $this->treeNode->delete($id);

        $this->jsonResponse(['status' => 'OK']);
    }

}