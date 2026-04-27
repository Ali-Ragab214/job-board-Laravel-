<?php

namespace App\Services\Skill;

use App\Models\Skill;
use App\DTOs\Skill\SkillDTO;
use App\Exceptions\SkillNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class SkillService implements SkillServiceInterface
{
    public function createSkill(string $name): SkillDTO
    {
        $skill = Skill::query()->create(['name' => $name]);

        return $this->mapToDTO($skill);
    }

    public function getAllSkills(int $perPage = 15): LengthAwarePaginator
    {
        $skills = Skill::query()
            ->latest('created_at')
            ->paginate($perPage);

        $skills->getCollection()->transform(fn($skill) => $this->mapToDTO($skill));

        return $skills;
    }

    public function getSkillById(int $id): SkillDTO
    {
        $skill = Skill::query()->find($id);

        if (!$skill) {
            throw new SkillNotFoundException("Skill with ID $id not found");
        }

        return $this->mapToDTO($skill);
    }

    public function updateSkill(int $id, string $name): SkillDTO
    {
        $skill = Skill::query()->find($id);

        if (!$skill) {
            throw new SkillNotFoundException("Skill with ID $id not found");
        }

        $skill->update(['name' => $name]);

        return $this->mapToDTO($skill);
    }

    public function deleteSkill(int $id): void
    {
        $skill = Skill::query()->find($id);

        if (!$skill) {
            throw new SkillNotFoundException("Skill with ID $id not found");
        }

        $skill->delete();
    }

    private function mapToDTO(Skill $skill): SkillDTO
    {
        return new SkillDTO(
            id: $skill->id,
            name: $skill->name,
            created_at: $skill->created_at,
            updated_at: $skill->updated_at,
        );
    }
}
