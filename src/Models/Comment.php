<?php

declare(strict_types=1);

namespace anurag\Commentable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kalnoy\Nestedset\NodeTrait;

class Comment extends Model
{
    use NodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

   
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): MorphTo
    {
        return $this->morphTo('creator');
    }

   
    public function createComment(Model $commentable, array $data, Model $creator): self
    {
        return $commentable->comments()->create(array_merge($data, [
            'creator_id'   => $creator->getAuthIdentifier(),
            'creator_type' => $creator->getMorphClass(),
        ]));
    }

   
    public function updateComment(int $id, array $data): bool
    {
        return (bool) static::find($id)->update($data);
    }

   
    public function deleteComment(int $id): bool
    {
        return (bool) static::find($id)->delete();
    }
}
