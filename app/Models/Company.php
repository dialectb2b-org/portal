<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function document()
    {
        return $this->hasOne(CompanyDocument::class,'company_id','id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function activities()
    {
        return $this->belongsToMany(SubCategory::class, 'company_activities', 'company_id', 'activity_id')->where('is_previlaged',1);
    }
    
    public function paid_activities()
    {
        return $this->belongsToMany(SubCategory::class, 'company_activities', 'company_id', 'activity_id')->where('is_previlaged',0)->withPivot('expiry_date','status');
    }

    public function locations()
    {
        return $this->belongsToMany(Region::class, 'company_locations', 'company_id', 'region_id');
    }
    
    public function current_package()
    {
        return $this->hasOne(Package::class,'id','current_plan');
    }
    
    public function payment(){
        return $this->belongsTo(Payment::class,'id','company_id');
    }
}
