<?php

namespace App\Traits;

trait ValidatesExistence
{
        /**
     * Validate the existence of an ID in the given table.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $table The name of the table to check the ID against.
     * @return array Validated data from the request.
     */
    public function validateExistence($request, $table)
    {
        if($request->has('id'))
        {
            return $request->validate([
                'id' => 'required|integer|exists:' . $table . ',id',
            ]);
        }
        elseif($request->has('ids'))
        {
            return $request->validate([
                'ids' => 'array',
                'ids.*' => 'integer|exists:' . $table . ',id',
            ]);
        }

        return [];
    }
}
