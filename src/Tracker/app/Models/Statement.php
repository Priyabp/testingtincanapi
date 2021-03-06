<?php
namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Statement extends Eloquent {

  protected $collection = 'statements';
  protected $hidden = ['_id', 'created_at', 'updated_at'];
  protected $fillable = ['statement', 'active', 'voided', 'refs', 'lrs_id', 'timestamp', 'stored'];

  public function lrs(){
    return $this->belongsTo('Lrs');
  }

}
