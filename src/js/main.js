import 'bootstrap/dist/css/bootstrap.css';
import "bootstrap-icons/font/bootstrap-icons.css";
import '../css/tree-nodes.css'

import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import NodeService from "./services/api/NodeService";

const openedClass = 'bi-arrow-down-right';
const closedClass = 'bi-arrow-right';
let deleteModal;
let editModal;
let countdown;


$(document).ready(() => {
    NodeService.getByParentId()
        .then(response => {
            const rootNodeData = response.nodes.shift()
            if (rootNodeData) {
                // Якщо root існує, відмальовуємо його
                const rootNode = createNodeElement(rootNodeData);
                $('#tree-container').append($('<ul>').addClass("tree").append(rootNode))
            } else {
                const createRootButton = $('<button>').text('Create Root').addClass('btn btn-primary').on('click', createRootNode);
                $('#tree-container').append(createRootButton);
            }
        })

    $('#confirmDelete').on('click', async function () {
        const nodeId = $(this).data('node-id');
        clearInterval(countdown);
        try {
            await NodeService.deleteNode(nodeId)
            $('li.node[data-id="' + nodeId + '"]').remove()
            deleteModal.hide()
        } catch(err) {
            console.error(err)
        }
    });

    $('#saveChanges').on('click', async function () {
        const nodeId = $(this).data('node-id');
        const newName = $('#nodeName').val();

        try {
            await NodeService.updateNode(nodeId, newName);
            $('li.node[data-id="' + nodeId + '"] .node-name').first().text(newName);
            editModal.hide();
        } catch (err) {
            console.error(err);
        }
    });
});

function createRootNode() {
    NodeService.addNode({parent_id: null, system_name: 'root_node'})
        .then(res => {
            const rootData = res.node;
            const rootNode = createNodeElement(rootData);
            const treeContainer = $('<ul>').addClass("tree").append(rootNode);
            $('#tree-container').empty().append(treeContainer);
        })
        .catch(err=>{
            console.error(err);
        })
}


function createNodeElement(nodeData) {
    const nodeId = nodeData.id;
    const nodeName = nodeData.system_name;
    const hasChildren = nodeData.has_children === '1';

    const $nodeElement = $(`<li class="node" data-id="${nodeId}">
    <span class="node-name">${nodeName}</span>
    <button type="button" class="btn btn-outline-primary btn-sm btn-add"><i class="bi bi-plus"></i></button>
    <button type="button" class="btn btn-outline-danger btn-sm btn-delete"><i class="bi bi-trash"></i></button>
    <button type="button" class="btn btn-outline-success btn-sm btn-edit"><i class="bi bi-pencil"></i></button>
</li>`);

    if (hasChildren) {
        const $expandButton = $("<i class='indicator bi " + closedClass + "'></i>");

        $nodeElement.prepend($expandButton);
        $nodeElement.addClass('branch');

        $expandButton.on('click', function () {
            expandNode(nodeId, $nodeElement);
        })
    }

    $nodeElement.find('.btn-add').on('click', function () {

        addChildToNode($nodeElement);
    });

    $nodeElement.find('.btn-edit').on('click', function () {
        const nodeId = $(this).parent().data('id');
        const nodeName = $('span', $(this).closest('li')).first().text();
        $('#nodeName').val(nodeName);
        $('#saveChanges').data('node-id', nodeId);
        editModal = new bootstrap.Modal('#editNodeModal');
        editModal.show()
    })

    $nodeElement.find('.btn-delete').on('click', function () {
        const nodeId = $(this).parent().data('id');
        $('#confirmDelete').data('node-id', nodeId);
        startTimer();
        deleteModal = new bootstrap.Modal('#deleteNodeModal');
        deleteModal.show()
    });

    return $nodeElement;
}

function expandNode(parentId, $parentNode) {

    NodeService.getByParentId( parentId )
        .then(response => {
            const children = response.nodes;

            if (children.length > 0) {
                const $ul = $('<ul>');
                children.forEach(function (child) {
                    const childNode = createNodeElement(child);
                    $ul.append(childNode);
                });
                $parentNode.append($ul);

                const expandButton = $parentNode.find('i.indicator:first');
                expandButton.removeClass(closedClass).addClass(openedClass);

                expandButton.off('click').on('click', function () {
                    collapseNode($parentNode);
                });
            }
        })
        .catch(err => {
            console.error(err)
        })

}

function collapseNode($parentNode) {
    $parentNode.find('ul').remove();

    const expandButton = $parentNode.find('i.indicator');
    expandButton.removeClass(openedClass).addClass(closedClass);

    expandButton.off('click').on('click', function () {
        const parentId = $parentNode.data('id');
        expandNode(parentId, $parentNode);
    });
}

function addChildToNode($parentNodeElement) {

    const parentId = $parentNodeElement.data('id');

    NodeService.addNode({parent_id: parentId, system_name: 'new_node'})
        .then(response => {
            const newChildNode = createNodeElement(response.node);
            let $childNodesList = $parentNodeElement.children('ul');

            if ($parentNodeElement.hasClass('branch')) {
                if (!$childNodesList.length) {
                    $parentNodeElement.find('i.indicator:first').click();
                } else {
                    $childNodesList.append(newChildNode);
                }
            } else {
                $parentNodeElement.addClass('branch');

                const $expandButton = $("<i class='indicator bi " + closedClass + "'></i>");

                $parentNodeElement.prepend($expandButton);
                $childNodesList = $('<ul>');
                $expandButton.removeClass(closedClass).addClass(openedClass);

                $expandButton.off('click').on('click', function () {
                    collapseNode($parentNodeElement);
                });

                $childNodesList.append(newChildNode);
                $parentNodeElement.append($childNodesList);
            }
        })
        .catch(err => {
            console.log(err);
        });

}

function startTimer() {
    let seconds = 20;
    countdown = setInterval(function() {
        if (seconds > 0) {
            seconds--;
            $('#timer').text(seconds);
        } else {
            clearInterval(countdown);
            deleteModal.hide()
        }
    }, 1000);
}