<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')

            ->whereHas('titles', function ($query) {
                $query->where('slug', 'manager');
            });
    }


    /**
     * Get the salesperson for the project.
     */
    public function purchasingperson()
    {
        return $this->belongsTo(User::class, 'sales_id')


            ->whereHas('titles', function ($query) {
                $query->where('slug', 'purchasing');
            });
    }
    public function city()
    {

        return $this->belongsTo(City::class);
    }




    public function projectEmployees()
    {
        return $this->hasMany(ProjectUser::class)
            ->where('management_type','purchase_order')
            ->orderBy('order');
    }


    public function man()
    {

        return $this->belongsToMany(User::class, 'project_users')
            ->whereHas('titles', function ($query) {
                $query->where('slug', 'manager');
            });
    }

    public function purchasing()
    {

        return $this->belongsToMany(User::class, 'project_users')
            ->whereHas('titles', function ($query) {
                $query->where('slug', 'purchasing');
            });
    }
    //employees
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_users')
            ->orderBy('order');
    }


    public function nonEmployeeUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_users')
            // ->whereHas('titles', function ($query) {
            //     $query->where('slug', '!=', 'employee');
            // })
            ->orderBy('order');
    }

    public function EmployeeUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_users')
            ->whereHas('titles', function ($query) {
                $query->where('slug', 'employee');
            });
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_employees')
            ->whereHas('titles', function ($query) {
                $query->where('slug', 'employee');
            });
    }
    public function projectUserEmployees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_employees')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'موظف');
            });
    }
    public function purchaseOrderEmployees(): HasMany
    {
        return $this->hasMany(ProjectUser::class)
            ->where('management_type', 'purchase_order')
            ->orderBy('order');
    }

    public function disbursementOrderEmployees(): HasMany
    {
        return $this->hasMany(ProjectUser::class)->where('management_type', 'disbursement_order')->orderBy('order');
    }


    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function projectIndices():HasMany
    {
        return $this->hasMany(ProjectIndex::class);
    }
    public function correspondent():MorphOne
    {
        return $this->morphOne(Correspondents::class, 'modelable');
    }
    public function prices():HasMany
    {
        return $this->hasMany(CompetitionPrice::class,'project_id');
    }
    public function purchaseOrders():HasMany
    {
        return $this->hasMany(PurchaseOrder::class,'project_id');
    }
}
