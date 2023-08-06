<?php

namespace App\Models;

use Core\Base\AbstractModel;

class TreeNode extends AbstractModel
{
    /**
     *
     */
    const PARENT_ID = 'parent_id';
    /**
     *
     */
    const SYSTEM_NAME = 'system_name';

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'tree_nodes';

    /**
     * Get node by id
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        $sql = "SELECT
                    {$this->table}.id,
                    {$this->table}.parent_id,
                    {$this->table}.system_name,
                    CASE WHEN EXISTS (SELECT 1 FROM tree_nodes AS child WHERE child.parent_id = {$this->table}.id) THEN TRUE ELSE FALSE END AS has_children
                FROM {$this->table} 
                WHERE id = ?";

        return $this->pdo->getSingle($sql, [$id]);
    }

    /**
     * Get nodes by parent_id
     *
     * @param $id
     * @return mixed
     */
    public function findByParentId($id = null)
    {
        $sql = "SELECT
                    {$this->table}.id,
                    {$this->table}.parent_id,
                    {$this->table}.system_name,
                    CASE WHEN EXISTS (SELECT 1 FROM tree_nodes AS child WHERE child.parent_id = {$this->table}.id) THEN TRUE ELSE FALSE END AS has_children
                FROM {$this->table} WHERE " . self::PARENT_ID;

        $sql .= is_null($id) ? " IS NULL" : " = ?";

        return $this->pdo->query($sql, [$id]);
    }

    /**
     * Create new node.
     *
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        $sql = "INSERT INTO {$this->table} ( " . self::PARENT_ID . " , " . self::SYSTEM_NAME . " ) VALUES (?, ?)";
        return $this->pdo->execute($sql,[$params['parent_id'], $params['system_name']]);
    }

    /**
     * Update node name.
     *
     * @param int $id
     * @param array $params
     * @return mixed
     */
    public function update(int $id, array $params)
    {
        $sql = "UPDATE {$this->table} SET ". self::SYSTEM_NAME . " = ? WHERE id = ?";
        return $this->pdo->execute($sql,[$params['system_name'], $id]);
    }

    /**
     * Delete node.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->pdo->execute($sql,[$id]);
    }
}