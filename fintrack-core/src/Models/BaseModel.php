<?php 
namespace FinTrack\Core\Models;

use Illuminate\Eloquent\Model;

class BaseModel extends Model {
    use SoftDeletes, HasUuid, HasOrganization;

    protected $primaryKey = 'id';
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'id',
        'organization_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}