<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassroomCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        foreach($this->collection as $model){

            $data[] = [
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
                'cover_image_url' => $model->cover_image_url,
                'meta' => [
                    'section' => $model->section,
                    'room' => $model->room,
                    'subject' => $model->subject,
                    'theme' => $model->theme,
                    'students_count' => $model->students_count ?? 0,
                    'teachers_count' => $model->teachers_count ?? 0,
                ],
                'user' => [
                    'name' => $model->user->name,
                ]
            ];
        }

        return [
            'data' => $data,
        ];
    }
}
