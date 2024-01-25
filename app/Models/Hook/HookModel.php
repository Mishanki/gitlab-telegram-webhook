<?php

namespace App\Models\Hook;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Hook\HookModel.
 *
 * @property int $id
 * @property string $event
 * @property string $hash
 * @property string $body
 * @property null|int $event_id
 * @property null|int $message_id
 * @property null|string $short_body
 * @property null|string $render
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @method static Builder|HookModel newModelQuery()
 * @method static Builder|HookModel newQuery()
 * @method static Builder|HookModel query()
 * @method static Builder|HookModel whereBody($value)
 * @method static Builder|HookModel whereCreatedAt($value)
 * @method static Builder|HookModel whereEvent($value)
 * @method static Builder|HookModel whereEventId($value)
 * @method static Builder|HookModel whereHash($value)
 * @method static Builder|HookModel whereId($value)
 * @method static Builder|HookModel whereMessageId($value)
 * @method static Builder|HookModel whereRender($value)
 * @method static Builder|HookModel whereShortBody($value)
 * @method static Builder|HookModel whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class HookModel extends Model
{
    use HasFactory;

    /* @var bool */
    public $timestamps = true;

    /** @var string */
    protected $table = 'hook';

    /** @var bool */
    protected static $unguarded = true;

    protected $casts = [
        'body' => 'array',
        'render' => 'array',
        'short_body' => 'array',
    ];
}
