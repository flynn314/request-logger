<?php
namespace Flynn314\RequestLogger\Model;

use Carbon\Carbon;
use Flynn314\RequestLogger\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string uuid
 * @property string method
 * @property string url
 * @property string request_headers
 * @property string request_content
 * @property string response_headers
 * @property string response_content
 * @property string query
 * @property string ip
 * @property int|null user_token_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int updated_at_diff_s
 */
class Record extends Model
{
    use Uuids;

    protected $primaryKey = 'uuid';

    protected $table = 'logs_requests';

    protected $casts = [
        'request_headers' => 'array',
        'response_headers' => 'array',
        'query' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function save(array $options = []): bool
    {
        if ($this->getKey()) {
            $this->attributes['updated_at_diff_s'] = $this->created_at->diff($this->updated_at)->s;
        }

        return parent::save($options);
    }
}
