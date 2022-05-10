<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper;

abstract class AbstractMapper
{
    protected function getOrganizedEmbeds(array $embeds): array
    {
        $organizedEmbeds = [];

        foreach ($embeds as $embed) {
            $explodedEmbeds = explode('.', $embed, 2);

            if (!isset($organizedEmbeds[$explodedEmbeds[0]])) {
                $organizedEmbeds[$explodedEmbeds[0]] = [];
            }

            if (count($explodedEmbeds) === 2) {
                $organizedEmbeds[$explodedEmbeds[0]][] = $explodedEmbeds[1];
            }
        }

        return $organizedEmbeds;
    }

    /**
     * @param array $entities
     * @param string[] $embeds
     *
     * @return array
     */
    public function mapArray(array $entities, array $embeds = []): array
    {
        return array_map(
            fn($entity) => $this->map($entity, $embeds),
            $entities,
        );
    }
}
