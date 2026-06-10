<?php


declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

class Character
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * 
     *
     * @param array{name: string, class: string, strength: int, agility: int, presence: int, abilities: array} $data
     * @return array{success: bool, message: string, id?: int}
     */
    public function create(int $userId, array $data): array
    {
        $validation = $this->validateCharacterData($data);
        if (!$validation['success']) {
            return $validation;
        }

        $abilitiesJson = json_encode($data['abilities'], JSON_UNESCAPED_UNICODE);

        $stmt = $this->db->prepare(
            'INSERT INTO characters
                (user_id, character_name, class, strength, agility, presence, abilities)
             VALUES
                (:user_id, :character_name, :class, :strength, :agility, :presence, :abilities)'
        );

        $stmt->execute([
            ':user_id'         => $userId,
            ':character_name'  => $data['name'],
            ':class'           => $data['class'],
            ':strength'        => $data['strength'],
            ':agility'         => $data['agility'],
            ':presence'        => $data['presence'],
            ':abilities'       => $abilitiesJson,
        ]);

        return [
            'success' => true,
            'message' => 'Character sheet inscribed into the grimoire.',
            'id'      => (int) $this->db->lastInsertId(),
        ];
    }

    /**
     * 
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, character_name, class, strength, agility, presence, abilities, created_at
             FROM characters
             WHERE user_id = :user_id
             ORDER BY created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        $characters = $stmt->fetchAll();

        foreach ($characters as &$char) {
            $char['abilities'] = $this->decodeAbilities($char['abilities']);
        }

        return $characters;
    }

    
    public function getByIdAndUserId(int $characterId, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, character_name, class, strength, agility, presence, abilities, created_at
             FROM characters
             WHERE id = :id AND user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute([
            ':id'      => $characterId,
            ':user_id' => $userId,
        ]);

        $char = $stmt->fetch();

        if (!$char) {
            return null;
        }

        $char['abilities'] = $this->decodeAbilities($char['abilities']);

        return $char;
    }

    /**
     * 
     *
     * @param array{name: string, class: string, strength: int, agility: int, presence: int, abilities: array} $data
     * @return array{success: bool, message: string}
     */
    public function update(int $characterId, int $userId, array $data): array
    {
        if ($this->getByIdAndUserId($characterId, $userId) === null) {
            return ['success' => false, 'message' => 'Character not found or access denied.'];
        }

        $validation = $this->validateCharacterData($data);
        if (!$validation['success']) {
            return $validation;
        }

        $abilitiesJson = json_encode($data['abilities'], JSON_UNESCAPED_UNICODE);

        $stmt = $this->db->prepare(
            'UPDATE characters SET
                character_name = :character_name,
                class          = :class,
                strength       = :strength,
                agility        = :agility,
                presence       = :presence,
                abilities      = :abilities
             WHERE id = :id AND user_id = :user_id'
        );

        $stmt->execute([
            ':character_name' => $data['name'],
            ':class'          => $data['class'],
            ':strength'       => $data['strength'],
            ':agility'        => $data['agility'],
            ':presence'       => $data['presence'],
            ':abilities'      => $abilitiesJson,
            ':id'             => $characterId,
            ':user_id'        => $userId,
        ]);

        return ['success' => true, 'message' => 'Character sheet updated.'];
    }

    /**
     * 
     *
     * @return array{success: bool, message: string}
     */
    public function delete(int $characterId, int $userId): array
    {
        $stmt = $this->db->prepare(
            'DELETE FROM characters WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            ':id'      => $characterId,
            ':user_id' => $userId,
        ]);

        if ($stmt->rowCount() === 0) {
            return ['success' => false, 'message' => 'Character not found or access denied.'];
        }

        return ['success' => true, 'message' => 'Character consigned to oblivion.'];
    }

    /**
     * 
     *
     * @return array{success: bool, message: string}
     */
    private function validateCharacterData(array $data): array
    {
        $name = trim($data['name'] ?? '');
        $class = trim($data['class'] ?? '');

        if ($name === '' || strlen($name) > 100) {
            return ['success' => false, 'message' => 'Character name is required (max 100 characters).'];
        }

        if ($class === '' || strlen($class) > 80) {
            return ['success' => false, 'message' => 'Class is required (max 80 characters).'];
        }

        foreach (['strength', 'agility', 'presence'] as $stat) {
            $value = $data[$stat] ?? null;
            if (!is_numeric($value) || (int) $value < 1 || (int) $value > 20) {
                return ['success' => false, 'message' => ucfirst($stat) . ' must be between 1 and 20.'];
            }
        }

        if (!isset($data['abilities']) || !is_array($data['abilities'])) {
            return ['success' => false, 'message' => 'At least one ability/skill is required.'];
        }

        $abilities = [];
        foreach ($data['abilities'] as $ability) {
            $abilityName = trim($ability['name'] ?? '');
            $rank = $ability['rank'] ?? null;

            if ($abilityName === '') {
                continue;
            }

            if (!is_numeric($rank) || (int) $rank < 0 || (int) $rank > 10) {
                return ['success' => false, 'message' => 'Ability ranks must be between 0 and 10.'];
            }

            $abilities[] = [
                'name' => $abilityName,
                'rank' => (int) $rank,
            ];
        }

        if (count($abilities) === 0) {
            return ['success' => false, 'message' => 'At least one ability/skill is required.'];
        }

        return ['success' => true, 'message' => ''];
    }

    /**
     * 
     *
     * @return array<int, array{name: string, rank: int}>
     */
    private function decodeAbilities(mixed $json): array
    {
        if (is_array($json)) {
            return $json;
        }

        $decoded = json_decode((string) $json, true);

        return is_array($decoded) ? $decoded : [];
    }
}
