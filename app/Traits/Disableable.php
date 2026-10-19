<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Disableable
{
     public function activate()
     {
          if ($this->cascadeDisableables) {
               array_map(function ($disableable) {

                    if ($this->$disableable) {

                         if ($this->$disableable() instanceof BelongsTo) {
     
                              $this->$disableable->activate();
                         } else if ($this->$disableable() instanceof HasMany) {
     
                              $this->$disableable->each(function ($model) {
                                   $model->activate();
                              });
                         }
                    }
               }, $this->cascadeDisableables);
          }

          return $this->update(['active' => true]);
     }

     public function disable()
     {
          if ($this->cascadeDisableables) {
               array_map(function ($disableable) {

                    if ($this->$disableable) {

                         if ($this->$disableable() instanceof BelongsTo) {
     
                              $this->$disableable->disable();
                         } else if (
                              $this->$disableable() instanceof HasMany ||
                              $this->$disableable() instanceof BelongsToMany
                         ) {
     
                              $this->$disableable->each(function ($model) {
     
                                   $model->disable();
                              });
                         }
                    }

               }, $this->cascadeDisableables);
          }

          return $this->update(['active' => false]);
     }
}
