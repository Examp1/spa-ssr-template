<?php

namespace App\Modules\Constructor\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface HasConstructor
{
    /**
     * Defined constructor components
     *
     * @return array
     */
    public function constructorComponents(): array;

    /**
     * @return MorphOne
     */
    public function constructor(): MorphOne;

    /**
     * @return mixed
     */
    public function getConstructorData();

    /**
     * Available Constructor components
     *
     * @return array
     */
    public function availableConstructorComponents(): array;

    /**
     * Defined Constructor components
     *
     * @param array $input
     * @return array
     */
    public function definedConstructorComponents(array $input): array;

    /**
     * Rules validation for constructor component fields
     *
     * @param array $input
     * @return array
     */
    public function rulesConstructorComponentFields(array $input): array;

    /**
     * Messages validation for constructor component fields
     *
     * @param array $input
     * @return array
     */
    public function messagesConstructorComponentFields(array $input): array;

    /**
     * Return Id for relation constructor
     *
     * @param int|null $id
     * @return mixed
     */
    public function entityConstructorId($id = null);
}
