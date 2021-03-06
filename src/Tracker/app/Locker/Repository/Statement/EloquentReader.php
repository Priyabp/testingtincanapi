<?php namespace App\Locker\Repository\Statement;

use App\Models\Statement;
use DB;
use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Locker\Helpers\Helpers as Helpers;
use App\Locker\Helpers\Exceptions as Exceptions;

abstract class EloquentReader
{
    protected $model = Statement::class;

    /**
     * Constructs a query restricted by the given options.
     * @param [String => Mixed] $opts
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    protected function where(Options $opts)
    {
        $scopes = $opts->getOpt('scopes');
        $query = (new $this->model)->where('lrs_id', new \MongoDB\BSON\ObjectID($opts->getOpt('lrs_id')));

        if (in_array('all', $scopes) || in_array('all/read', $scopes) || in_array('statements/read', $scopes)) {
            // Get all statements.
        } else if (in_array('statements/read/mine', $scopes)) {
            $query = $query->where('client_id', $opts->getOpt('client')->_id);
        } else {
            throw new Exceptions\Exception('Unauthorized request.', 401);
        }

        return $query;
    }

    /**
     * Gets the statement from the model as an Object.
     * @param Model $model
     * @return \stdClass
     */
    protected function formatModel(Model $model)
    {
        return Helpers::replaceHtmlEntity($model->statement);
    }

    public function getCollection()
    {
        return DB::collection((new $this->model)->getTable());
    }
}
