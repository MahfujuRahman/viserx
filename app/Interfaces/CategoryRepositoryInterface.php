<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAll(int $perPage = 15);

    public function getById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
