<?php

namespace Marvel\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shop extends Model
{
    use Sluggable;

    protected $table = 'shops';

    public $guarded = [];
    protected $casts = [
        'logo' => 'json',
        'cover_image' => 'json',
        'address' => 'json',
        'settings' => 'json',
        'premium'=>'boolean'
    ];
    protected $fillable=[
        'approval_token_id',
        'shop',
        'name',
        'description',
        'cover_image',
        'logo',
        'address',
        'settings',
        'country_id',
        'owner_id',
        'premium_plan_id'
    ];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return BelongsTo
     */
    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class, 'shop_id');
    }
    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shop_id');
    }

    /**
     * @return HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'shop_id');
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    /**
     * @return HasMany
     */
    public function withdraws(): HasMany
    {
        return $this->hasMany(Withdraw::class, 'shop_id');
    }

    /**
     * @return BelongsToMany
     */
    public function staffs(): HasMany
    {
        return $this->hasMany(User::class, 'shop_id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_shop');
    }
    public function approvalToken(): hasOne
    {
        return $this->hasOne(ApprovalTokens::class, 'id','approval_token_id');
    }


    public function country(): hasOne
    {
        return $this->hasOne(Countries::class, 'id','country_id');
    }

    public function plan():hasOne{

        return $this->hasOne(PremiumPlans::class,'id','premium_plan_id');

    }
    public function subscription():hasOne{

        return $this->hasOne(PremiumSubscriptions::class,'shop_id','id');

    }
}
