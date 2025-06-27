<?php

namespace App\Observers;

use App\Models\SubPart;
use App\Models\MasterPart; // Make sure to import MasterPart

class SubPartObserver
{
    /**
     * Handle the SubPart "created" event.
     */
    public function created(SubPart $subPart): void
    {
        $this->updateMasterPartPrice($subPart->masterPart);
    }

    /**
     * Handle the SubPart "updated" event.
     */
    public function updated(SubPart $subPart): void
    {
        // Check if price was changed or if part_number (master part foreign key) was changed
        if ($subPart->isDirty('price') || $subPart->isDirty('part_number')) {
            $this->updateMasterPartPrice($subPart->masterPart);

            // If part_number was changed, update the old master part's price as well
            if ($subPart->isDirty('part_number')) {
                $oldMasterPartNumber = $subPart->getOriginal('part_number');
                if ($oldMasterPartNumber) {
                    $oldMasterPart = MasterPart::find($oldMasterPartNumber);
                    if ($oldMasterPart) {
                        $this->updateMasterPartPrice($oldMasterPart);
                    }
                }
            }
        }
    }

    /**
     * Handle the SubPart "deleted" event.
     */
    public function deleted(SubPart $subPart): void
    {
        $this->updateMasterPartPrice($subPart->masterPart);
    }

    /**
     * Handle the SubPart "restored" event.
     */
    public function restored(SubPart $subPart): void
    {
        $this->updateMasterPartPrice($subPart->masterPart);
    }

    /**
     * Handle the SubPart "force deleted" event.
     */
    public function forceDeleted(SubPart $subPart): void
    {
        // If your SubPart model uses soft deletes and you force delete,
        // ensure the master part price is updated.
        // If not using soft deletes, this is less critical here.
        $this->updateMasterPartPrice($subPart->masterPart);
    }

    /**
     * Updates the master part's price based on its subparts.
     */
    protected function updateMasterPartPrice(?MasterPart $masterPart): void
    {
        if ($masterPart) {
            $newPrice = $masterPart->subParts()->sum('price');
            $masterPart->update(['part_price' => $newPrice]);
        }
    }
}