import ApiService from "./ApiService";

class NodeService extends ApiService{

    constructor() {
        super();

        this._getChildrenNodesUrl = '/tree-node/get-children';
        this._getNodeUrl = '/tree-node/get';
        this._addNodeUrl = '/tree-node/add'
        this._editNodeUrl = '/tree-node/edit'
        this._deleteNodeUrl = '/tree-node/delete'
    }

    getByParentId(parentId = null) {
        const data = parentId !== null ? {parent_id: parentId} : {};
        return this.getResource(this._getChildrenNodesUrl, data);
    }

    getById(id) {
        const data = {id: id};
        return this.getResource(this._getChildrenNodesUrl, data);
    }

    addNode(params) {
        return this.postResource(this._addNodeUrl, params)
    }

    deleteNode(nodeId) {
        return this.deleteResource(this._deleteNodeUrl + `/${nodeId}`)
    }

    updateNode(nodeId, nodeName) {
        return this.putResource(this._editNodeUrl + `/${nodeId}`, {system_name: nodeName})
    }
}

const nodeService = new NodeService();
export default nodeService;
