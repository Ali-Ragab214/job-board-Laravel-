<?php

namespace App\Services\Skill;

use App\DTOs\Skill\SkillDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface SkillServiceInterface
{
    public function createSkill(string $name): SkillDTO;

    public function getAllSkills(int $perPage = 15): LengthAwarePaginator;

    public function getSkillById(int $id): SkillDTO;

    public function updateSkill(int $id, string $name): SkillDTO;

    public function deleteSkill(int $id): void;
}
