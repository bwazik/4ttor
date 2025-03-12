<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait PreventDeletionIfRelated
{
    /**
     * Get names of related models with existing records.
     *
     * @param Model $model
     * @param array $relationships
     * @return array
     */
    public function getRelatedRelationshipNames(Model|Collection $model, array $relationships): array
    {
        $relatedRecords = [];

        if ($model instanceof Collection) {
            foreach ($model as $singleModel) {
                $singleModelRecords = $this->getRelatedRelationshipNames($singleModel, $relationships);

                // Merge results while preserving array structure
                foreach ($singleModelRecords as $relation => $modelIds) {
                    $relatedRecords[$relation] = array_merge($relatedRecords[$relation] ?? [], $modelIds);
                }
            }
        } else {
            foreach ($relationships as $relation) {
                // Check if the relationship exists and fetch related record IDs
                $relatedIds = $model->$relation()->exists() ? [$model->id] : [];
                if (!empty($relatedIds)) {
                    $relatedRecords[$relation] = array_merge($relatedRecords[$relation] ?? [], $relatedIds);
                }
            }
        }

        return $relatedRecords;
    }

    /**
     * Check if a model or collection of models has related records and return the dependency error message if necessary.
     *
     * @param Model|Collection $models
     * @param array $relationships
     * @param string $transModelKey
     * @return array|null
     */
    public function checkForSingleDependencies($models, array $relationships, string $transModelKey): ?array
    {
        $accountTranslations = [
            'admin/studentAccount.studentAccount' => trans('main.studentAccount'),
            'admin/teacherAccount.teacherAccount' => trans('main.teacherAccount')
        ];

        // Ensure we work with a collection of models (even if a single model is passed)
        if (!$models instanceof Collection) {
            $models = collect([$models]);
        }

        foreach ($models as $model) {
            // Get the related records for the specified relationships
            $relatedRecords = $this->getRelatedRelationshipNames($model, $relationships);

            // If there are related records, we need to block the deletion and show a message
            if (!empty($relatedRecords)) {
                $dependencyMessages = [];

                foreach ($relatedRecords as $relation => $relatedItems) {
                    if (!empty($relatedItems)) {
                        $relationName = trans("admin/{$relation}.{$relation}");

                        $dependencyMessages[] = $accountTranslations[$relationName] ?? $relationName;
                    }
                }

                $dependencyList = implode(', ', $dependencyMessages);

                $message = trans('main.errorDependencies', [
                    'model' => trans($transModelKey),
                    'name' => $model->name,
                    'dependency' => $dependencyList,
                ]);

                return [
                    'status' => 'error',
                    'message' => $message,
                ];
            }
        }

        // No dependencies found for all models, return null to indicate no issue
        return null;
    }

    /**
     * Check if a model or collection of models has related records and return the dependency error message if necessary.
     *
     * @param Collection $records
     * @param array $relationships
     * @param string $modelName
     * @return array|null
     */
    public function checkForMultipleDependencies($records, $relationships, $modelName)
    {
        $dependencyMessages = [];
        $relatedRecordNames = [];

        // Loop through each relationship to check for dependencies
        foreach ($relationships as $relation) {
            $relatedRecords = $this->getRelatedRelationshipNames($records, [$relation]);

            if (!empty($relatedRecords[$relation])) {
                $relationName = trans("admin/{$relation}.{$relation}");
                $dependencyMessages[] = $relationName;

                // Collect the names of records that have this specific relationship
                $relatedIds = $relatedRecords[$relation];
                $relatedRecordsWithRelation = $records->whereIn('id', $relatedIds);

                foreach ($relatedRecordsWithRelation as $record) {
                    if (!in_array($record->name, $relatedRecordNames)) {
                        $relatedRecordNames[] = $record->name;
                    }
                }
            }
        }

        if (!empty($dependencyMessages)) {
            $recordNames = implode(', ', $relatedRecordNames);
            $dependencyList = implode(', ', $dependencyMessages);

            $message = trans('main.errorDependencies', [
                'model' => trans($modelName),
                'name' => $recordNames,
                'dependency' => $dependencyList,
            ]);

            return [
                'status' => 'error',
                'message' => $message,
            ];
        }

        // If no dependencies, return null (indicating no issues)
        return null;
    }
}
